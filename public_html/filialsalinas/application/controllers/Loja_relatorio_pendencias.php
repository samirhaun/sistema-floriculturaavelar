<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_relatorio_pendencias extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/pedidos_model');
        $this->set_menu_active(array('menu' => 'loja', 'submenu' => 'loja-relatorio-pendencias'));
    }

    function index(){
        $this->montaTela('pendencias/relatorio');
    }

    function buscar(){
        if($_POST){
            $data['filtro_inicio'] = $this->input->post('dia_inicial') ?: '';
            $data['filtro_fim'] = $this->input->post('dia_final') ?: '';

            $inicio = $data['filtro_inicio'] ? inverteData($data['filtro_inicio'], '-') : null;
            $fim = $data['filtro_fim'] ? inverteData($data['filtro_fim'], '-') : null;

            $totais = $this->pedidos_model->get_pendencias_totais($inicio, $fim);
            $data['total_pendente'] = $totais->total;
            $data['qtd_parcelas'] = $totais->qtd;
            $data['ticket_medio'] = ($totais->qtd > 0) ? ($totais->total / $totais->qtd) : 0;

            $data['pendencias'] = $this->pedidos_model->get_pendencias($inicio, $fim);
            $data['hoje'] = date('Y-m-d');

            $data['resultado'] = $this->load->view('pendencias/relatorio_resultado', $data, true);
            $this->montaTela('pendencias/relatorio', $data);
        }
    }

}
