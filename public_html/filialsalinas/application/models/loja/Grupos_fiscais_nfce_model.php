<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grupos_fiscais_nfce_model extends CI_Model {
    public function ativos()
    {
        return $this->db->where('ativo', 1)->order_by('nome', 'ASC')->get('grupos_fiscais_nfce')->result();
    }

    public function listar()
    {
        return $this->db->order_by('nome', 'ASC')->get('grupos_fiscais_nfce')->result();
    }

    public function buscar($id)
    {
        return $this->db->where('id', (int) $id)->get('grupos_fiscais_nfce')->row();
    }

    public function salvar(array $dados, $id = null)
    {
        if ($id) return $this->db->where('id', (int) $id)->update('grupos_fiscais_nfce', $dados);
        return $this->db->insert('grupos_fiscais_nfce', $dados);
    }
}
