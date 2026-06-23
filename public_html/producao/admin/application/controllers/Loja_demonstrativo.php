<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_demonstrativo extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/pedidos_model');
        $this->load->model('loja/contas_pagar_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'loja',
                                'submenu' => 'loja-demonstrativo'
                                )
                            );
    }


       

    function demonstrativo(){
        // $data['convenios'] = $this->faturamento_model->get_convenios();
        // $data['procedimentos'] = $this->faturamento_model->get_procedimentos();
        $data['vendedores'] = $this->pedidos_model->get_vendedores();
        $data['entregadores'] = $this->pedidos_model->get_entregadores();
        $data['plano_contas'] = $this->contas_pagar_model->get_planos_conta();
        $this->montaTela('pedidos/relatorio',$data);
    }


    function demonstrativo_buscar(){

        if($_POST){
            $data['filtro_inicio'] = $this->input->post('dia_inicial');
            $data['filtro_fim'] =  $this->input->post('dia_final');
            $data['filtro_origem'] = $this->input->post('origem');
            $data['filtro_vendedor'] = $this->input->post('vendedor');
            $data['filtro_plano_conta'] = $this->input->post('plano_contas_id');
            $data['filtro_exibir_receitas'] = $this->input->post('exibir_receitas');
            $data['filtro_referencia'] = $this->input->post('referencia');
            $data['filtro_situacao_pgto'] = $this->input->post('situacao_pgto');
            $data['filtro_entregador'] = $this->input->post('entregador');
            $data['filtro_forma_pgto'] = $this->input->post('forma_pgto');
            $data['filtro_exibir_despesas'] = $this->input->post('exibir_despesas');

            //BUSCANDO OS PROCEDIMENTOS REALIZADOS
            if($data['filtro_exibir_receitas'] == 1):

                $data['pedidos'] = $this->pedidos_model
                                            ->get_demonstrativo(inverteData($data['filtro_inicio'],'-')
                                                                ,inverteData($data['filtro_fim'],'-')
                                                                ,$data['filtro_origem']
                                                                ,$data['filtro_vendedor']
                                                                ,$data['filtro_referencia']
                                                                ,$data['filtro_situacao_pgto']
                                                                ,$data['filtro_entregador']
                                                                ,$data['filtro_forma_pgto']);

                                                            //       echo '<pre>';
                                                            // print_r($data['pedidos']);
                                                            // exit;

            endif;

            
          if($data['filtro_exibir_despesas'] == 1):

           $data['contas_pagar'] = $this->pedidos_model
                                          ->get_demonstrativo_pagar(inverteData($data['filtro_inicio'],'-')
                                                            ,inverteData($data['filtro_fim'],'-')
                                                            ,$data['filtro_origem']
                                                            ,$data['filtro_vendedor']
                                                            ,$data['filtro_plano_conta']
                                                            ,$data['filtro_referencia']
                                                            ,$data['filtro_forma_pgto']);


          endif;                          
                                                            // echo '<pre>';
                                                            // print_r($data['contas_pagar']);
                                                            // exit;
        

            $data['vendedores'] = $this->pedidos_model->get_vendedores();

            $data['entregadores'] = $this->pedidos_model->get_entregadores();
            
            $data['plano_contas'] = $this->contas_pagar_model->get_planos_conta();

            $data['resultado'] = $this->load->view('pedidos/relatorio_resultado', $data, true);
            

          
            
            $this->montaTela('pedidos/relatorio',$data);


        }

    }


    /* novo demonstrativo*/

    function demonstrativo_novo(){
        $this->set_menu_active(
            array(
                'menu' => 'loja',
                'submenu' => 'loja-demonstrativo-novo'
                )
            );
        // $data['convenios'] = $this->faturamento_model->get_convenios();
        // $data['procedimentos'] = $this->faturamento_model->get_procedimentos();
        $data['vendedores'] = $this->pedidos_model->get_vendedores();
        $data['entregadores'] = $this->pedidos_model->get_entregadores();
        $data['plano_contas'] = $this->contas_pagar_model->get_planos_conta();
        $this->montaTela('pedidos/relatorio_novo',$data);
    }


    function demonstrativo_buscar_novo(){
        $this->set_menu_active(
            array(
                'menu' => 'loja',
                'submenu' => 'loja-demonstrativo-novo'
                )
            );
        if($_POST){
            $data['filtro_inicio'] = $this->input->post('dia_inicial');
            $data['filtro_fim'] =  $this->input->post('dia_final');
            $data['filtro_origem'] = $this->input->post('origem') ?: 'all';
            $data['filtro_vendedor'] = $this->input->post('vendedor') ?: 'all';
            $data['filtro_plano_conta'] = $this->input->post('plano_contas_id') ?: 'all';
            $data['filtro_exibir_receitas'] = $this->input->post('exibir_receitas');
            $data['filtro_referencia'] = $this->input->post('referencia');
            $data['filtro_situacao_pgto'] = $this->input->post('situacao_pgto') ?: array('all');
            $data['filtro_entregador'] = $this->input->post('entregador') ?: 'all';
            $data['filtro_forma_pgto'] = $this->input->post('forma_pgto') ?: array();
            $data['filtro_exibir_despesas'] = $this->input->post('exibir_despesas');

            $inicio = inverteData($data['filtro_inicio'],'-');
            $fim = inverteData($data['filtro_fim'],'-');
            $page = (int) $this->input->post('page') ?: 1;
            $page_rec = (int) $this->input->post('page_receitas') ?: 1;
            $page_desp = (int) $this->input->post('page_despesas') ?: 1;
            $per_page = 50;

            $data['page'] = $page;
            $data['page_receitas'] = $page_rec;
            $data['page_despesas'] = $page_desp;
            $data['per_page'] = $per_page;

            $pedidos_all = $this->pedidos_model->get_demonstrativo_novo(
                $inicio, $fim, $data['filtro_origem'], $data['filtro_vendedor'],
                $data['filtro_referencia'], $data['filtro_situacao_pgto'],
                $data['filtro_entregador'], $data['filtro_forma_pgto']
            );

            $contas_all = $this->pedidos_model->get_demonstrativo_pagar_novo(
                $inicio, $fim, $data['filtro_origem'], $data['filtro_vendedor'],
                $data['filtro_plano_conta'], $data['filtro_referencia'],
                $data['filtro_forma_pgto'], $data['filtro_situacao_pgto']
            );

            $data['total_receitas_count'] = count($pedidos_all);
            $data['total_despesas_count'] = count($contas_all);

            $merged = array();
            foreach($pedidos_all as $p){ $p->_tipo = 'receita'; $merged[] = $p; }
            foreach($contas_all as $c){ $c->_tipo = 'despesa'; $merged[] = $c; }
            $total_all = count($merged);
            $total_pages = ceil($total_all / $per_page);
            $data['registros'] = array_slice($merged, ($page - 1) * $per_page, $per_page);
            $data['total_all'] = $total_all;
            $data['total_pages'] = $total_pages;

            $total_pages_rec = ceil($data['total_receitas_count'] / $per_page);
            $total_pages_desp = ceil($data['total_despesas_count'] / $per_page);
            $data['pedidos_pag'] = array_slice($pedidos_all, ($page_rec - 1) * $per_page, $per_page);
            $data['contas_pag'] = array_slice($contas_all, ($page_desp - 1) * $per_page, $per_page);
            $data['total_pages_receitas'] = $total_pages_rec;
            $data['total_pages_despesas'] = $total_pages_desp;

            $data['totais_forma_pgto_receitas'] = $this->pedidos_model->get_demonstrativo_totais_por_forma_pgto(
                $inicio, $fim, $data['filtro_origem'], $data['filtro_vendedor'],
                $data['filtro_referencia'], $data['filtro_situacao_pgto'],
                $data['filtro_entregador'], $data['filtro_forma_pgto']
            );

            $data['totais_forma_pgto_despesas'] = $this->pedidos_model->get_demonstrativo_totais_por_forma_pgto_despesas(
                $inicio, $fim, $data['filtro_plano_conta'], $data['filtro_referencia'],
                $data['filtro_forma_pgto'], $data['filtro_situacao_pgto']
            );

            $data['totais_por_categoria'] = $this->pedidos_model->get_demonstrativo_por_categoria(
                $inicio, $fim, $data['filtro_referencia']
            );

            $data['detalhe_categoria'] = $this->pedidos_model->get_demonstrativo_detalhe_categoria(
                $inicio, $fim, $data['filtro_referencia']
            );

            $data['totais_por_plano_conta'] = $this->pedidos_model->get_demonstrativo_por_plano_conta(
                $inicio, $fim, $data['filtro_referencia']
            );

            $data['detalhe_plano_conta'] = $this->pedidos_model->get_demonstrativo_detalhe_plano_conta(
                $inicio, $fim, $data['filtro_referencia']
            );

            $todos_planos = $this->contas_pagar_model->get_todos_planos_conta();
            $data['planos_lookup'] = array();
            if(!empty($todos_planos)){
                foreach($todos_planos as $pl){
                    $data['planos_lookup'][$pl->cod] = $pl->descricao;
                }
            }

            $total_receitas_aberto = 0; $total_receitas_pago = 0;
            foreach($pedidos_all as $p){
                if($p->status_receita == 1) $total_receitas_pago += (float) $p->valor_receita;
                else $total_receitas_aberto += (float) $p->valor_receita;
            }
            $data['card_total_a_receber'] = $total_receitas_aberto;
            $data['card_total_recebido'] = $total_receitas_pago;

            $total_desp_aberto = 0; $total_desp_pago = 0;
            foreach($contas_all as $c){
                if($c->status == 1) $total_desp_pago += (float) $c->valor;
                else $total_desp_aberto += (float) $c->valor;
            }
            $data['card_total_a_pagar'] = $total_desp_aberto;
            $data['card_total_pago_desp'] = $total_desp_pago;
            $data['card_saldo'] = $total_receitas_pago - $total_desp_pago;
            $data['card_previsao'] = $total_receitas_aberto - $total_desp_aberto;

            $data['resultado'] = $this->load->view('pedidos/relatorio_resultado_novo', $data, true);

            if($this->input->is_ajax_request()){
                $this->output->set_output(json_encode(array(
                    'html' => $data['resultado'],
                    'page' => $page
                )));
                return;
            }

            $data['vendedores'] = $this->pedidos_model->get_vendedores();
            $data['entregadores'] = $this->pedidos_model->get_entregadores();
            $data['plano_contas'] = $this->contas_pagar_model->get_planos_conta();

            $this->montaTela('pedidos/relatorio_novo',$data);
        }
    }

}