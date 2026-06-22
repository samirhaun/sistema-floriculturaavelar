<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_maquininhas_cartao extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/maquininhas_cartao_model');
        $this->load->helper('form');
        $this->load->helper('text');

        $this->set_menu_active(array(
            'menu' => 'Loja',
            'submenu' => 'Loja-maquininhas_cartao'
        ));
    }

    public function index()
    {
        $this->lista();
    }

    public function lista()
    {
        $data['dados'] = $this->maquininhas_cartao_model->get_lista();
        if (!empty($data['dados'])) {
            foreach ($data['dados'] as $maquininha) {
                $maquininha->taxas = $this->maquininhas_cartao_model->get_taxas($maquininha->id);
            }
        }

        if ($this->input->get('type')) {
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }

        $this->montaTela('loja/maquininhas_cartao/lista', $data);
    }

    public function novo()
    {
        $data = array();
        $this->montaTela('loja/maquininhas_cartao/formulario', $data);
    }

    public function editar()
    {
        if ($this->input->get('id')) {
            $data['dados'] = $this->maquininhas_cartao_model->get_registro($this->input->get('id'));
            $this->montaTela('loja/maquininhas_cartao/formulario', $data);
        }
    }

    public function salvar()
    {
        if (!$this->input->post()) {
            redirect(base_url(array('loja', 'maquininhas-cartao')));
        }

        $fornecedor_taxa = $this->maquininhas_cartao_model->get_fornecedor_taxa_padrao();
        $plano_taxa = $this->maquininhas_cartao_model->get_plano_taxa_padrao();
        $taxas = $this->taxas_post();

        if (!$fornecedor_taxa || !$plano_taxa) {
            $_GET['type'] = 'error';
            $_GET['title'] = 'Cadastro';
            $_GET['message'] = 'Fornecedor ou plano de taxa padrao nao encontrado!';
            $this->lista();
            return;
        }

        $primeira_taxa = !empty($taxas) ? $taxas[0] : array(
            'taxa_debito' => 0,
            'taxa_credito_1x' => 0,
            'taxa_credito_2x' => 0,
            'taxa_credito_3x' => 0,
            'taxa_credito_4x' => 0,
        );

        $dados = array(
            'nome' => $this->input->post('nome'),
            'fornecedor_id' => $fornecedor_taxa->id,
            'plano_contas_taxa_id' => $plano_taxa->id,
            'taxa_debito' => $primeira_taxa['taxa_debito'],
            'taxa_credito_1x' => $primeira_taxa['taxa_credito_1x'],
            'taxa_credito_2x' => $primeira_taxa['taxa_credito_2x'],
            'taxa_credito_3x' => $primeira_taxa['taxa_credito_3x'],
            'taxa_credito_4x' => $primeira_taxa['taxa_credito_4x'],
            'ativo' => $this->input->post('ativo') ? 1 : 0,
        );

        $id = $this->input->post('id') ? $this->input->post('id') : NULL;
        $salvou = $this->maquininhas_cartao_model->salvar($dados, $taxas, $id);

        $_GET['type'] = $salvou ? 'success' : 'error';
        $_GET['title'] = $id ? 'Atualizacao' : 'Cadastro';
        $_GET['message'] = $salvou ? 'Maquininha salva com sucesso!' : 'Ocorreu um erro ao salvar a maquininha!';

        $this->lista();
        $url = base_url(array('loja', 'maquininhas-cartao'));
        $this->output->append_output('<script>window.history.replaceState("", "Albedo", "'. $url .'")</script>');
    }

    public function excluir()
    {
        if ($this->input->post('id')) {
            if ($this->maquininhas_cartao_model->excluir($this->input->post('id'))) {
                echo json_encode(array('type' => 'success', 'title' => 'Exclusao', 'message' => 'Maquininha removida com sucesso!'));
            } else {
                echo json_encode(array('type' => 'error', 'title' => 'Exclusao', 'message' => 'Ocorreu um erro ao remover a maquininha!'));
            }
        }
        return;
    }

    private function decimal_post($campo)
    {
        $valor = $this->input->post($campo);
        if ($valor === null || $valor === '') {
            return 0;
        }
        return str_replace(',', '.', $valor);
    }

    private function taxas_post()
    {
        $ids = $this->input->post('taxa_id');
        $grupos = $this->input->post('grupo_bandeira');
        $bandeiras = $this->input->post('bandeiras');
        $taxas = array();

        if (empty($grupos)) {
            return $taxas;
        }

        foreach ($grupos as $key => $grupo) {
            if (trim($grupo) === '') {
                continue;
            }

            $taxas[] = array(
                'id' => isset($ids[$key]) ? $ids[$key] : null,
                'grupo_bandeira' => trim($grupo),
                'bandeiras' => isset($bandeiras[$key]) ? trim($bandeiras[$key]) : '',
                'taxa_debito' => $this->decimal_post_array('taxa_debito', $key),
                'taxa_credito_1x' => $this->decimal_post_array('taxa_credito_1x', $key),
                'taxa_credito_2x' => $this->decimal_post_array('taxa_credito_2x', $key),
                'taxa_credito_3x' => $this->decimal_post_array('taxa_credito_3x', $key),
                'taxa_credito_4x' => $this->decimal_post_array('taxa_credito_4x', $key),
            );
        }

        return $taxas;
    }

    private function decimal_post_array($campo, $key)
    {
        $valores = $this->input->post($campo);
        if (!isset($valores[$key]) || $valores[$key] === '') {
            return 0;
        }
        return str_replace(',', '.', $valores[$key]);
    }
}
