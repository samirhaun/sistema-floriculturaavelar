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


    public function lista()
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