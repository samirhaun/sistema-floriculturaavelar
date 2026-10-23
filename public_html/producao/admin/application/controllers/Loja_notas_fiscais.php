<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_notas_fiscais extends TEC_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loja/notas_fiscais_model');
        $this->load->library('focus_nfe');
        $this->set_menu_active(array('menu' => 'loja', 'submenu' => 'loja-notas-fiscais'));
    }

    private function autorizar()
    {
        if (!check_habilidade('visualizar_pedidos')) {
            show_error('Acesso negado.', 403);
        }
    }

    public function lista()
    {
        $this->autorizar();
        $inicio = $this->input->get('inicio') ?: date('Y-m-01');
        $fim = $this->input->get('fim') ?: date('Y-m-d');
        $status = $this->input->get('status');
        $data = array('inicio' => $inicio, 'fim' => $fim, 'status' => $status,
            'notas' => $this->notas_fiscais_model->listar($inicio, $fim, $status));
        $this->montaTela('notas_fiscais/lista', $data);
    }

    public function emitir()
    {
        $this->autorizar();
        $pedido_id = (int) $this->input->post('pedido_id');
        if (!$pedido_id) show_error('Pedido nao informado.', 400);
        $this->load->library('nfce_service');
        try {
            $nota = $this->nfce_service->emitir_pedido($pedido_id);
            if ($nota && $nota->status === 'autorizada') {
                redirect('loja/nfce/imprimir?pedido='.$pedido_id);
                return;
            }
            $motivo = ($nota && !empty($nota->erro)) ? $nota->erro : 'Consulte o relatorio fiscal.';
            $mensagem = urlencode('A NFC-e nao foi autorizada: '.$motivo);
        } catch (Exception $e) {
            log_message('error', 'NFC-e pedido #'.$pedido_id.': '.$e->getMessage());
            $mensagem = urlencode($e->getMessage());
        }
        redirect('loja/detalhes-pedido?id='.$pedido_id.'&type=error&title=NFC-e&message='.$mensagem);
    }

    public function imprimir()
    {
        $this->autorizar();
        $nota = $this->nota_autorizada((int) $this->input->get('pedido'));
        try {
            $arquivo = $this->focus_nfe->baixar_arquivo($nota->danfe_url);
            $html = $arquivo['conteudo'];
            // A NFC-e usa o mesmo comando de impressao do recibo atual. A
            // escolha da impressora continua sendo a impressora padrao do
            // navegador/caixa, sem depender de integracao local adicional.
            $retornar = $this->input->get('retornar') === '1';
            $script = '<script>window.onload=function(){ window.print(); };';
            if ($retornar) {
                $script .= 'window.onafterprint=function(){ window.location.href='
                    .json_encode(base_url('loja/pedidos')).'; };';
            }
            $script .= '</script>';
            $html = stripos($html, '</body>') !== false ? str_ireplace('</body>', $script.'</body>', $html) : $html.$script;
            $this->output->set_content_type('text/html; charset=utf-8')->set_output($html);
        } catch (Exception $e) {
            show_error('Nao foi possivel obter a DANFC-e: '.$e->getMessage(), 502);
        }
    }

    public function xml()
    {
        $this->autorizar();
        $nota = $this->nota_autorizada((int) $this->input->get('pedido'));
        try {
            $arquivo = $this->focus_nfe->baixar_arquivo($nota->xml_url);
            $nome = 'NFCe-'.$nota->serie.'-'.$nota->numero.'.xml';
            $this->output->set_content_type('application/xml')
                ->set_header('Content-Disposition: attachment; filename="'.$nome.'"')
                ->set_output($arquivo['conteudo']);
        } catch (Exception $e) {
            show_error('Nao foi possivel obter o XML: '.$e->getMessage(), 502);
        }
    }

    public function sintegra()
    {
        $this->autorizar();
        $this->set_menu_active(array('menu' => 'loja', 'submenu' => 'loja-sintegra'));
        $mes = $this->mes_referencia();
        list($inicio, $fim) = $this->periodo($mes);

        $data = array('mes' => $mes, 'conferencia' => null, 'erro' => null, 'notification' => null);
        if ($this->input->get('type')) {
            $data['notification'] = array(
                'type' => $this->input->get('type'),
                'title' => $this->input->get('title'),
                'message' => $this->input->get('message'),
            );
        }
        $this->load->library('sintegra_service');
        try {
            $data['conferencia'] = $this->sintegra_service->conferencia($inicio, $fim);
        } catch (Exception $e) {
            $data['erro'] = $e->getMessage();
        }
        $this->montaTela('notas_fiscais/sintegra', $data);
    }

    public function sintegra_download()
    {
        $this->autorizar();
        $mes = $this->mes_referencia();
        list($inicio, $fim) = $this->periodo($mes);

        $this->load->library('sintegra_service');
        try {
            $conteudo = $this->sintegra_service->gerar($inicio, $fim);
        } catch (Exception $e) {
            log_message('error', 'SINTEGRA '.$mes.': '.$e->getMessage());
            redirect('loja/sintegra?mes='.$mes.'&type=error&title=SINTEGRA&message='.urlencode($e->getMessage()));
            return;
        }

        $config = $this->config->item('fiscal');
        $razao = isset($config['estabelecimento']['razao_social']) ? $config['estabelecimento']['razao_social'] : 'ESTABELECIMENTO';
        $nome = 'SINTEGRA_'.$razao.'_'.date('m_Y', strtotime($inicio)).'.txt';
        $this->output->set_content_type('text/plain; charset=iso-8859-1')
            ->set_header('Content-Disposition: attachment; filename="'.$nome.'"')
            ->set_header('Content-Length: '.strlen($conteudo))
            ->set_output($conteudo);
    }

    /**
     * Importa XMLs de NF-e de fornecedor para os registros 50 de entrada.
     * Nao movimenta estoque nem contas a pagar.
     */
    public function sintegra_importar_entradas()
    {
        $this->autorizar();
        $mes = $this->mes_referencia_post();
        $this->load->library('sintegra_service');
        if (!$this->sintegra_model->entradas_disponiveis()) {
            $this->voltar_sintegra($mes, 'error', 'Execute a migration 021 antes de importar notas de entrada.');
            return;
        }

        $finalidade = (string) $this->input->post('finalidade');
        $arquivos = array();
        if (!empty($_FILES['xml_entradas']['name']) && is_array($_FILES['xml_entradas']['name'])) {
            foreach ($_FILES['xml_entradas']['name'] as $i => $nome) {
                if ($nome === '') continue;
                $arquivos[] = array(
                    'name' => $nome,
                    'tmp_name' => $_FILES['xml_entradas']['tmp_name'][$i],
                    'error' => $_FILES['xml_entradas']['error'][$i],
                );
            }
        }
        if (!$arquivos) {
            $this->voltar_sintegra($mes, 'error', 'Selecione pelo menos um arquivo XML.');
            return;
        }

        $importadas = 0;
        $outro_mes = 0;
        $avisos = array();
        foreach ($arquivos as $arquivo) {
            $nome = $arquivo['name'];
            if ($arquivo['error'] !== UPLOAD_ERR_OK) { $avisos[] = $nome.': falha no envio.'; continue; }
            if (strtolower(pathinfo($nome, PATHINFO_EXTENSION)) !== 'xml') { $avisos[] = $nome.': a extensao deve ser .xml.'; continue; }
            $conteudo = file_get_contents($arquivo['tmp_name']);
            if ($conteudo === false || strlen($conteudo) > 10 * 1024 * 1024) { $avisos[] = $nome.': arquivo ilegivel ou maior que 10 MB.'; continue; }

            try {
                $lido = $this->sintegra_service->ler_entrada_xml($conteudo, $finalidade);
            } catch (RuntimeException $e) {
                $avisos[] = $nome.': '.$e->getMessage();
                continue;
            }
            $existente = $this->sintegra_model->entrada_por_chave($lido['nota']['chave_nfe']);
            if ($existente) {
                $avisos[] = $nome.': nota ja importada (entrada em '.date('d/m/Y', strtotime($existente->data_entrada)).').';
                continue;
            }
            if (!$this->sintegra_model->inserir_entrada($lido['nota'], $lido['cfops'])) {
                $avisos[] = $nome.': nao foi possivel salvar no banco.';
                continue;
            }
            $importadas++;
            if (substr($lido['nota']['data_entrada'], 0, 7) !== $mes) $outro_mes++;
        }

        $mensagem = $importadas.' nota(s) de entrada importada(s).';
        if ($outro_mes) $mensagem .= ' '.$outro_mes.' delas tem entrada em outro mes e aparece(m) no SINTEGRA daquele mes.';
        if ($avisos) $mensagem .= ' Nao importadas: '.implode(' | ', $avisos);
        $this->voltar_sintegra($mes, $avisos ? 'warning' : 'success', $mensagem);
    }

    public function sintegra_entrada_xml()
    {
        $this->autorizar();
        $this->load->model('loja/sintegra_model');
        $entrada = $this->sintegra_model->entradas_disponiveis()
            ? $this->sintegra_model->entrada_xml($this->input->get('id'))
            : null;
        if (!$entrada) show_error('Nota de entrada nao encontrada.', 404);
        $this->output->set_content_type('application/xml; charset=utf-8')
            ->set_header('Content-Disposition: attachment; filename="NFe_'.$entrada->chave_nfe.'.xml"')
            ->set_output($entrada->xml_conteudo);
    }

    public function sintegra_excluir_entrada()
    {
        $this->autorizar();
        $mes = $this->mes_referencia_post();
        $this->load->model('loja/sintegra_model');
        $ok = $this->sintegra_model->entradas_disponiveis()
            && $this->sintegra_model->excluir_entrada($this->input->post('id'));
        $this->voltar_sintegra($mes, $ok ? 'success' : 'error', $ok ? 'Nota de entrada removida do SINTEGRA.' : 'Nota de entrada nao encontrada.');
    }

    private function voltar_sintegra($mes, $tipo, $mensagem)
    {
        redirect('loja/sintegra?mes='.$mes.'&type='.$tipo.'&title=SINTEGRA&message='.urlencode($mensagem));
    }

    private function mes_referencia_post()
    {
        $mes = (string) $this->input->post('mes');
        return preg_match('/^\d{4}-\d{2}$/', $mes) ? $mes : date('Y-m');
    }

    private function mes_referencia()
    {
        $mes = (string) $this->input->get('mes');
        return preg_match('/^\d{4}-\d{2}$/', $mes) ? $mes : date('Y-m');
    }

    private function periodo($mes)
    {
        $inicio = $mes.'-01';
        return array($inicio, date('Y-m-t', strtotime($inicio)));
    }

    private function nota_autorizada($pedido_id)
    {
        $nota = $this->notas_fiscais_model->buscar_por_pedido($pedido_id);
        if (!$nota || $nota->status !== 'autorizada' || empty($nota->danfe_url)) {
            show_error('NFC-e autorizada nao encontrada para este pedido.', 404);
        }
        return $nota;
    }
}
