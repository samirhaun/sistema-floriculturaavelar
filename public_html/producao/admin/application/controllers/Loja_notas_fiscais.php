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
            $mensagem = urlencode('A NFC-e nao foi autorizada. Consulte o relatorio fiscal.');
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

        $data = array('mes' => $mes, 'conferencia' => null, 'erro' => null);
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
