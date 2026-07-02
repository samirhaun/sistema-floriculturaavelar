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

    public function get_todos_planos_conta()
    {
        $this->db->select('cod, descricao');
        $this->db->from('plano_contas');
        $this->db->where('plano_contas.ativo', 1);
        $this->db->order_by('cod ASC');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            $planos = $query->result();
            foreach($planos as $plano){
                $plano->descricao = $this->texto_maiusculo($plano->descricao);
            }
            return $planos;
        }

        return false;
    }





    public function get_lista($limite = 1000)
    {
        $this->db->select('contas_pagar.*, fornecedores.nome as fornecedor,plano_contas.descricao as plano_conta, plano_contas.cod as cod_plano_conta');
        $this->db->from('contas_pagar');
        $this->db->join('fornecedores','fornecedores.id = contas_pagar.fornecedores_id');
        $this->db->join('plano_contas','plano_contas.id = contas_pagar.plano_contas_id');
        $this->db->order_by('contas_pagar.id', 'desc');
        if($limite){
            $this->db->limit((int) $limite);
        }
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
        $this->db->where('plano_contas.ativo', 1);
        $this->db->order_by('plano_contas.cod IS NULL', 'ASC', false);
        $this->db->order_by('plano_contas.cod', 'ASC');
        $this->db->order_by('plano_contas.descricao', 'ASC');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $this->preparar_planos_select($this->montar_arvore($query->result()));
        }else{
            return FALSE;
        }
    }

    private function preparar_planos_select($planos)
    {
        foreach($planos as $plano){
            $plano->descricao = $this->texto_maiusculo($plano->descricao);

            $prefixo = '';
            if(!empty($plano->nivel)){
                $prefixo = str_repeat('&nbsp;&nbsp;&nbsp;', (int) $plano->nivel) . '|-- ';
            }

            $codigo = trim((string) $plano->cod);
            $plano->rotulo_select = $prefixo . ($codigo !== '' ? $codigo . ' - ' : '') . $plano->descricao;
        }

        return $planos;
    }

    private function montar_arvore($contas)
    {
        $ids = array();
        foreach ($contas as $conta) {
            $ids[(int) $conta->id] = true;
        }

        $por_pai = array();
        foreach ($contas as $conta) {
            $pai = $conta->plano_conta_id ? (int) $conta->plano_conta_id : 0;
            if ($pai && !isset($ids[$pai])) {
                $pai = 0;
            }
            if (!isset($por_pai[$pai])) {
                $por_pai[$pai] = array();
            }
            $por_pai[$pai][] = $conta;
        }

        foreach ($por_pai as $pai => $filhos) {
            usort($por_pai[$pai], array($this, 'ordenar_por_codigo'));
        }

        $resultado = array();
        $this->adicionar_filhos($resultado, $por_pai, 0, 0);

        return $resultado;
    }

    private function adicionar_filhos(&$resultado, $por_pai, $pai, $nivel)
    {
        if (empty($por_pai[$pai])) {
            return;
        }

        foreach ($por_pai[$pai] as $conta) {
            $conta->nivel = $nivel;
            $conta->tem_filhos = !empty($por_pai[(int) $conta->id]) ? 1 : 0;
            $resultado[] = $conta;
            $this->adicionar_filhos($resultado, $por_pai, (int) $conta->id, $nivel + 1);
        }
    }

    private function ordenar_por_codigo($a, $b)
    {
        $cod_a = trim((string) $a->cod);
        $cod_b = trim((string) $b->cod);

        if ($cod_a === '' && $cod_b !== '') {
            return 1;
        }

        if ($cod_a !== '' && $cod_b === '') {
            return -1;
        }

        if ($cod_a !== '' || $cod_b !== '') {
            $comparacao = version_compare($cod_a, $cod_b);
            if ($comparacao !== 0) {
                return $comparacao;
            }
        }

        return strcasecmp($a->descricao, $b->descricao);
    }

    private function texto_maiusculo($texto)
    {
        $texto = trim((string) $texto);

        if (function_exists('mb_strtoupper')) {
            return mb_strtoupper($texto, 'UTF-8');
        }

        return strtoupper($texto);
    }

 




}
