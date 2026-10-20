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
            $data['total_duplicata'] = $totais->total_duplicata;
            $data['qtd_parcelas_duplicata'] = $totais->qtd_duplicata;
            $data['ticket_medio'] = ($totais->qtd > 0) ? ($totais->total / $totais->qtd) : 0;

            // Duplicata (forma_pgto = 7) e' venda a prazo: fica isolada da lista/total principal,
            // mas listada a parte para nao sumir do relatorio.
            $pendencias_todas = $this->pedidos_model->get_pendencias($inicio, $fim);
            $data['pendencias'] = array();
            $data['pendencias_duplicata'] = array();
            if(!empty($pendencias_todas)){
                foreach($pendencias_todas as $p){
                    if((int) $p->forma_pgto === 7) $data['pendencias_duplicata'][] = $p;
                    else $data['pendencias'][] = $p;
                }
            }
            $data['pendencias_vendedores'] = $this->pedidos_model->get_pendencias_por_vendedor($inicio, $fim);
            $data['hoje'] = date('Y-m-d');

            $pedidos_ids = array();
            if(!empty($pendencias_todas)){
                foreach($pendencias_todas as $p){
                    $pedidos_ids[] = $p->pedido_cod;
                }
                $pedidos_ids = array_unique($pedidos_ids);
            }
            $data['itens_pedidos'] = $this->pedidos_model->get_itens_pedidos_lote($pedidos_ids);

            $data['resultado'] = $this->load->view('pendencias/relatorio_resultado', $data, true);
            $this->montaTela('pendencias/relatorio', $data);
        }
    }

}
