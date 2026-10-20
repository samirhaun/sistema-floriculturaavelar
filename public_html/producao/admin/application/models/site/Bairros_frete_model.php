<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bairros_frete_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('bairro');
    }

    public function get_bairros($somente_ativos = false)
    {
        $this->db->select('*');
        $this->db->from('bairros_frete');
        if ($somente_ativos) {
            $this->db->where('ativo', 1);
        }
        $this->db->order_by('nome', 'ASC');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : array();
    }

    public function get_bairro($id)
    {
        $this->db->select('*');
        $this->db->from('bairros_frete');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : FALSE;
    }

    // Busca por nome_normalizado (mesmo critério usado no autocomplete do
    // checkout/pedido) -- usada pelo endpoint de busca remota do select2.
    public function buscar($termo, $limite = 20)
    {
        $termo_normalizado = normalizar_bairro($termo);
        $this->db->select('id, nome, valor_frete');
        $this->db->from('bairros_frete');
        $this->db->where('ativo', 1);
        $this->db->like('nome_normalizado', $termo_normalizado);
        $this->db->order_by('nome', 'ASC');
        $this->db->limit($limite);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : array();
    }

    // Candidatos "parecidos" com o nome informado, pra tela de cadastro
    // avisar sobre possível duplicata antes de salvar (ver bairros_parecidos
    // no helper). Não bloqueia -- só sugere revisão manual.
    public function buscar_parecidos($nome, $ignorar_id = null)
    {
        $normalizado = normalizar_bairro($nome);
        $existentes = $this->get_bairros();
        $parecidos = array();

        foreach ($existentes as $bairro) {
            if ($ignorar_id && (int) $bairro->id === (int) $ignorar_id) {
                continue;
            }
            if (bairros_parecidos($normalizado, $bairro->nome_normalizado)) {
                $parecidos[] = $bairro;
            }
        }

        return $parecidos;
    }

    public function salvar($dados, $id = NULL)
    {
        $dados['nome_normalizado'] = normalizar_bairro($dados['nome']);
        $dados['updated_at'] = date('Y-m-d H:i:s');

        if ($id) {
            $this->db->where('id', $id);
            return $this->db->update('bairros_frete', $dados);
        }

        $dados['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('bairros_frete', $dados);
    }

    public function excluir($id)
    {
        // Soft delete -- pedidos antigos podem referenciar este id.
        $this->db->where('id', $id);
        return $this->db->update('bairros_frete', array(
            'ativo' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ));
    }
}
