<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_pedidos extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/pedidos_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'loja',
                                'submenu' => 'loja-pedidos'
                                )
                            );
    }

    public function index()
    {
        $this->lista();
    }


    public function listaAntiga()
    {
        
        //CHECK HABILIDADE
        if(!check_habilidade('visualizar_pedidos')): echo 'Acesso negado.'; exit; endif;

        $data['pedidos'] =  $this->pedidos_model->get_pedidos();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }
        //var_dump($data); exit;

        // echo '<pre>';
        // print_r($data);
        // exit;
    
        $this->montaTela('pedidos/lista', $data);
    }

    public function lista()
    {
        
        //CHECK HABILIDADE
        if(!check_habilidade('visualizar_pedidos')): echo 'Acesso negado.'; exit; endif;

        $data['pedidos'] =  $this->pedidos_model->get_pedidos();

         

        $rows = array();
            foreach ($data['pedidos'] as $row) {

                $pedido = array();

                //ORIGEM
                if($row->origem == 1): 
                    $pedido['origem'] = 'Balcão'; 
                else: 
                    $pedido['origem'] = ' Site'; 
                endif;

                //CODIGO
                $pedido['id'] = $row->id; 

                //DATA PEDIDO
                $pedido['data_pedido'] = date("d/m/Y H:i", strtotime($row->data_pedido));


                //DATA HORA ENTREGA
                $pedido['data_hora_entrega'] = date("d/m/Y", strtotime($row->data_entrega)).' '.$row->hora_entrega;

                //CLIENTE
                $pedido['cliente'] = $row->cliente_pedido.' / '.$row->pessoa_entrega;

                //CLIENTE
                $pedido['telefone'] = $row->cliente_telefone;

                //CLIENTE
                 $pedido['valor'] = number_format($row->valor_total, 2, ',', '.');

                  //TIPO
                 $pedido['tipo'] = 'Balcão';


                 //FORMA PGTO
                 if($row->forma_pagamento_balcao == 1): 
                    $pedido['forma_pgto'] = '<span class="wishlist-in-stock">Dinheiro</span>';
            elseif($row->forma_pagamento_balcao == 2): 
                        $pedido['forma_pgto'] = '<span class="wishlist-in-stock">Débito</span>';
                        elseif($row->forma_pagamento_balcao == 3): 
                            $pedido['forma_pgto'] = '<span class="wishlist-in-stock">Crédito 1x</span>';
                            elseif($row->forma_pagamento_balcao == 4): 
                                $pedido['forma_pgto'] = '<span class="wishlist-in-stock">Crédito 2x</span>';
                                elseif($row->forma_pagamento_balcao == 5): 
                                    $pedido['forma_pgto'] = '<span class="wishlist-in-stock">Crédito 3x</span>';
                                    elseif($row->forma_pagamento_balcao == 6): 
                                        $pedido['forma_pgto'] = '<span class="wishlist-in-stock">Crédito 4x</span>';
                                        elseif($row->forma_pagamento_balcao == 7): 
                                            $pedido['forma_pgto'] = '<span class="wishlist-in-stock">Duplicata</span>';
                                            elseif($row->forma_pagamento_balcao == 8): 
                                                $pedido['forma_pgto'] = '<span class="wishlist-in-stock">Cheque</span>';
                                                elseif($row->forma_pagamento_balcao == 9): 
                                                    $pedido['forma_pgto'] = '<span class="wishlist-in-stock">Pix</span>';

                                                else:
                                                    $pedido['forma_pgto'] = '';
          endif;

          //pedido pago
          if($row->pedido_pago == 0) $pedido['pago'] = 'Não pago'; else $pedido['pago'] = 'Pago';


          //entrega
          if($row->status_pedido == 0): $pedido['entrega'] = 'Aguardando confirmação da loja';
                                                        elseif($row->status_pedido == 1): $pedido['entrega'] = 'Confirmado pela loja';
                                                        elseif($row->status_pedido == 2): $pedido['entrega'] = 'Saiu para entrega';
                                                        elseif($row->status_pedido == 3): $pedido['entrega'] = 'Pedido concluído';
                                                        elseif($row->status_pedido == 4): $pedido['entrega'] = 'Pedido cancelado pela loja';
                                                        elseif($row->status_pedido == 5): $pedido['entrega'] = 'Pedido cancelado pelo cliente';
                                                        endif;

                                                        // if(!empty($row->entregador)):
                                                        //     $pedido['entrega'].= '<br>('.$row->entregador.')';
                                                        // endif;

                                                        if(!empty($row->entregador)):
                                                        $pedido['entregador'] = '('.$row->entregador.')';

                                                        else:
                                                            $pedido['entregador'] = '-';
                                                        endif;


                                                        $pedido['acoes'] = '
                                                        <a href="'.base_url(array('loja', 'detalhes-pedido')) .'?id='. $row->id.'" class="btn btn-default btn-icon-action" data-toggle="tooltip" title="Visualizar Pedido"><i class="fa fa-plus-square"></i></a>
                                                        ';

                                                        if ($row->status_pedido != '4' && $row->status_pedido != '5'):
                                                            $pedido['acoes'] .= '
                                                            <a href="javascript:void(0);" class="btn btn-default btn-icon-action editar-pedido" onclick="editando_pedido('. $row->id.')" data-idpedido="'.$row->id .'" data-toggle="tooltip" title="Alterar status pedido"><i class="fa fa-truck"></i></a>
                                                            ';
                                                        endif;

                                                        $pedido['acoes'] .= '
                                                            <a href="'.base_url(array('site', 'editar-pedido')).'?id='.$row->id.'" class="btn btn-default btn-icon-action" data-toggle="tooltip" data-placement="bottom" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                                            ';



                $rows[] = array_values((array)$pedido);

                // echo '<pre>';
                // print_r($rows);
                // exit;
            }
        $data['pedidos'] = json_encode($rows);

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }
        //var_dump($data); exit;

        // echo '<pre>';
        // print_r($data);
        // exit;
    
        $this->montaTela('pedidos/lista_nova', $data);
    }

    public function editar_pedido()
    {

        $data['pedido_id'] = $this->input->get('id');
        $data['pedido'] = $this->pedidos_model->busca_pedido($this->input->get('id'));

        $data['entregadores'] = $this->pedidos_model->get_entregadores();

        $this->load->view('pedidos/editar_pedido', $data);

    }



    public function salvar_pedido()
    {
        if($id = $this->input->post('hdn_id_pedido')){
            $db_pedido = array(
                                'codigo_rastreio' => $this->input->post('codigo_rastreio'),
                                'status_pedido' => $this->input->post('status_pedido'),
                                );

            if($this->pedidos_model->salvar_pedido($db_pedido, $id)){
                $_GET['type'] = 'success';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Pedido atualizado com sucesso!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Pedido cadastrado com sucesso!';
                }
            }else{
                $_GET['type'] = 'error';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Ocorreu um erro ao atualizar o pedido!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Ocorreu um erro ao cadastrar o pedido!';
                }
            }
            $this->lista();
            $url = base_url(array('loja', 'pedidos'));
            $this->output->append_output('<script>window.history.replaceState("", "Meritus", "'. $url .'")</script>');
        }
    }

    public function detalhes_pedido()
    {
        $data['pedido_id'] = $this->input->get('id');

        $data['pedido'] = $this->pedidos_model->get_pedido($this->input->get('id'));

        $data['produtos'] = $this->pedidos_model->get_pedido_produtos($this->input->get('id'));

        // echo '<pre>';
        // print_r($data);
        // exit;




        if($this->input->get('print')){
            $this->load->view('pedidos/impressao_pedido_mp4200', $data);
        }else if($this->input->get('print_cupom')){
            $this->load->view('pedidos/impressao_cupom_mp4200', $data);
        }else if($this->input->get('print_bilhete')){
            $this->load->view('pedidos/impressao_pedido_bilhete', $data);
        }else{
            $this->montaTela('pedidos/detalhes_pedido', $data);
        }
    }


        public function salvar_rastreio()
    {


        $id = $this->input->post('hdn_id_pedido');


            $db_pedido = array(
                                'codigo_rastreio' => $this->input->post('codigo_rastreio'),
                                'status_pedido' => $this->input->post('status_pedido'),
                                'entregadores_id' => ($this->input->post('entregadores_id')) ? $this->input->post('entregadores_id') : null,
                                'data_envio' => time()
                                );
            
/*         
           if($db_pedido['status_pedido'] == 4){

               $this->pedidos_model->alterar_qtd_produto($this->input->post('hdn_id_pedido'));


            }
 */
            if($this->pedidos_model->salvar_pedido($db_pedido, $id)){
                $_GET['type'] = 'success';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Pedido atualizado com sucesso!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Pedido cadastrado com sucesso!';
                }
            }else{
                $_GET['type'] = 'error';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Ocorreu um erro ao atualizar o pedido!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Ocorreu um erro ao cadastrar o pedido!';
                }
            }
            $this->lista();
            $url = base_url(array('loja', 'pedidos'));
            $this->output->append_output('<script>window.history.replaceState("", "Meritus", "'. $url .'")</script>');
        
    }

    public function finalizar_pedido(){
        if ($this->pedidos_model->finalizar_pedido($this->input->post('id'))) {
            echo json_encode(TRUE);
        }else{
            echo json_encode(FALSE);
        }
    }



    function demonstrativo(){
        // $data['convenios'] = $this->faturamento_model->get_convenios();
        // $data['procedimentos'] = $this->faturamento_model->get_procedimentos();
        $data['vendedores'] = $this->pedidos_model->get_vendedores();
        $this->montaTela('pedidos/relatorio',$data);
    }


    function demonstrativo_buscar(){

        if($_POST){
            $data['filtro_inicio'] = $this->input->post('dia_inicial');
            $data['filtro_fim'] =  $this->input->post('dia_final');
            $data['filtro_origem'] = $this->input->post('origem');
            $data['filtro_vendedor'] = $this->input->post('vendedor');

            //BUSCANDO OS PROCEDIMENTOS REALIZADOS
            $data['pedidos'] = $this->pedidos_model
                                          ->get_demonstrativo(inverteData($data['filtro_inicio'],'-')
                                                            ,inverteData($data['filtro_fim'],'-')
                                                            ,$data['filtro_origem']
                                                            ,$data['filtro_vendedor']);


                                                            

            // echo '<pre>';
            // print_r($data['dados']);
            // exit;

            $data['resultado'] = $this->load->view('pedidos/relatorio_resultado', $data, true);
            
            $this->montaTela('pedidos/relatorio',$data);


        }

    }

}