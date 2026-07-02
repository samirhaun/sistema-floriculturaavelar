

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plano_contas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function salvar($dados, $id=NULL)
    {
        if (isset($dados['plano_conta_id']) && $dados['plano_conta_id'] === '') {
            $dados['plano_conta_id'] = NULL;
        }

        if ($id && isset($dados['plano_conta_id']) && (int) $dados['plano_conta_id'] === (int) $id) {
            return false;
        }

        if (isset($dados['ativo'])) {
            $dados['deleted_at'] = ((int) $dados['ativo'] === 1) ? NULL : date('Y-m-d H:i:s');
        }

        if($id){
            $dados['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            if($this->db->update('plano_contas', $dados))
            {
                return $id;
            }
            else{
                return false;
            }
        }else{
            $dados['created_at'] = date('Y-m-d H:i:s');
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





    public function get_lista($mostrar_inativos = FALSE)
    {
        $this->db->select('plano_contas.*');
        $this->db->from('plano_contas');
        if (!$mostrar_inativos) {
            $this->db->where('plano_contas.ativo', 1);
        }
        $this->db->order_by('plano_contas.cod IS NULL', 'ASC', false);
        $this->db->order_by('plano_contas.cod', 'ASC');
        $this->db->order_by('plano_contas.descricao', 'ASC');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $this->montar_arvore($query->result());
        }else{
            return FALSE;
        }
    }

    public function get_opcoes_arvore($ignorar_id = NULL)
    {
        $this->db->select('plano_contas.*');
        $this->db->from('plano_contas');
        $this->db->where('plano_contas.ativo', 1);
        if ($ignorar_id) {
            $this->db->where('plano_contas.id !=', $ignorar_id);
        }
        $this->db->order_by('plano_contas.cod IS NULL', 'ASC', false);
        $this->db->order_by('plano_contas.cod', 'ASC');
        $this->db->order_by('plano_contas.descricao', 'ASC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $this->preparar_planos_select($this->montar_arvore($query->result()));
        }

        return array();
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
        $ids = $this->get_ids_com_descendentes($id);

        $this->db->where_in('id', $ids);
        if($this->db->update('plano_contas', array(
            'ativo' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ))){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    private function get_ids_com_descendentes($id)
    {
        $ids = array((int) $id);
        $pendentes = array((int) $id);

        while (!empty($pendentes)) {
            $atual = array_shift($pendentes);
            $this->db->select('id');
            $this->db->from('plano_contas');
            $this->db->where('plano_conta_id', $atual);
            $query = $this->db->get();

            foreach ($query->result() as $filho) {
                $filho_id = (int) $filho->id;
                if (!in_array($filho_id, $ids)) {
                    $ids[] = $filho_id;
                    $pendentes[] = $filho_id;
                }
            }
        }

        return $ids;
    }


 




}
