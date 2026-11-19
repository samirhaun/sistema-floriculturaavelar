<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_conciliacao extends TEC_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!check_habilidade('relatorios_fluxo_caixa_pedidos')) {
            show_error('Acesso negado.', 403);
        }
        $this->load->model('loja/conciliacao_model');
        $this->load->library('sicoob_api');
        $this->set_menu_active(array('menu' => 'loja', 'submenu' => 'loja-conciliacao'));
    }

    public function index()
    {
        $inicio = $this->data_valida($this->input->get('inicio'), date('Y-m-01'));
        $fim = $this->data_valida($this->input->get('fim'), date('Y-m-d'));
        if ($fim < $inicio) {
            $fim = $inicio;
        }

        $data['inicio'] = $inicio;
        $data['fim'] = $fim;
        $data['configurado'] = $this->sicoob_api->configurado();
        $data['erro_config'] = $this->sicoob_api->erro_configuracao();
        $data['resumo'] = $this->conciliacao_model->resumo($inicio, $fim);
        $data['extrato'] = $this->conciliacao_model->extrato($inicio, $fim);
        $data['ultima_importacao'] = $this->conciliacao_model->ultima_importacao();
        $data['mensagem'] = $this->session->flashdata('conciliacao_msg');
        $data['erro'] = $this->session->flashdata('conciliacao_erro');
        $this->montaTela('conciliacao/index', $data);
    }

    public function importar()
    {
        $inicio = $this->data_valida($this->input->post('inicio'), date('Y-m-01'));
        $fim = $this->data_valida($this->input->post('fim'), date('Y-m-d'));
        if ($fim > date('Y-m-d')) {
            $fim = date('Y-m-d');
        }
        $volta = base_url(array('loja', 'conciliacao-bancaria')).'?inicio='.$inicio.'&fim='.$fim;

        if (!$this->sicoob_api->configurado()) {
            $this->session->set_flashdata('conciliacao_erro', $this->sicoob_api->erro_configuracao());
            redirect($volta);
        }
        if ($inicio < date('Y-m-01', strtotime('-3 months'))) {
            $this->session->set_flashdata('conciliacao_erro', 'O Sicoob so disponibiliza o extrato dos ultimos 3 meses.');
            redirect($volta);
        }

        try {
            $total = 0;
            $mes = new DateTime(substr($inicio, 0, 7).'-01');
            $ultimo = new DateTime(substr($fim, 0, 7).'-01');
            while ($mes <= $ultimo) {
                $transacoes = $this->sicoob_api->extrato_mes((int) $mes->format('m'), (int) $mes->format('Y'));
                $ini_mes = max($inicio, $mes->format('Y-m-01'));
                $fim_mes = min($fim, $mes->format('Y-m-t'));
                $sicoob = $this->sicoob_api;
                $total += $this->conciliacao_model->salvar_extrato($sicoob->conta(), $ini_mes, $fim_mes, $transacoes,
                    function ($descricao, $complemento) use ($sicoob) { return $sicoob->categoria($descricao, $complemento); });
                $mes->modify('+1 month');
            }
            $this->session->set_flashdata('conciliacao_msg', $total.' lancamento(s) importado(s) do Sicoob.');
        } catch (Exception $e) {
            $this->session->set_flashdata('conciliacao_erro', $e->getMessage());
        }
        redirect($volta);
    }

    private function data_valida($valor, $padrao)
    {
        return (is_string($valor) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor) && strtotime($valor)) ? $valor : $padrao;
    }
}
