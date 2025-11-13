<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blocos_estaticos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function atualizar_bloco_estatico($dados)
    {
        if($dados){
            $this->db->where('nome_unico', $dados['nome_unico']);
            if($this->db->update('blocos_estaticos', $dados))
            {
                return true;
            }
            else{
                return false;
            }
        }
    }


    public function get_bloco_estatico($nome_unico = null)
    {
        if($nome_unico){

            $this->db->select('*');
            $this->db->from('blocos_estaticos');
            $this->db->where('nome_unico', $nome_unico);

            $query = $this->db->get();

            if($query->num_rows() > 0){
                return $query->row();
            }else{
                return FALSE;
            }

        }
    }

}