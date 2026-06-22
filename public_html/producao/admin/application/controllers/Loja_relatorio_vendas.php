<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_relatorio_vendas extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/pedidos_model');
        $this->set_menu_active(array('menu' => 'loja', 'submenu' => 'loja-relatorio-vendas'));
    }

    function index(){
        $this->montaTela('vendas/relatorio');
    }

    function buscar(){
        if($_POST){
            $data['filtro_inicio'] = $this->input->post('dia_inicial');
            $data['filtro_fim'] = $this->input->post('dia_final');

            $inicio = inverteData($data['filtro_inicio'], '-');
            $fim = inverteData($data['filtro_fim'], '-');

            $totais = $this->pedidos_model->get_vendas_totais($inicio, $fim);
            $data['total_pedidos'] = $totais ? $totais->total_pedidos : 0;
            $data['total_itens'] = $totais ? $totais->total_itens : 0;
            $data['total_vendido'] = $totais ? $totais->total_vendido : 0;
            $data['ticket_medio'] = ($totais && $totais->total_pedidos > 0) ? ($totais->total_vendido / $totais->total_pedidos) : 0;

            $data['produtos'] = $this->pedidos_model->get_vendas_por_produto($inicio, $fim);
            $data['detalhe_produtos'] = $this->pedidos_model->get_vendas_detalhe_produto($inicio, $fim);

            $total_catalogo = 0;
            if(!empty($data['produtos'])){
                foreach($data['produtos'] as $p) $total_catalogo += $p->total;
            }
            $data['total_catalogo'] = $total_catalogo;
            $data['total_descontos'] = $total_catalogo - $data['total_vendido'];

            $data['resultado'] = $this->load->view('vendas/relatorio_resultado', $data, true);
            $this->montaTela('vendas/relatorio', $data);
        }
    }

}
