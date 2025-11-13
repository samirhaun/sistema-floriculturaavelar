<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_anuncios extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('anuncios/anuncios_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'site',
                                'submenu' => 'anuncios'
                                )
                            );
    }

    public function index()
    {
        $this->lista();
    }


    public function lista()
    {

            $data['anuncio'] =  $this->anuncios_model->get_anuncio();
        
        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }
        $this->montaTela('site/anuncios/anuncios', $data);
    }



    function salvar_anuncios(){



        $this->load->helper('text');
        if($this->input->post()){
            if(!empty($_FILES['anuncio']['name'])){
                $this->load->library('upload', array(
                        'upload_path' => FCPATH.'../assets/images/anuncios/',
                        'allowed_types' => 'jpg|png|gif',
                        'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                        'max_size' => 8 * 1024,
                        'remove_spaces' => TRUE
                    ));

                if ($this->upload->do_upload('anuncio')){
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

                if($this->input->post('anuncio')){
                    if ($_FILES['anuncio']['name'] != $this->input->post('anuncio')) {
                        $apagar = FCPATH.'../assets/images/anuncios/' . $this->input->post('anuncio');
                        @unlink($apagar);
                    }
                }
            }else{
                if($this->input->post('anuncio')){
                    $file_data['file_name'] = $this->input->post('anuncio');
                }else{
                    $file_data['file_name'] = '';
                }
            }

            $dados = array(
                'url' => $this->input->post('url'),
                'imagem' => $file_data['file_name']
            );

            if($data = $this->anuncios_model->get_anuncio()){

                    if($this->anuncios_model->salvar_anuncio($dados, $data->id)){
                        $_GET['type'] = 'success';
                        $_GET['title'] = 'Anuncios';
                        $_GET['message'] = 'Anuncio Atualizado com Sucesso!';
                        $this->lista();
                        $url = base_url(array('site', 'anuncios'));
                        $this->output->append_output('<script>window.history.replaceState("", "Line Hair", "'. $url .'")</script>');

                        }

            }else{
                      if($this->anuncios_model->salvar_anuncio($dados)){

                        $_GET['type'] = 'success';
                        $_GET['title'] = 'Anuncios';
                        $_GET['message'] = 'Anuncio Atualizado com Sucesso!';
                        $this->lista();
                        $url = base_url(array('site', 'anuncios'));
                        $this->output->append_output('<script>window.history.replaceState("", "Line Hair", "'. $url .'")</script>');

                 }

            }

        



            

        }
    }














}