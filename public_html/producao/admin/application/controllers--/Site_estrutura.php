<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_estrutura extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('site/estrutura_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'conteudo',
                                'submenu' => 'site-estrutura'
                                )
                            );
    }

    public function index()
    {
        $this->lista();
    }


    public function lista()
    {
        $data['estrutura'] =  $this->estrutura_model->get_estruturas();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('site/estrutura/lista', $data);
    }

    function novo_estrutura(){
        $this->montaTela('site/estrutura/formulario');
    }

    function salvar_estrutura(){
        if($this->input->post()){
            if(!empty($_FILES['estrutura']['name'])){
                $this->load->library('upload', array(
                        'upload_path' => FCPATH.'../public/imagens/estrutura',
                        'allowed_types' => 'jpg|png|gif',
                        'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                        'max_size' => 8 * 1024,
                        'remove_spaces' => TRUE
                    ));

                if ($this->upload->do_upload('estrutura')){
                    $file_data = $this->upload->data();
                    $this->load->library('image_lib', array(
                            'image_library' => 'gd2',
                            'source_image' => $file_data['full_path'],
                            'create_thumb' => FALSE,
                            'maintain_ratio' => TRUE,
                            'width' => 1920,
                            'height' => 650,
                            'quality' => '100%'
                        )
                    );
                    $this->image_lib->resize();
                }

                if($this->input->post('imagem_estrutura')){
                    if ($_FILES['estrutura']['name'] != $this->input->post('imagem_estrutura')) {
                        $apagar = FCPATH.'../public/imagens/estrutura/' . $this->input->post('imagem_estrutura');
                        @unlink($apagar);
                    }
                }
            }else{
                if($this->input->post('imagem_estrutura')){
                    $file_data['file_name'] = $this->input->post('imagem_estrutura');
                }else{
                    $file_data['file_name'] = '';
                }
            }

            $dados = array(
                'titulo' => $this->input->post('titulo'),
                'texto' => $this->input->post('texto'),
                
                'imagem' => $file_data['file_name']
            );

            $id = NULL;

            //editar agenda
            if($this->input->post('id')){
                $id = $this->input->post('id');
            }

            if($this->estrutura_model->salvar_estrutura($dados, $id))
            {
                $_GET['type'] = 'success';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'estrutura atualizado com sucesso!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'estrutura cadastrado com sucesso!';
                }
            }
            else
            {
                $_GET['type'] = 'error';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Ocorreu um erro ao atualizar o estrutura!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Ocorreu um erro ao cadastrar o estrutura!';
                }
            }
            $this->lista();
            $url = base_url(array('site'));
            $this->output->append_output('<script>window.history.replaceState("", "SWE", "'. $url .'")</script>');
        }
    }

    public function editar_estrutura()
    {
        if($this->input->get('id')){
            $dados['estrutura'] = $this->estrutura_model->get_estrutura($this->input->get('id'));
            $this->montaTela('site/estrutura/formulario', $dados);
        }
    }

    function excluir_estrutura(){
        if ($this->input->post('id')) {
            $estrutura = $this->estrutura_model->get_estrutura($this->input->post('id'));
            if($this->estrutura_model->excluir_estrutura($this->input->post('id'))){
                $apagar = FCPATH.'../public/imagens/estrutura/' . $estrutura->imagem;
                @unlink($apagar);
                $response['type'] = 'success';
                $response['title'] = 'Exclusão';
                $response['message'] = 'estrutura excluído com sucesso!';
                echo json_encode($response);
            }else{
                $response['type'] = 'error';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Ocorreu um erro ao excluír o estrutura!';
                echo json_encode($response);
            }
        }
        return;
    }



  

  public function upload_imagem($value='')
    {
      if(!empty($_FILES['imagem']['name'])){
        $this->load->library('upload', [
            'upload_path' => FCPATH.'../public/imagens/estrutura',
            'allowed_types' => 'jpg|png|gif',
            'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
            'max_size' => 8 * 1024,
            'remove_spaces' => TRUE
          ]
        );
        if ($this->upload->do_upload('imagem')){
          $file_data = $this->upload->data();
          $this->load->library('image_lib', [
              'image_library' => 'gd2',
              'source_image' => $file_data['full_path'],
              'create_thumb' => FALSE,
              'maintain_ratio' => TRUE,
              'quality' => '100%'
            ]
          );
          //$this->image_lib->resize();
        }

        if($this->input->post('imagem_horario')){
          if ($_FILES['imagem']['name'] != $this->input->post('imagem_horario')) {
            $apagar = FCPATH.'../public/imagens/estrutura/' . $this->input->post('imagem_horario');
            @unlink($apagar);
          }
        }
      }else{
        if($this->input->post('imagem_horario')){
          $file_data['file_name'] = $this->input->post('imagem_horario');
        }else{
          $file_data['file_name'] = '';
        }
      }
      return $file_data;
    }


}
