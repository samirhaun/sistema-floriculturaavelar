<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_grupos extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/grupos_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'Loja',
                                'submenu' => 'Loja-grupos-categorias'
                                )
                            );
    }

    public function index()
    {
        $this->lista();
    }


    public function lista()
    {
        $data['grupos'] =  $this->grupos_model->get_grupos();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('loja/grupos/lista', $data);
    }

    function novo(){

        $this->montaTela('loja/grupos/formulario');
    }

    function salvar(){

        $this->load->helper(array('text','url'));
        
        if($this->input->post()){

/*

            if(!empty($_FILES['imagem']['name'])){
                $this->load->library('upload', array(
                        'upload_path' => FCPATH.'../assets/images/categorias',
                        'allowed_types' => 'jpg|png|gif',
                        'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                        'max_size' => 8 * 1024,
                        'remove_spaces' => TRUE
                    ));

                if ($this->upload->do_upload('imagem')){
                    $file_data = $this->upload->data();
                    $this->load->library('image_lib', array(
                            'image_library' => 'gd2',
                            'source_image' => $file_data['full_path'],
                            'create_thumb' => FALSE,
                            'maintain_ratio' => TRUE,
                            'width' => 320,
                            'height' => 320
                        )
                    );
                    $this->image_lib->resize();
                }

                if($this->input->post('imagem_categoria')){
                    if ($_FILES['imagem']['name'] != $this->input->post('imagem_categoria')) {
                        $apagar = FCPATH.'../assets/images/categorias/' . $this->input->post('imagem_categoria');
                        @unlink($apagar);
                    }
                }
            }else{
                if($this->input->post('imagem_categoria')){
                    $file_data['file_name'] = $this->input->post('imagem_categoria');
                }else{
                    $file_data['file_name'] = '';
                }
            }

           
*/ 

            $dados = array(
                'nome' => $this->input->post('nome'),
                'nome_tag' => url_title(convert_accented_characters($this->input->post('nome')), '-', true),
          
            );
            $id = NULL;

            //editar agenda
            if($this->input->post('id')){
                $id = $this->input->post('id');
            }

            if($this->grupos_model->salvar($dados, $id))
            {
                $_GET['type'] = 'success';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Grupo de Categoria atualizado com sucesso!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Grupo de Categoria cadastrado com sucesso!';
                }
            }
            else
            {
                $_GET['type'] = 'error';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Ocorreu um erro ao atualizar o grupo!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Ocorreu um erro ao cadastrar o grupo!';
                }
            }
            $this->lista();
            $url = base_url(array('loja'));
            $this->output->append_output('<script>window.history.replaceState("", "Acrilmoc", "'. $url .'")</script>');
        }
    }

    public function editar()
    {
        if($this->input->get('id')){
            $dados['grupo'] = $this->grupos_model->get_grupo($this->input->get('id'));


            $this->montaTela('loja/grupos/formulario', $dados);
        }
    }

    function excluir(){
        if ($this->input->post('id')) {
            // $categoria = $this->categorias_model->get_categoria($this->input->post('id'));
            if($this->grupos_model->excluir($this->input->post('id'))){
                // $apagar = FCPATH.'../assets/images/categorias/' . $categoria->imagem;
                // @unlink($apagar);
                $response['type'] = 'success';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Grupo de Categoria excluído com sucesso!';
                echo json_encode($response);
            }else{
                $response['type'] = 'error';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Ocorreu um erro ao excluír a categoria!';
                echo json_encode($response);
            }
        }
        return;
    }

}