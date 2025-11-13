<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_eventos extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('site/eventos_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'agenda',
                                'submenu' => 'site-eventos'
                                )
                            );
    }

    public function index()
    {
        $this->lista();
    }


    public function lista()
    {
        $data['eventos'] =  $this->eventos_model->get_eventos();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('site/eventos/lista', $data);
    }

    function novo_evento(){
        $this->montaTela('site/eventos/formulario');
    }

    function salvar_evento(){
        $this->load->helper('text');
        if($this->input->post()){
            if(!empty($_FILES['evento']['name'])){
                $this->load->library('upload', array(
                        'upload_path' => FCPATH.'../public/imagens/alojamentos',
                        'allowed_types' => 'jpg|png|gif',
                        'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                        'max_size' => 8 * 1024,
                        'remove_spaces' => TRUE
                    ));

                if ($this->upload->do_upload('evento')){
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

                if($this->input->post('imagem_evento')){
                    if ($_FILES['evento']['name'] != $this->input->post('imagem_evento')) {
                        $apagar = FCPATH.'../public/imagens/alojamentos/' . $this->input->post('imagem_evento');
                        @unlink($apagar);
                    }
                }
            }else{
                if($this->input->post('imagem_evento')){
                    $file_data['file_name'] = $this->input->post('imagem_evento');
                }else{
                    $file_data['file_name'] = '';
                }
            }

            $dados = array(
                'titulo' => $this->input->post('titulo'),
                'descricao' => $this->input->post('descricao'),
                'vagas' => $this->input->post('vagas'),
                'texto_breve' => $this->input->post('texto_breve'),
                'imagem' => $file_data['file_name']
            );

            $id = NULL;

            //editar evento
            if($this->input->post('id')){
                $id = $this->input->post('id');
            }else{
                $dados['data'] = time();
            }

            if($this->eventos_model->salvar_evento($dados, $id))
            {
                $_GET['type'] = 'success';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Evento atualizada com sucesso!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Evento cadastrada com sucesso!';
                }
            }
            else
            {
                $_GET['type'] = 'error';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Ocorreu um erro ao atualizar a evento!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Ocorreu um erro ao cadastrar a evento!';
                }
            }
            $this->lista();
            $url = base_url(array('site', 'eventos'));
            $this->output->append_output('<script>window.history.replaceState("", "Sólido kids", "'. $url .'")</script>');
        }
    }

    public function editar_evento()
    {
        if($this->input->get('id')){
            $dados['evento'] = $this->eventos_model->get_evento($this->input->get('id'));
            $this->montaTela('site/eventos/formulario', $dados);
        }
    }

    function excluir_evento(){
        if ($this->input->post('id')) {
            $evento = $this->eventos_model->get_evento($this->input->post('id'));
            if($this->eventos_model->excluir_evento($this->input->post('id'))){
                $apagar = FCPATH.'../files/uploads/eventos/' . $evento->imagem;
                @unlink($apagar);
                $response['type'] = 'success';
                $response['title'] = 'Exclusão';
                $response['message'] = 'evento excluído com sucesso!';
                echo json_encode($response);
            }else{
                $response['type'] = 'error';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Ocorreu um erro ao excluír o evento!';
                echo json_encode($response);
            }
        }
        return;
    }



    public function excluir_pessoa(){



        if ($this->input->post('id')) {
          
            if($this->eventos_model->excluir_pessoa($this->input->post('id'))){
                
                $this->eventos_model->atualiza_alojamento($this->input->get('id_alojamento'));

                $response['type'] = 'success';
                $response['title'] = 'Exclusão';
                $response['message'] = 'evento excluído com sucesso!';
                echo json_encode($response);
            }else{
                $response['type'] = 'error';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Ocorreu um erro ao excluír o evento!';
                echo json_encode($response);
            }
        }
        return;
    }

    public function lista_agendamentos(){

         $this->set_menu_active(
                            array(
                                'menu' => 'agenda',
                                'submenu' => 'agenda-lista'
                                )
                            );

        $data['agendamentos'] =  $this->eventos_model->get_agendamentos();
    
        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('site/eventos/lista_agendamentos', $data);
    }


    public function lista_pessoas(){

           $this->set_menu_active(
                            array(
                                'menu' => 'agenda',
                                'submenu' => 'agenda-lista'
                                )
                            );

        $data['pessoas'] =  $this->eventos_model->get_pessoas($this->input->get('id'));
        $data['id_alojamento'] =  $this->input->get('id_alojamento');

        // var_dump($data['pessoas']);
        // exit;
        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('site/eventos/lista_pessoas', $data);
    }

}