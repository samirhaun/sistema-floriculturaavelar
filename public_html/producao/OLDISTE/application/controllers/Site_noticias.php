<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_noticias extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('site/noticias_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'conteudo',
                                'submenu' => 'site-noticias'
                                )
                            );
    }

    public function index()
    {
        $this->lista();
    }


    public function lista()
    {
    
    $data['noticias'] =  $this->noticias_model->get_noticias();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('site/noticias/lista', $data);
    }

    function nova_noticia(){
        $this->montaTela('site/noticias/formulario');
    }

    function salvar_noticia(){
        $this->load->helper('text');
        if($this->input->post()){
            if(!empty($_FILES['noticia']['name'])){
                $this->load->library('upload', array(
                        'upload_path' => FCPATH.'../assets/images/noticias',
                        'allowed_types' => 'jpg|png|gif',
                        'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                        'max_size' => 8 * 1024,
                        'remove_spaces' => TRUE
                    ));

                if ($this->upload->do_upload('noticia')){
                    $file_data = $this->upload->data();
                    $this->load->library('image_lib', array(
                            'image_library' => 'gd2',
                            'source_image' => $file_data['full_path'],
                            'create_thumb' => FALSE,
                            'maintain_ratio' => TRUE,
                            //'width' => 800,
                            //'height' => 400
                        )
                    );
                    $this->image_lib->resize();
                }

                if($this->input->post('imagem_noticia')){
                    if ($_FILES['noticia']['name'] != $this->input->post('imagem_noticia')) {
                        $apagar = FCPATH.'../assets/images/noticias/' . $this->input->post('imagem_noticia');
                        @unlink($apagar);
                    }
                }
            }else{
                if($this->input->post('imagem_noticia')){
                    $file_data['file_name'] = $this->input->post('imagem_noticia');
                }else{
                    $file_data['file_name'] = '';
                }
            }

           



            //caso nome_url n seja unico
            $slug_url =  url_title(convert_accented_characters($this->input->post('titulo')), '-', TRUE);
            $test_url = $this->noticias_model->confere_nome_url($slug_url);
            
            $data_noti = new DateTime($this->input->post('data'));

            if($test_url){
                //ja existe essa slug
               $nome_url_gerado = $this->gerar_novo_nome_url($slug_url);//gerar uma nova

                   $dados = array(
                    'titulo' => $this->input->post('titulo'),
                    //'nome_url' => url_title(convert_accented_characters($this->input->post('titulo')), '-', TRUE),
                    'nome_url' => $nome_url_gerado,
                    'texto' => $this->input->post('conteudo'),
                    'texto_breve' => $this->input->post('introducao'),
                    'data' => ($data_noti->format('Y-m-d')),
                    'imagem' => $file_data['file_name']
                );
            }else{

                  $dados = array(
                    'titulo' => $this->input->post('titulo'),
                    'nome_url' => url_title(convert_accented_characters($this->input->post('titulo')), '-', TRUE),
                    'texto' => $this->input->post('conteudo'),
                    'texto_breve' => $this->input->post('introducao'),
                    'data' => ($data_noti->format('Y-m-d')),
                    'imagem' => $file_data['file_name']
                );


            }


            $id = NULL;

            //editar noticia
            if($this->input->post('id')){
                $id = $this->input->post('id');
            }else{
                $dados['data'] = time();
            }

            if($this->noticias_model->salvar_noticia($dados, $id))
            {
                $_GET['type'] = 'success';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Notícia atualizada com sucesso!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Notícia cadastrada com sucesso!';
                }
            }
            else
            {
                $_GET['type'] = 'error';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Ocorreu um erro ao atualizar a notícia!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Ocorreu um erro ao cadastrar a notícia!';
                }
            }
            $this->lista();
            $url = base_url(array('site'));
            $this->output->append_output('<script>window.history.replaceState("", "Sólido kids", "'. $url .'")</script>');
        }
    }

    public function editar_noticia()
    {
        if($this->input->get('id')){
            $dados['noticia'] = $this->noticias_model->get_noticia($this->input->get('id'));
            $this->montaTela('site/noticias/formulario', $dados);
        }
    }

    function excluir_noticia(){
        if ($this->input->post('id')) {

            //excluir respostas 
            if($this->noticias_model->excluir_respostas_cometarios_noticia($this->input->post('id'))){
                //excluir comentarios
                if($this->noticias_model->excluir_cometarios_noticia($this->input->post('id'))){
                    $noticia = $this->noticias_model->get_noticia($this->input->post('id'));
                    if($this->noticias_model->excluir_noticia($this->input->post('id'))){
                        $apagar = FCPATH.'../assets/images/noticias/' . $noticia->imagem;
                        @unlink($apagar);
                        $response['type'] = 'success';
                        $response['title'] = 'Exclusão';
                        $response['message'] = 'noticia excluído com sucesso!';
                        echo json_encode($response);
                    }else{
                        $response['type'] = 'error';
                        $response['title'] = 'Exclusão';
                        $response['message'] = 'Ocorreu um erro ao excluír o noticia!';
                        echo json_encode($response);
                    }
                }
            }
        }
        return;
    }




    function gerar_novo_nome_url($slug_url){        

        $contador = 1000000;
        for ($i=1; $i <$contador; $i++) {

           $novo_slug_url = $slug_url ."-" . $i;
           $test_url =  $this->noticias_model->confere_nome_url($novo_slug_url);

           if($test_url == false){
            break;

           }

            
        }
       /* echo '<pre>';
        print_r($novo_slug_url);
        echo '</pre>';
        exit();*/

        return $novo_slug_url;

    }





    public function adicionar_galeria(){

  
         $dados['noticia'] = $this->noticias_model->get_noticia($this->input->get('id'));
        $this->montaTela('site/noticias/formulario_galeria', $dados);
    }





    public function salvar_galeria(){
      
           if($this->input->post()){
            $id_noticia = $this->input->post('id');
            $db_img_galeria = array();
            $cont = count($_FILES['galeria']['name']);

            $files = $_FILES;
            $_FILES = array();

            $config_upload = array(
                            'upload_path' => FCPATH.'../assets/images/noticias/galeria',
                            'allowed_types' => 'jpg|png|gif',
                            'create_thumb' => FALSE,
                            // 'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                            'max_size' => 8 * 1024,
                            'remove_spaces' => TRUE
                            );

            $config_image_lib = array(
                                    'image_library' => 'gd2',
                                    // 'source_image' => $file_data['full_path'],
                                    // 'new_image' => FCPATH.'../files/uploads/galerias-fotos/'.$small_thumb,
                                   // 'width' => 150,
                                   // 'height' => 150
                                    );

            $this->load->library('upload', $config_upload);
            $this->load->library('image_lib', $config_image_lib);

            for ($i=0; $i < $cont; $i++) {
                $_FILES['galeria']['name']= $files['galeria']['name'][$i];
                $_FILES['galeria']['type']= $files['galeria']['type'][$i];
                $_FILES['galeria']['tmp_name']= $files['galeria']['tmp_name'][$i];
                $_FILES['galeria']['error']= $files['galeria']['error'][$i];
                $_FILES['galeria']['size']= $files['galeria']['size'][$i];

                $config_upload['file_name'] = hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500));

                $this->upload->initialize($config_upload);

                if ($this->upload->do_upload('galeria')){
                    $file_data = $this->upload->data();

                    $small_thumb = 'S_' . $file_data['file_name'];
                    $config_image_lib['new_image'] = FCPATH.'../assets/images/noticias/galeria/';
                    $config_image_lib['source_image'] = $file_data['full_path'];

                    $this->image_lib->initialize($config_image_lib);
                    $this->image_lib->resize();
                    array_push($db_img_galeria, array('imagem' => $file_data['file_name'], 'noticias_id' => $id_noticia));
                     
                }   
            }
            }

            
            if (count($db_img_galeria)>0) {
                
                if ($this->noticias_model->salvar_galeria($db_img_galeria)) {
                         $_GET['type'] = 'success';
               
                    $_GET['title'] = 'Adicionado';
                    $_GET['message'] = 'galeria adicionada com sucesso!';


                        $this->lista();
                        $url = base_url(array('site'));
                        $this->output->append_output('<script>window.history.replaceState("", "", "'. $url .'")</script>');
                }


            }



    }



    public function galeria(){
        

         if($this->input->get('id')){
            $dados['noticia'] = $this->noticias_model->get_noticia($this->input->get('id'));
            $dados['galeria'] = $this->noticias_model->get_galeria_noticia($this->input->get('id'));
            $this->montaTela('site/noticias/lista_galeria', $dados);
        }

    }

    public function galeria_excluir(){

                     $galeria = $this->noticias_model->get_galeria_imagem($this->input->post('id'));
                  
                    if($this->noticias_model->excluir_imagem_galeria($this->input->post('id'))){
                        $apagar = FCPATH.'../assets/images/noticias/galeria/'.$galeria->imagem;
                        @unlink($apagar);
                        $response['type'] = 'success';
                        $response['title'] = 'Exclusão';
                        $response['message'] = 'excluído com sucesso!';
                        echo json_encode($response);
                    }else{
                        $response['type'] = 'error';
                        $response['title'] = 'Exclusão';
                        $response['message'] = 'Ocorreu um erro ao excluír!';
                        echo json_encode($response);
                    }

    }



}