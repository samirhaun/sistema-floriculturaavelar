<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_blocos_estaticos extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('site/blocos_estaticos_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'conteudo',
                                'submenu' => 'site-banners'
                                )
                            );
    }

    public function mostrar($nome_unico = null)
    {
        if($nome_unico){

            $nome_unico = $nome_unico;

        }else{

            $nome_unico = $this->input->get('nome_unico');

        }


        $data['bloco'] = $this->blocos_estaticos_model->get_bloco_estatico($nome_unico);


        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }


        $this->montaTela('site/blocos_estaticos/formulario', $data);

        
    }


    public function atualizar()
    {

        // echo '<pre>';
        // print_r($_FILES);
        // exit;
        
        if($this->input->post()){
            if(!empty($_FILES['banner']['name'])){

                $width_img1 = $this->input->post('width_img1');
                $height_img1 = $this->input->post('height_img1');

                $this->load->library('upload', array(
                        'upload_path' => FCPATH.'../assets/images/blocos',
                        'allowed_types' => 'jpg|png|gif',
                        'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                        'max_size' => 8 * 1024,
                        'remove_spaces' => TRUE
                    ));

                if ($this->upload->do_upload('banner')){
                    $file_data = $this->upload->data();
                    $this->load->library('image_lib', array(
                            'image_library' => 'gd2',
                            'source_image' => $file_data['full_path'],
                            'create_thumb' => FALSE,
                            'maintain_ratio' => TRUE,
                            'width' => $width_img1,
                            'height' => $height_img1,
                            'quality' => '100%'
                        )
                    );
                    $this->image_lib->resize();
                }

                if($this->input->post('imagem_banner')){
                    if ($_FILES['banner']['name'] != $this->input->post('imagem_banner')) {
                        // $apagar = FCPATH.'../assets/images/blocos/' . $this->input->post('imagem_banner');
                        // @unlink($apagar);
                    }
                }
            }else{
                if($this->input->post('imagem_banner')){
                    $file_data['file_name'] = $this->input->post('imagem_banner');
                }else{
                    $file_data['file_name'] = '';
                }
            }



            /*IMAGEM 2*/

            if(!empty($_FILES['banner2']['name'])){

                $width_img2 = $this->input->post('width_img2');
                $height_img2 = $this->input->post('height_img2');

                $this->load->library('upload', array(
                        'upload_path' => FCPATH.'../assets/images/blocos',
                        'allowed_types' => 'jpg|png|gif',
                        'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                        'max_size' => 8 * 1024,
                        'remove_spaces' => TRUE
                    ));

                if ($this->upload->do_upload('banner2')){
                    $file_data2 = $this->upload->data();
                    $this->load->library('image_lib', array(
                            'image_library' => 'gd2',
                            'source_image' => $file_data2['full_path'],
                            'create_thumb' => FALSE,
                            'maintain_ratio' => TRUE,
                            'width' => $width_img2,
                            'height' => $height_img2,
                            'quality' => '100%'
                        )
                    );
                    $this->image_lib->resize();
                }

                if($this->input->post('imagem_banner2')){
                    if ($_FILES['banner2']['name'] != $this->input->post('imagem_banner2')) {
                        // $apagar = FCPATH.'../assets/images/blocos/' . $this->input->post('imagem_banner');
                        // @unlink($apagar);
                    }
                }
            }else{
                if($this->input->post('imagem_banner2')){
                    $file_data2['file_name'] = $this->input->post('imagem_banner2');
                }else{
                    $file_data2['file_name'] = '';
                }
            }


            /*FIM IMAGEM 2*/


            /*IMAGEM 3*/

            if(!empty($_FILES['banner3']['name'])){

                $width_img3 = $this->input->post('width_img3');
                $height_img3 = $this->input->post('height_img3');

                $this->load->library('upload', array(
                        'upload_path' => FCPATH.'../assets/images/blocos',
                        'allowed_types' => 'jpg|png|gif',
                        'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                        'max_size' => 8 * 1024,
                        'remove_spaces' => TRUE
                    ));

                if ($this->upload->do_upload('banner3')){
                    $file_data3 = $this->upload->data();
                    $this->load->library('image_lib', array(
                            'image_library' => 'gd2',
                            'source_image' => $file_data3['full_path'],
                            'create_thumb' => FALSE,
                            'maintain_ratio' => TRUE,
                            'width' => $width_img3,
                            'height' => $height_img3,
                            'quality' => '100%'
                        )
                    );
                    $this->image_lib->resize();
                }

                if($this->input->post('imagem_banner3')){
                    if ($_FILES['banner3']['name'] != $this->input->post('imagem_banner3')) {
                        // $apagar = FCPATH.'../assets/images/blocos/' . $this->input->post('imagem_banner');
                        // @unlink($apagar);
                    }
                }
            }else{
                if($this->input->post('imagem_banner3')){
                    $file_data3['file_name'] = $this->input->post('imagem_banner3');
                }else{
                    $file_data3['file_name'] = '';
                }
            }


            /*FIM IMAGEM 3*/


            /*IMAGEM 4*/

            if(!empty($_FILES['banner4']['name'])){

                $width_img4 = $this->input->post('width_img4');
                $height_img4 = $this->input->post('height_img4');

                $this->load->library('upload', array(
                        'upload_path' => FCPATH.'../assets/images/blocos',
                        'allowed_types' => 'jpg|png|gif',
                        'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                        'max_size' => 8 * 1024,
                        'remove_spaces' => TRUE
                    ));

                if ($this->upload->do_upload('banner4')){
                    $file_data4 = $this->upload->data();
                    $this->load->library('image_lib', array(
                            'image_library' => 'gd2',
                            'source_image' => $file_data3['full_path'],
                            'create_thumb' => FALSE,
                            'maintain_ratio' => TRUE,
                            'width' => $width_img3,
                            'height' => $height_img3,
                            'quality' => '100%'
                        )
                    );
                    $this->image_lib->resize();
                }

                if($this->input->post('imagem_banner4')){
                    if ($_FILES['banner4']['name'] != $this->input->post('imagem_banner4')) {
                        // $apagar = FCPATH.'../assets/images/blocos/' . $this->input->post('imagem_banner');
                        // @unlink($apagar);
                    }
                }
            }else{
                if($this->input->post('imagem_banner4')){
                    $file_data4['file_name'] = $this->input->post('imagem_banner4');
                }else{
                    $file_data4['file_name'] = '';
                }
            }


            /*FIM IMAGEM 4*/

            $dados = array(
                'nome_unico' => $this->input->post('nome_unico'),
                'titulo_1' => $this->input->post('titulo_1'),
                'texto_1' => $this->input->post('texto_1'),
                'titulo_2' => $this->input->post('titulo_2'),
                'texto_2' => $this->input->post('texto_2'),
                'texto_3' => $this->input->post('texto_3'),
                'texto_4' => $this->input->post('texto_4'),
                'titulo_3' => $this->input->post('titulo_3'),
                'texto_longo_1' => $this->input->post('texto_longo_1'),
                'imagem_1' => $file_data['file_name'],
                'imagem_2' => $file_data2['file_name'],
                'imagem_3' => $file_data3['file_name'],
                'imagem_4' => $file_data4['file_name'],
            );


            if($this->blocos_estaticos_model->atualizar_bloco_estatico($dados))
            {
                $_GET['type'] = 'success';
                $_GET['title'] = 'Atualização';
                $_GET['message'] = 'Bloco atualizado com sucesso!';
            }
            else
            {
                $_GET['type'] = 'error';
                $_GET['title'] = 'Atualização';
                $_GET['message'] = 'Ocorreu um erro ao atualizar o bloco!';
            }

            $this->mostrar($dados['nome_unico']);

            $url = base_url(array('site'));

            $this->output->append_output('<script>window.history.replaceState("", "SWE", "'. $url .'")</script>');
        }


    }


}