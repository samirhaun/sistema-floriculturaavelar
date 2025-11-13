<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_clientes extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/clientes_model');
        $this->load->model('loja/usuarios_model');
        $this->load->model('loja/pedidos_model');
        $this->load->helper('form');
        $this->load->helper('text');


        $this->set_menu_active(
            array(
                'menu' => 'Loja',
                'submenu' => 'Loja-clientes'
                )
            );
    }

    public function index()
    {
        $this->lista();
    }

    function verifica_permissoes(){

        $dados['permissoes'] = $this->usuarios_model->get_permissoes($this->auth->get_id_usuario());
        if ($dados['permissoes']->clientes != 1) {
           $this->acesso_negado();
        }else{
             return 2;
           }
        
    }

    public function lista()
    {
        $data['clientes'] =  $this->clientes_model->get_clientes();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('loja/clientes/lista', $data);
    }

    public function historico_cliente(){

        //     $dados['permissoes'] = $this->usuarios_model->get_permissoes($this->auth->get_id_usuario());
        //  if ($dados['permissoes']->ver_pedidos == 1) {
        //    $data['pedidos'] =  $this->pedidos_model->get_pedidos_cliente($this->input->get('id'));

        // }else{
        //    $data['pedidos'] =  $this->pedidos_model->get_pedidos_cliente($this->input->get('id'));

        // }
        $data['pedidos'] =  $this->pedidos_model->get_pedidos_cliente($this->input->get('id'));

       if($this->input->get('type')){
        $notification = new stdClass;
        $notification->type = $this->input->get('type');
        $notification->title = $this->input->get('title');
        $notification->message = $this->input->get('message');
        $data['notification'] = $notification;
    }

        $this->montaTela('loja/clientes/historico', $data);
    }

    public function lista_enderecos()
    {
        $data['enderecos'] =  $this->clientes_model->get_enderecos($this->input->get('id'));

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('loja/clientes/lista_enderecos', $data);
    }


    function novo_cliente(){
        // $this->verifica_permissoes();
       // $data['categorias'] = $this->clientes_model->get_categorias();
        $this->montaTela('loja/clientes/formulario');
    }

    function salvar_cliente(){
        // $this->verifica_permissoes();

        // echo '<pre>';
        // print_r($_POST);
        // exit;


        if($this->input->post()){
            $dados = array(
                'origem' => 2,
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'telefone' => $this->input->post('telefone'),
                'cpf' => $this->input->post('cpf'),
                // 'cep' => $this->input->post('cep'),
                // 'rua' => $this->input->post('rua'),
                // 'numero' => $this->input->post('numero'),
                // 'bairro' => $this->input->post('bairro'),
                // 'cidade' => $this->input->post('cidade'),
                // 'estado' => $this->input->post('estado'),
                // 'complemento' => $this->input->post('complemento'),

            );

            $id = NULL;

            //editar agenda
            if($this->input->post('id')){
                $id = $this->input->post('id');
            }

            if($id_cliente = $this->clientes_model->salvar_cliente($dados, $id))
            {
                $_GET['type'] = 'success';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'cliente atualizado com sucesso!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'cliente cadastrado com sucesso!';
                }

                /*ENDEREÇOS VINCULADOS*/

                $dados_count = $this->input->post('descricao');

                $dados_vinculo = array();

                //VERIICANDO SE É CADASTRO OU EDIÇÃO
                if(empty($id)):
                    $idcliente = $id_cliente;
                else:
                    $idcliente = $id;
                endif;
    
                if(count($dados_count) > 0):

                    $dados_produto = array();

                    foreach ($dados_count as $key => $value) {
                        if(!empty($this->input->post('descricao')[$key])):

                            $dados_produto['ideditando'] = $this->input->post('ideditando')[$key];
                            $dados_produto['descricao'] = $this->input->post('descricao')[$key];
                            $dados_produto['cep'] = $this->input->post('cep')[$key];
                            $dados_produto['rua'] = $this->input->post('rua')[$key];
                            $dados_produto['numero'] = $this->input->post('numero')[$key];
                            $dados_produto['bairro'] = $this->input->post('bairro')[$key];
                            $dados_produto['cidade'] = $this->input->post('cidade')[$key];
                            $dados_produto['estado'] = $this->input->post('estado')[$key];
                            $dados_produto['complemento'] = $this->input->post('complemento')[$key];
                            $dados_produto['clientes_id'] = $idcliente;
                            array_push($dados_vinculo, $dados_produto);

                        endif;
                    }

                $this->clientes_model->salvar_enderecos_vinculados($dados_vinculo);


                endif;



                /*FIM ENDEREÇOS VINCULADOS*/
            }
            else
            {
                $_GET['type'] = 'error';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Ocorreu um erro ao atualizar o cliente!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Ocorreu um erro ao cadastrar o cliente!';
                }
            }
            $this->lista();
            $url = base_url(array('loja',  'clientes'));
            $this->output->append_output('<script>window.history.replaceState("", "Albedo", "'. $url .'")</script>');
        }
    }

    public function editar_cliente()
    {
        // $this->verifica_permissoes();
        if($this->input->get('id')){
            // $dados['categorias'] = $this->clientes_model->get_categorias();
            $dados['cliente'] = $this->clientes_model->get_cliente($this->input->get('id'));

            $dados['enderecos_vinculados'] =  $this->clientes_model->get_clientes_vinculados($this->input->get('id'));

            $this->montaTela('loja/clientes/formulario', $dados);
        }
    }

    function excluir_cliente(){

        // if ($this->verifica_permissoes()) {
          

        if ($this->input->post('id')) {


            if($this->clientes_model->excluir_cliente($this->input->post('id'))){

                $response['type'] = 'success';
                $response['title'] = 'Exclusão';
                $response['message'] = 'cliente excluído com sucesso!';
                echo json_encode($response);
            }else{
                $response['type'] = 'error';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Ocorreu um erro ao excluír o cliente!';
                echo json_encode($response);
            }

            
        }
        // }else{
        //      $response['type'] = 'error';
        //     $response['title'] = 'Acesso Negado';
        //     $response['message'] = 'Você nao tem permissão para realizar esta ação!';
        //     $this->output->set_output(json_encode($response));
        // }
        return;
    }
    

}