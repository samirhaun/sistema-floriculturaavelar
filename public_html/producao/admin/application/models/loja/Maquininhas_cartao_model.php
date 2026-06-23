<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maquininhas_cartao_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function salvar($dados, $taxas = array(), $id = NULL)
    {
        $this->db->trans_start();
        $dados['updated_at'] = date('Y-m-d H:i:s');

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('maquininhas_cartao', $dados);
            $maquininha_id = $id;
        } else {
            $dados['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('maquininhas_cartao', $dados);
            $maquininha_id = $this->db->insert_id();
        }

        $this->salvar_taxas($maquininha_id, $taxas);

        $this->db->trans_complete();
        return $this->db->trans_status() ? $maquininha_id : false;
    }

    public function get_lista()
    {
        $this->db->select('maquininhas_cartao.*');
        $this->db->select('(SELECT COUNT(*) FROM maquininhas_cartao_taxas WHERE maquininhas_cartao_taxas.maquininha_cartao_id = maquininhas_cartao.id AND maquininhas_cartao_taxas.deleted_at IS NULL) as total_grupos', false);
        $this->db->from('maquininhas_cartao');
        $this->db->where('maquininhas_cartao.deleted_at IS NULL', null, false);
        $this->db->order_by('maquininhas_cartao.nome ASC');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : false;
    }

    public function get_ativas()
    {
        $this->db->select('*');
        $this->db->from('maquininhas_cartao');
        $this->db->where('ativo', 1);
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->order_by('nome ASC');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : false;
    }

    public function get_taxas_ativas()
    {
        $this->db->select('maquininhas_cartao_taxas.*, maquininhas_cartao.nome as maquininha');
        $this->db->from('maquininhas_cartao_taxas');
        $this->db->join('maquininhas_cartao', 'maquininhas_cartao.id = maquininhas_cartao_taxas.maquininha_cartao_id');
        $this->db->where('maquininhas_cartao_taxas.ativo', 1);
        $this->db->where('maquininhas_cartao_taxas.deleted_at IS NULL', null, false);
        $this->db->where('maquininhas_cartao.ativo', 1);
        $this->db->where('maquininhas_cartao.deleted_at IS NULL', null, false);
        $this->db->order_by('maquininhas_cartao.nome ASC, maquininhas_cartao_taxas.grupo_bandeira ASC');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : false;
    }

    public function get_registro($id = NULL)
    {
        if (!$id) {
            return false;
        }

        $this->db->select('*');
        $this->db->from('maquininhas_cartao');
        $this->db->where('id', $id);
        $this->db->where('deleted_at IS NULL', null, false);
        $query = $this->db->get();
        if ($query->num_rows() === 0) {
            return false;
        }

        $registro = $query->row();
        $registro->taxas = $this->get_taxas($registro->id);
        return $registro;
    }

    public function excluir($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('maquininhas_cartao', array(
            'ativo' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }

    public function get_fornecedores()
    {
        $this->db->select('*');
        $this->db->from('fornecedores');
        $this->db->order_by('nome ASC');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : false;
    }

    public function get_fornecedor_taxa_padrao()
    {
        $this->db->select('*');
        $this->db->from('fornecedores');
        $this->db->like('nome', 'ANTECIPA');
        $this->db->order_by('id ASC');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : false;
    }

    public function get_plano_taxa_padrao()
    {
        $this->db->select('*');
        $this->db->from('plano_contas');
        $this->db->where('ativo', 1);
        $this->db->where('lancamento', 1);
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->where('descricao', 'Taxa de Maquininha');
        $this->db->order_by('cod ASC');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : false;
    }

    public function get_taxas($maquininha_id)
    {
        $this->db->select('*');
        $this->db->from('maquininhas_cartao_taxas');
        $this->db->where('maquininha_cartao_id', $maquininha_id);
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->order_by('grupo_bandeira ASC');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : false;
    }

    private function salvar_taxas($maquininha_id, $taxas)
    {
        $ids_mantidos = array();

        foreach ($taxas as $taxa) {
            if (empty($taxa['grupo_bandeira'])) {
                continue;
            }

            $dados_taxa = array(
                'maquininha_cartao_id' => $maquininha_id,
                'grupo_bandeira' => $taxa['grupo_bandeira'],
                'bandeiras' => $taxa['bandeiras'],
                'taxa_debito' => $taxa['taxa_debito'],
                'taxa_credito_1x' => $taxa['taxa_credito_1x'],
                'taxa_credito_2x' => $taxa['taxa_credito_2x'],
                'taxa_credito_3x' => $taxa['taxa_credito_3x'],
                'taxa_credito_4x' => $taxa['taxa_credito_4x'],
                'taxa_antecipacao' => $taxa['taxa_antecipacao'],
                'ativo' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            );

            if (!empty($taxa['id'])) {
                $this->db->where('id', $taxa['id']);
                $this->db->where('maquininha_cartao_id', $maquininha_id);
                $this->db->update('maquininhas_cartao_taxas', $dados_taxa);
                $ids_mantidos[] = (int) $taxa['id'];
            } else {
                $dados_taxa['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('maquininhas_cartao_taxas', $dados_taxa);
                $ids_mantidos[] = (int) $this->db->insert_id();
            }
        }

        $this->db->where('maquininha_cartao_id', $maquininha_id);
        if (!empty($ids_mantidos)) {
            $this->db->where_not_in('id', $ids_mantidos);
        }
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->update('maquininhas_cartao_taxas', array(
            'ativo' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function get_planos_taxa()
    {
        $this->db->select('*');
        $this->db->from('plano_contas');
        $this->db->where('ativo', 1);
        $this->db->where('lancamento', 1);
        $this->db->where('deleted_at IS NULL', null, false);
        $this->db->where("(cod LIKE '2.3.%' OR descricao LIKE '%Maquin%')", null, false);
        $this->db->order_by('cod ASC');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : false;
    }
}
