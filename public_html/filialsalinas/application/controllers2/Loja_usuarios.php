<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_usuarios extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/usuarios_model');
        $this->load->helper('form');
        $this->load->helper('text');

        $this->set_menu_active(
            array(
                'menu' => 'Loja',
                'submenu' => 'Loja-usuarios'
                )
            );
    }


    function verifica_permissoes(){

        $dados['permissoes'] = $this->usuarios_model->get_permissoes($this->auth->get_id_usuario());
        if ($dados['permissoes']->usuarios != 1) {
         $this->acesso_negado();
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
    $data['usuarios'] =  $this->usuarios_model->get_usuarios();

    if($this->input->get('type')){
        $notification = new stdClass;
        $notification->type = $this->input->get('type');
        $notification->title = $this->input->get('title');
        $notification->message = $this->input->get('message');
        $data['notification'] = $notification;
    }

    $this->montaTela('loja/usuarios/lista', $data);
}

public function lista_enderecos()
{
    $data['enderecos'] =  $this->usuarios_model->get_enderecos($this->input->get('id'));

    if($this->input->get('type')){
        $notification = new stdClass;
        $notification->type = $this->input->get('type');
        $notification->title = $this->input->get('title');
        $notification->message = $this->input->get('message');
        $data['notification'] = $notification;
    }

    $this->montaTela('loja/usuarios/lista_enderecos', $data);
}


function novo_usuario(){
    // $this->verifica_permissoes();
       // $data['categorias'] = $this->usuarios_model->get_categorias();
    $this->montaTela('loja/usuarios/formulario');
}

function salvar_usuario(){

    // $this->verifica_permissoes();

    if($this->input->post()){
        $dados = array(
            'nome' => $this->input->post('nome'),
            'email' => $this->input->post('email'),
            'cpf' => $this->input->post('cpf'),
            'desconto_maximo' => $this->input->post('desconto_maximo'),

        );

        $id = NULL;

            //editar agenda
        if($this->input->post('id')){
            $id = $this->input->post('id');
        }else{
            $dados['senha'] = md5($this->input->post('senha'));
        }

        if($id_usuario = $this->usuarios_model->salvar_usuario($dados, $id))
        {
            $this->usuarios_model->salvar_permissoes($id_usuario);

            $_GET['type'] = 'success';
            if($id){
                $_GET['title'] = 'Atualização';
                $_GET['message'] = 'usuario atualizado com sucesso!';
            }else{
                $_GET['title'] = 'Cadastro';
                $_GET['message'] = 'usuario cadastrado com sucesso!';
            }
        }
        else
        {
            $_GET['type'] = 'error';
            if($id){
                $_GET['title'] = 'Atualização';
                $_GET['message'] = 'Ocorreu um erro ao atualizar o usuario!';
            }else{
                $_GET['title'] = 'Cadastro';
                $_GET['message'] = 'Ocorreu um erro ao cadastrar o usuario!';
            }
        }
        $this->lista();
        $url = base_url(array('loja',  'usuarios'));
        $this->output->append_output('<script>window.history.replaceState("", "Albedo", "'. $url .'")</script>');
    }
}

public function editar_usuario()
{
    // $this->verifica_permissoes();
    if($this->input->get('id')){
            // $dados['categorias'] = $this->usuarios_model->get_categorias();
        $dados['usuario'] = $this->usuarios_model->get_usuario($this->input->get('id'));

        $this->montaTela('loja/usuarios/formulario', $dados);
    }
}

function excluir_usuario(){
    // if ($this->verifica_permissoes()) {

        // $this->verifica_permissoes();
        if ($this->input->post('id')) {
           
           $this->usuarios_model->excluir_permissoes($this->input->post('id'));

            if($this->usuarios_model->excluir_usuario($this->input->post('id'))){

                $response['type'] = 'success';
                $response['title'] = 'Exclusão';
                $response['message'] = 'usuario excluído com sucesso!';
                echo json_encode($response);
            }else{
                $response['type'] = 'error';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Ocorreu um erro ao excluír o usuario!';
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



function permissoes_usuario(){


    // $this->verifica_permissoes();

    // $dados['permissoes'] = $this->usuarios_model->get_permissoes($this->input->get('id'));
    $dados['habilidades'] = $this->usuarios_model->get_habilidades($this->input->get('id'));

    $dados['usuario'] = $this->usuarios_model->get_usuario($this->input->get('id'));

    // echo '<pre>';
    // print_r($dados);
    // exit;


    $this->montaTela('loja/usuarios/permissoes', $dados);

}

public function permissoes_muda(){

    // $this->verifica_permissoes();

    // var_dump($_POST);
    // exit;

    if ($this->usuarios_model->muda_permissao($this->input->post('usuario'), $this->input->post('habilidade'), $this->input->post('status'))) {
        $response['type'] = 'success';
        $response['title'] = 'Exclusão';
        $response['message'] = 'Habilidade Atualizada com sucesso!';
        echo json_encode($response);
    }else{
     $response['type'] = 'error';
     $response['title'] = 'Exclusão';
     $response['message'] = 'Ocorreu um erro ao Atualizar o item!';
     echo json_encode($response);
 }

}

public function permissoes_muda_all(){

    // $this->verifica_permissoes();

    // var_dump($_POST);
    // exit;

    if ($this->usuarios_model->muda_permissao_all($this->input->post('status'), $this->input->post('usuario'))) {
        $response['type'] = 'success';
        $response['title'] = 'Exclusão';
        $response['message'] = 'Habilidades Atualizada com sucesso!';
        echo json_encode($response);
    }else{
     $response['type'] = 'error';
     $response['title'] = 'Exclusão';
     $response['message'] = 'Ocorreu um erro ao Atualizar o item!';
     echo json_encode($response);
 }

}

}
