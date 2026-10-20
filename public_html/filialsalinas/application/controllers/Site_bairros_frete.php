<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_bairros_frete extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('site/bairros_frete_model');

        $this->set_menu_active(
                            array(
                                'menu' => 'bairros_frete',
                                'submenu' => 'lista-bairros-frete'
                                )
                            );
    }

    public function index()
    {
        $this->lista();
    }

    public function lista()
    {
        $data['bairros'] = $this->bairros_frete_model->get_bairros();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('site/bairros_frete/lista', $data);
    }

    public function novo()
    {
        $this->montaTela('site/bairros_frete/formulario');
    }

    public function editar()
    {
        if($this->input->get('id')){
            $dados['bairro'] = $this->bairros_frete_model->get_bairro($this->input->get('id'));
            $this->montaTela('site/bairros_frete/formulario', $dados);
        }
    }

    public function salvar()
    {
        if($this->input->post()){
            $id = $this->input->post('id') ?: NULL;

            $dados = array(
                'nome' => trim($this->input->post('nome')),
                'valor_frete' => str_replace(',', '.', $this->input->post('valor_frete')),
                'ativo' => $this->input->post('ativo') ? 1 : 0,
            );

            if($this->bairros_frete_model->salvar($dados, $id)){
                $_GET['type'] = 'success';
                $_GET['title'] = $id ? 'Atualização' : 'Cadastro';
                $_GET['message'] = $id ? 'atualizado com sucesso!' : 'cadastrado com sucesso!';
            }else{
                $_GET['type'] = 'error';
                $_GET['title'] = $id ? 'Atualização' : 'Cadastro';
                $_GET['message'] = $id ? 'Ocorreu um erro ao atualizar!' : 'Ocorreu um erro ao cadastrar!';
            }

            $this->lista();
            $url = base_url(array('site', 'bairros-frete'));
            $this->output->append_output('<script>window.history.replaceState("", "SWE", "'. $url .'")</script>');
        }
    }

    public function excluir()
    {
        if($this->input->post('id')){
            if($this->bairros_frete_model->excluir($this->input->post('id'))){
                $response['type'] = 'success';
                $response['title'] = 'Exclusão';
                $response['message'] = 'desativado com sucesso!';
                echo json_encode($response);
            }else{
                $response['type'] = 'error';
                $response['title'] = 'Exclusão';
                $response['message'] = 'Ocorreu um erro ao desativar!';
                echo json_encode($response);
            }
        }
        return;
    }

    // Busca remota pro select2 (checkout e formulário de pedido). Espera
    // ?q=termo, devolve {results: [{id, text, valor_frete}]}.
    public function buscar()
    {
        $termo = $this->input->get('q');
        $resultado = array();

        if(strlen(trim((string) $termo)) >= 2){
            $bairros = $this->bairros_frete_model->buscar($termo);
            foreach($bairros as $bairro){
                $resultado[] = array(
                    'id' => $bairro->id,
                    'text' => $bairro->nome,
                    'valor_frete' => $bairro->valor_frete,
                );
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('results' => $resultado)));
    }

    // Usado pela tela de cadastro pra avisar "já existe um bairro parecido"
    // antes de salvar -- não bloqueia, só sugere revisão (ver plano).
    public function checar_parecido()
    {
        $nome = $this->input->get('nome');
        $ignorar_id = $this->input->get('id');
        $parecidos = array();

        if(strlen(trim((string) $nome)) >= 2){
            foreach($this->bairros_frete_model->buscar_parecidos($nome, $ignorar_id) as $bairro){
                $parecidos[] = array('id' => $bairro->id, 'nome' => $bairro->nome, 'valor_frete' => $bairro->valor_frete);
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('parecidos' => $parecidos)));
    }
}
