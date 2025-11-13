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
            endif;

           $data['contas_pagar'] = $this->pedidos_model
                                          ->get_demonstrativo_pagar(inverteData($data['filtro_inicio'],'-')
                                                            ,inverteData($data['filtro_fim'],'-')
                                                            ,$data['filtro_origem']
                                                            ,$data['filtro_vendedor']
                                                            ,$data['filtro_plano_conta']
                                                            ,$data['filtro_referencia']
                                                            ,$data['filtro_forma_pgto']);


                                                            
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

}