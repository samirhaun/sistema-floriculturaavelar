<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marcas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }




      public function salvar_marca($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('marcas', $dados))
            {
                return true;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert('marcas', $dados))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }


    public function get_marcas(){

        $this->db->select('marcas.*');
        $this->db->from('marcas');
        if($query = $this->db->get()){

            return $query->result();

        }else{
            return false;
        }
    }


    public function get_marca($id){

        $this->db->select('marcas.*');
        $this->db->from('marcas');
        $this->db->where('id', $id);
        if($query = $this->db->get()){
            return $query->row();
        }else{
            return false;
        }
    }

    public function delete_marca($id){
        $this->db->where('id', $id);
        if ($this->db->delete('marcas')) {
          return true;
        }else{
          return false;
        }

    }




}
