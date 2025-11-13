<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_marcas extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('marcas/marcas_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'Loja',
                                'submenu' => 'Loja-marcas'
                                )
                            );
    }

    public function index()
    {
        $this->lista();
    }


    public function lista()
    {

      $data['marcas'] =  $this->marcas_model->get_marcas();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }
        $this->montaTela('site/marcas/lista', $data);
    }

    public function nova()
    {


        $this->montaTela('site/marcas/marcas');
    }



    function salvar_marcas(){




        $this->load->helper('text');
        if($this->input->post()){
            if(!empty($_FILES['marca']['name'])){
                $this->load->library('upload', array(
                        'upload_path' => FCPATH.'../assets/images/marcas/',
                        'allowed_types' => 'jpg|png|gif',
                        'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                        'max_size' => 8 * 1024,
                        'remove_spaces' => TRUE
                    ));

                if ($this->upload->do_upload('marca')){
                    $file_data = $this->upload->data();
                    $this->load->library('image_lib', array(
                            'image_library' => 'gd2',
                            'source_image' => $file_data['full_path'],
                            'create_thumb' => FALSE,
                            'maintain_ratio' => TRUE,
                            'width' => 1680,
                            'height' => 1100
                        )
                    );
                    $this->image_lib->resize();
                }

            }else{
                if($this->input->post('marca')){
                    $file_data['file_name'] = $this->input->post('marca');
                }else{
                    $file_data['file_name'] = '';
                }
            }

            $dados = array(
                'titulo' => $this->input->post('titulo'),
                'nome_tag' => url_title(convert_accented_characters($this->input->post('titulo')), '-', true),
                //'imagem' => $file_data['file_name']
            );


              if($this->marcas_model->salvar_marca($dados)){
                $_GET['type'] = 'success';
                $_GET['title'] = 'Marcas';
                $_GET['message'] = 'Marca inserida com Sucesso!';
                $this->lista();
                $url = base_url(array('loja', 'marcas'));
                $this->output->append_output('<script>window.history.replaceState("", "Lamitié", "'. $url .'")</script>');

            }

        }
    }


    public function editar()
    {
        if($this->input->get('id')){
            $dados['marca'] = $this->marcas_model->get_marca($this->input->get('id'));
            $this->montaTela('site/marcas/editar', $dados);
        }
    }


    public function update(){

        $this->load->helper('text');
        if($this->input->post()){

             $dados = array(
                'titulo' => $this->input->post('titulo'),
                'nome_tag' => url_title(convert_accented_characters($this->input->post('titulo')), '-', true),
                //'imagem' => $file_data['file_name']
            );

             if($this->marcas_model->salvar_marca($dados, $this->input->post('id'))){
                $_GET['type'] = 'success';
                $_GET['title'] = 'Marcas';
                $_GET['message'] = 'Marca atualizada com Sucesso!';
                $this->lista();
                $url = base_url(array('loja', 'marcas'));
                $this->output->append_output('<script>window.history.replaceState("", "Lamitié", "'. $url .'")</script>');

            }

        }

    }



public function excluir(){

  if ($id = $this->input->post('id')) {

      $marca = $this->marcas_model->get_marca($id);
      if($marca->imagem){
        if ($this->marcas_model->delete_marca($id)) {
          $apagar = FCPATH.'../assets/images/marcas/' . $marca->imagem;
          if (@unlink($apagar)) {
            $response['type'] = 'success';
            $response['title'] = 'Exclusão';
            $response['message'] = 'Marca excluída com sucesso!';
            echo json_encode($response);
          }else{
            $response['type'] = 'error';
            $response['title'] = 'Exclusão';
            $response['message'] = 'Ocorreu um erro ao excluír a Marca!';
            echo json_encode($response);
          }
        }
    }else{
      $this->marcas_model->delete_marca($id);
      $response['type'] = 'success';
      $response['title'] = 'Exclusão';
      $response['message'] = 'Marca excluída com sucesso!';
      echo json_encode($response);
    }
  }


}










}
