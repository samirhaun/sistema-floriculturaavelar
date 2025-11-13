

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendedores_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function salvar($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('vendedores', $dados))
            {
                return $id;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert('vendedores', $dados))
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
        $this->db->select('vendedores.*');
        $this->db->from('vendedores');
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
            $this->db->select('vendedores.*');
            $this->db->from('vendedores');
            $this->db->where('vendedores.id', $id);
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
        if($this->db->delete('vendedores')){
            return TRUE;
        }else{
            return FALSE;
        }
    }


 




}
