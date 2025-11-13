

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plano_contas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function salvar($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('plano_contas', $dados))
            {
                return $id;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert('plano_contas', $dados))
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
        $this->db->select('plano_contas.*');
        $this->db->from('plano_contas');
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
            $this->db->select('plano_contas.*');
            $this->db->from('plano_contas');
            $this->db->where('plano_contas.id', $id);
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
        if($this->db->delete('plano_contas')){
            return TRUE;
        }else{
            return FALSE;
        }
    }


 




}
