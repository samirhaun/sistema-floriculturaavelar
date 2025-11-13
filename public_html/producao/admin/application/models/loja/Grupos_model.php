<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grupos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function salvar($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('grupos_categorias', $dados))
            {
                return true;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert('grupos_categorias', $dados))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    public function get_grupos_categorias()
    {
        $this->db->select('*');
        $this->db->from('grupos_categorias');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_grupos()
    {
        $this->db->select('*');
        $this->db->from('grupos_categorias');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_grupo($id=null)
    {
        if($id){
            $this->db->select('*');
            $this->db->from('grupos_categorias');
            $this->db->where('id', $id);
            $query = $this->db->get();
            if($query->num_rows() > 0){
                return $query->row();
            }else{
                return FALSE;
            }
        }
    }

    function excluir($id){
        
        $this->db->query('SET foreign_key_checks = 0;');
        $this->db->where('id', $id);
        $retorno = $this->db->delete('grupos_categorias');
        $this->db->query('SET foreign_key_checks = 1;');

        if($retorno){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}