<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Noticias_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_num_noticias($busca=NULL)
    {
        $this->db->from('noticias');
        if($busca){
            $this->db->like('titulo', $busca);
            $this->db->or_like('texto', $busca);
        }
        return $this->db->count_all_results();
    }

    public function get_noticias($pagina, $itens_pagina, $busca=NULL)
    {
        $this->db->select('id, titulo, nome_url, texto_breve, imagem, autor, data')
                ->from('noticias')
                ->order_by('id', 'DESC')
                ->limit($itens_pagina, $itens_pagina * $pagina);
        if($busca){
            $this->db->like('titulo', $busca);
            $this->db->or_like('texto', $busca);
        }
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }



    public function get_ultimas_noticias()
    {
        $this->db->select('id, titulo, nome_url, texto_breve, imagem, autor, data')
                ->from('noticias')
                ->order_by('id', 'DESC')
                ->limit(3);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }


    public function get_noticia($nome_url)
    {
        $this->db->select('*')
                ->from('noticias')
                ->where('nome_url', $nome_url);
        $query = $this->db->get();
        if($query->num_rows() == 1){
            return $query->row();
        }else{
            return FALSE;
        }
    }
    public function get_noticia_ultimas()
    {
        $this->db->select('*')
                ->from('noticias');
                $this->db->limit(3);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }


        public function get_noticia_galeria($id)
    {
        $this->db->select('*')
                ->from('galeria_noticias');
                $this->db->where('noticias_id', $id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_home()
    {
        $this->db->select('*')
                ->from('home');
        $query = $this->db->get();
        if($query->num_rows() == 1){
            return $query->row();
        }else{
            return FALSE;
        }
    }
}
