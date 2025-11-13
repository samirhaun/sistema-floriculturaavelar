<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_contas_pagar extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/contas_pagar_model');
        $this->load->helper('form');
        $this->load->helper('text');

        $this->set_menu_active(
            array(
                'menu' => 'Loja',
                'submenu' => 'Loja-contas_pagar'
                )
            );
    }


    function verifica_permissoes(){

        $dados['permissoes'] = $this->contas_pagar_model->get_permissoes($this->auth->get_id_usuario());
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
    $data['dados'] =  $this->contas_pagar_model->get_lista();

    if($this->input->get('type')){
        $notification = new stdClass;
        $notification->type = $this->input->get('type');
        $notification->title = $this->input->get('title');
        $notification->message = $this->input->get('message');
        $data['notification'] = $notification;
    }

    $this->montaTela('loja/contas_pagar/lista', $data);
}




function novo(){
    // $this->verifica_permissoes();
       $data['fornecedores'] = $this->contas_pagar_model->get_fornecedores();
       $data['plano_contas'] = $this->contas_pagar_model->get_planos_conta();
    $this->montaTela('loja/contas_pagar/formulario',$data);
}

function salvar(){

    // $this->verifica_permissoes();

    if($this->input->post()){
        $dados = array(
            'descricao' => $this->input->post('descricao'),
            'data_vencimento' => inverteData($this->input->post('data_vencimento'),'/'),
            'valor' => str_replace(',', '.', $this->input->post('valor')),
            'status' => $this->input->post('status'),
            'data_pago' => ($this->input->post('data_pago')) ? inverteData($this->input->post('data_pago'),'/') : null,
            'plano_contas_id' => $this->input->post('plano_contas_id'),
            'fornecedores_id' => $this->input->post('fornecedores_id'),
            'forma_pgto' => $this->input->post('forma_pgto'),
        );

        $id = NULL;

            //editar agenda
        if($this->input->post('id')){
            $id = $this->input->post('id');
        }

        if($this->contas_pagar_model->salvar($dados, $id))
        {


            $_GET['type'] = 'success';
            if($id){
                $_GET['title'] = 'Atualização';
                $_GET['message'] = 'conta a pagar atualizada com sucesso!';
            }else{
                $_GET['title'] = 'Cadastro';
                $_GET['message'] = 'conta a pagar cadastrada com sucesso!';
            }
        }
        else
        {
            $_GET['type'] = 'error';
            if($id){
                $_GET['title'] = 'Atualização';
                $_GET['message'] = 'Ocorreu um erro ao atualizar o conta a pagar!';
            }else{
                $_GET['title'] = 'Cadastro';
                $_GET['message'] = 'Ocorreu um erro ao cadastrar o conta a pagar!';
            }
        }
        $this->lista();
        $url = base_url(array('loja',  'contas_pagar'));
        $this->output->append_output('<script>window.history.replaceState("", "Albedo", "'. $url .'")</script>');
    }
}

public function editar()
{
    // $this->verifica_permissoes();
    if($this->input->get('id')){
            // $dados['categorias'] = $this->contas_pagar_model->get_categorias();
        $dados['dados'] = $this->contas_pagar_model->get_registro($this->input->get('id'));
        $dados['fornecedores'] = $this->contas_pagar_model->get_fornecedores();
       $dados['plano_contas'] = $this->contas_pagar_model->get_planos_conta();

        $this->montaTela('loja/contas_pagar/formulario', $dados);
    }
}

function excluir(){
    // if ($this->verifica_permissoes()) {

        // $this->verifica_permissoes();
        if ($this->input->post('id')) {

            if($this->contas_pagar_model->excluir($this->input->post('id'))){

                $response['type'] = 'success';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Conta a pagar excluída com sucesso!';
                echo json_encode($response);
            }else{
                $response['type'] = 'error';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Ocorreu um erro ao excluír a conta a pagar!';
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
