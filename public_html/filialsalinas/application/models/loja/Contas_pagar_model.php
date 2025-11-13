

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contas_pagar_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function salvar($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('contas_pagar', $dados))
            {
                return $id;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert('contas_pagar', $dados))
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
        $this->db->select('contas_pagar.*, fornecedores.nome as fornecedor,plano_contas.descricao as plano_conta, plano_contas.cod as cod_plano_conta');
        $this->db->from('contas_pagar');
        $this->db->join('fornecedores','fornecedores.id = contas_pagar.fornecedores_id');
        $this->db->join('plano_contas','plano_contas.id = contas_pagar.plano_contas_id');
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
            $this->db->select('contas_pagar.*');
            $this->db->from('contas_pagar');
            $this->db->where('contas_pagar.id', $id);
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
        if($this->db->delete('contas_pagar')){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    public function get_fornecedores()
    {
        $this->db->select('fornecedores.*');
        $this->db->from('fornecedores');
        $this->db->order_by('nome ASC');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_planos_conta()
    {
        $this->db->select('plano_contas.*');
        $this->db->from('plano_contas');
        $this->db->order_by('cod ASC');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

 




}
