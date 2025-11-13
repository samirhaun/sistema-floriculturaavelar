<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('loja/pedidos_model');
        $this->set_menu_active(
                            array(
                                'menu' => 'loja',
                                'submenu' => 'loja-pedidos'
                                )
                            );
    }

    public function index(){
        $this->login();
    }

    public function login(){
        $data = array();
        if($this->input->get('error'))
            $data['error'] = TRUE;
        $this->load->view('auth/formulario_login', $data);
    }

    public function validar_login()
    {
        if($this->input->post()){
            if($user = $this->admin_model->validar_login($this->input->post('cpf'), md5($this->input->post('senha')))){
                $this->session->set_userdata('usuario', $user);
                redirect(base_url('home'),'refresh');
            }
        }
        $_GET['error'] = 'true';
        $this->login();
        $url = base_url(array('login'));
        $this->output->append_output('<script>window.history.replaceState("", "Sistema Agenda", "'. $url .'")</script>');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->login();
        $url = base_url(array('login'));
        $this->output->append_output('<script>window.history.replaceState("", "Sistema Agenda", "'. $url .'")</script>');
    }

    public function home2(){

      $data['pedidos'] =  $this->pedidos_model->get_pedidos();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }
        //var_dump($data); exit;
        $this->montaTela('pedidos/lista', $data);

    }

    public function home(){

        //CHECK HABILIDADE
        if(!check_habilidade('visualizar_pedidos')): echo 'Acesso negado.'; exit; endif;


        redirect(base_url('loja/pedidos'),'refresh');

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

                                                        // if(!empty($row->entregador)):
                                                        // $pedido['entregador'] = '('.$row->entregador.')';

                                                        // else:
                                                        //     $pedido['entregador'] = '-';
                                                        // endif;


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
}
