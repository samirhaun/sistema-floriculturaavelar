<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_eventos extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/eventos_model');
        $this->load->helper('form');
        $this->load->helper('text');

        $this->set_menu_active(
            array(
                'menu' => 'Loja',
                'submenu' => 'Loja-eventos'
                )
            );
    }


    function verifica_permissoes(){

        $dados['permissoes'] = $this->eventos_model->get_permissoes($this->auth->get_id_usuario());
        if ($dados['permissoes']->usuarios != 1) {

     }else{
       return 2;
   }

}


public function index()
{
    $this->lista();
}


public function lista()
{
    $data['dados'] =  $this->eventos_model->get_lista();

    if($this->input->get('type')){
        $notification = new stdClass;
        $notification->type = $this->input->get('type');
        $notification->title = $this->input->get('title');
        $notification->message = $this->input->get('message');
        $data['notification'] = $notification;
    }

    $this->montaTela('loja/eventos/lista', $data);
}




function novo(){
    // $this->verifica_permissoes();
       // $data['categorias'] = $this->eventos_model->get_categorias();
    $this->montaTela('loja/eventos/formulario');
}

function salvar(){

    // $this->verifica_permissoes();

    if($this->input->post()){
        $dados = array(
            'descricao' => $this->input->post('descricao'),
        );

        $id = NULL;

            //editar agenda
        if($this->input->post('id')){
            $id = $this->input->post('id');
        }

        if($this->eventos_model->salvar($dados, $id))
        {


            $_GET['type'] = 'success';
            if($id){
                $_GET['title'] = 'Atualização';
                $_GET['message'] = 'evento atualizado com sucesso!';
            }else{
                $_GET['title'] = 'Cadastro';
                $_GET['message'] = 'evento cadastrado com sucesso!';
            }
        }
        else
        {
            $_GET['type'] = 'error';
            if($id){
                $_GET['title'] = 'Atualização';
                $_GET['message'] = 'Ocorreu um erro ao atualizar o evento!';
            }else{
                $_GET['title'] = 'Cadastro';
                $_GET['message'] = 'Ocorreu um erro ao cadastrar o evento!';
            }
        }
        $this->lista();
        $url = base_url(array('loja',  'eventos'));
        $this->output->append_output('<script>window.history.replaceState("", "Albedo", "'. $url .'")</script>');
    }
}

public function editar()
{
    // $this->verifica_permissoes();
    if($this->input->get('id')){
            // $dados['categorias'] = $this->eventos_model->get_categorias();
        $dados['dados'] = $this->eventos_model->get_registro($this->input->get('id'));

        $this->montaTela('loja/eventos/formulario', $dados);
    }
}

function excluir(){
    // if ($this->verifica_permissoes()) {

        // $this->verifica_permissoes();
        if ($this->input->post('id')) {

            if($this->eventos_model->excluir($this->input->post('id'))){

                $response['type'] = 'success';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Evento excluído com sucesso!';
                echo json_encode($response);
            }else{
                $response['type'] = 'error';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Ocorreu um erro ao excluír o evento!';
                echo json_encode($response);
            }


        }

//     }else{
//      $response['type'] = 'error';
//      $response['title'] = 'Acesso Negado';
//      $response['message'] = 'Você nao tem permissão para realizar esta ação!';
//      $this->output->set_output(json_encode($response));
//  }
 return;
}




}
