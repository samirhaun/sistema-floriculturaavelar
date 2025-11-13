

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entregadores_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function salvar($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('entregadores', $dados))
            {
                return $id;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert('entregadores', $dados))
            {
                return $this->db->insert_id();
            }
            else
            {
                return false;
            }
        }
    }





    public function get_lista()
    {
        $this->db->select('entregadores.*');
        $this->db->from('entregadores');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    

    public function get_registro($id=null)
    {
        if($id){
            $this->db->select('entregadores.*');
            $this->db->from('entregadores');
            $this->db->where('entregadores.id', $id);
            $query = $this->db->get();
            if($query->num_rows() > 0){
                $data = $query->row();
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    

   

  

  

 
    function excluir($id){
        $this->db->where('id', $id);
        if($this->db->delete('entregadores')){
            return TRUE;
        }else{
            return FALSE;
        }
    }


 




}
