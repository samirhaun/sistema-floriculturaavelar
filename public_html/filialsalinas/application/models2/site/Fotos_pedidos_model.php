<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fotos_pedidos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function salvar_pedido($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('pedidos_fotos', $dados))
            {
                return true;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert_batch('pedidos_fotos', $dados))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    public function salvar_fotos_pedido($dados)
    {
        // var_dump($dados);
        // exit;
        if($this->db->insert_batch('fotos_pedidos', $dados))
            {
                return true;
            }
            else
            {
                return false;
            }
    }

    public function excluir_imagem($imagem)
    {
        $this->db->trans_start();
            foreach ($imagem as $id) {
                $this->db->where('id', $id);
                $this->db->delete('fotos_pedidos');
            }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return FALSE;
        }else{
            return TRUE;
        }
    }

    public function get_pedidos($id)
    {
        $this->db->select('*');
        $this->db->from('pedidos_fotos');
        $this->db->where('pedidos_id', $id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_status_pedido($id)
    {
        $this->db->select('status_pedido,id');
        $this->db->from('pedidos');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return FALSE;
        }
    }

    public function get_pedido($id)
    {
        $this->db->select('*');
        $this->db->from('pedidos');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return FALSE;
        }
    }

    public function get_foto($id)
    {
        $this->db->select('*');
        $this->db->from('pedidos_fotos');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return FALSE;
        }
    }

    public function get_fotos_pedido($idpedido)
    {
        $this->db->select('*');
        $this->db->from('fotos_pedidos');
        $this->db->where('pedidos_id', $idpedido);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }



    function excluir_pedido($id){
        $this->db->where('id', $id);
        if($this->db->delete('pedidos_fotos')){

                return TRUE;

        }else{
            return FALSE;
        }
    }
}
