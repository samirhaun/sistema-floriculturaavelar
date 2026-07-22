<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/taxas_cartao_model');
    }


    public function get_pedidos_new($limite = 1000)
    {
        $this->db->select('pedidos.*');
        $this->db->select('clientes.nome as cliente_pedido, clientes.telefone as cliente_telefone');
        $this->db->select('entregadores.descricao as entregador');
        $this->db->select('CASE WHEN COUNT(contas_receber.id) > 0 AND SUM(CASE WHEN contas_receber.status = 0 THEN 1 ELSE 0 END) = 0 THEN 1 ELSE 0 END as pedido_pago', false);
        $this->db->from('pedidos');
        $this->db->join('clientes', 'clientes.id=pedidos.clientes_id','left');
        $this->db->join('entregadores', 'entregadores.id=pedidos.entregadores_id','left');
        $this->db->join('contas_receber', 'contas_receber.pedidos_id=pedidos.id','left');
        $this->db->group_by('pedidos.id');
        $this->db->order_by('id','desc');
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

    public function salvar_pedido($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('pedidos', $dados))
            {
                return $id;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert('pedidos', $dados))
            {
                return $this->db->insert_id() ;
            }
            else
            {
                return false;
            }
        }
    }

    public function check_pedido_duplicado($clientes_id, $valor_total, $minutos = 5)
    {
        $this->db->where('clientes_id', $clientes_id)->where('valor_total', $valor_total);
        $this->db->where('data_pedido >=', date('Y-m-d H:i:s', strtotime('-'.(int) $minutos.' minutes')));
        $this->db->where('origem', 1)->where_in('status_pedido', array(0, 1, 5));
        return $this->db->get('pedidos')->num_rows() > 0;
    }

    public function salvar_pedido_sem_duplicar($dados, $minutos = 5)
    {
        $cliente_id = isset($dados['clientes_id']) ? (int) $dados['clientes_id'] : 0;
        $valor_total = isset($dados['valor_total']) ? number_format((float) $dados['valor_total'], 2, '.', '') : '0.00';
        $nome_lock = 'pedido_'.sha1($cliente_id.'|'.$valor_total);
        $lock = $this->db->query('SELECT GET_LOCK(?, 10) AS adquirido', array($nome_lock))->row();
        if (!$lock || (int) $lock->adquirido !== 1) return array('id' => null, 'duplicado' => false, 'erro' => true);

        try {
            if ($this->check_pedido_duplicado($cliente_id, $valor_total, $minutos)) return array('id' => null, 'duplicado' => true, 'erro' => false);
            if (!$this->db->insert('pedidos', $dados)) return array('id' => null, 'duplicado' => false, 'erro' => true);
            return array('id' => $this->db->insert_id(), 'duplicado' => false, 'erro' => false);
        } finally {
            $this->db->query('SELECT RELEASE_LOCK(?)', array($nome_lock));
        }
    }



    public function get_pedidos_andamento($id=NULL)
    {
         $this->db->select('pedidos.*');
        $this->db->select('clientes.nome As nome_cliente');
        $this->db->select('usuarios.nome');
        $this->db->from('pedidos');
        $this->db->join('clientes', 'pedidos.clientes_id=clientes.id');
        $this->db->join('usuarios', 'pedidos.usuarios_id=usuarios.id');
        $status_pedido = array(2,5);
        $this->db->where_not_in('pedidos.status_pedido', $status_pedido);

        if($id){
            $this->db->where('pedidos.usuarios_id', $id);
        }


        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_pedidos_orcamento($id=NULL)
        {
            $this->db->select('pedidos.*');
            $this->db->select('pedidos.data_entrega');
            $this->db->select('clientes.nome As nome_cliente');
            $this->db->select('usuarios.nome');
            $this->db->from('pedidos');
            $this->db->join('clientes', 'pedidos.clientes_id=clientes.id');
            $this->db->join('usuarios', 'pedidos.usuarios_id=usuarios.id');
            $this->db->where('pedidos.status_pedido', 5);
            if($id){
                $this->db->where('pedidos.usuarios_id', $id);
            }

            $this->db->order_by("STR_TO_DATE(pedidos.data_entrega,'%d/%m/%Y') ASC  ");

            $query = $this->db->get();
            if($query->num_rows() > 0){
                return $query->result();
            }else{
                return FALSE;
            }
        }

  public function get_pedidos($id=NULL)
    {
         $this->db->select('pedidos.*');
        $this->db->select('clientes.nome As nome_cliente');
        $this->db->select('usuarios.nome');
        $this->db->from('pedidos');
        $this->db->join('clientes', 'pedidos.clientes_id=clientes.id');
        $this->db->join('usuarios', 'pedidos.usuarios_id=usuarios.id');

        if($id){
            $this->db->where('pedidos.usuarios_id', $id);
        }


        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }




    public function get_pedido($id=null)
    {
        if($id){
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
    }

    public function get_produtos($id=null)
    {
        if($id){
            $this->db->select('*');
            $this->db->from('pedido_produto');
            $this->db->where('pedidos_id', $id);
            $query = $this->db->get();
            if($query->num_rows() > 0){
                return $query->result();
            }else{
                return FALSE;
            }
        }
    }

    function excluir_pedido($id){
        $this->db->trans_start();
        $this->taxas_cartao_model->excluir_financeiro_pedido($id);
        $this->db->where('id', $id);
        $this->db->delete('pedidos');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function excluir_itens_pedido($id){
        $this->db->where('pedidos_id', $id);
        if($this->db->delete('pedido_produto')){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function excluir_item_pedido($id){
        $this->db->where('id', $id);
        if($this->db->delete('pedido_produto')){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function salvar_contas_receber($dados, $pedido_id = null){
        if(!$pedido_id || empty($dados) || !is_array($dados)){
            log_message('error', 'Contas a receber preservadas: tentativa de salvar lista vazia para pedido #'.(int) $pedido_id);
            return false;
        }

        $this->db->trans_start();

        $lotes_antecipacao = $this->taxas_cartao_model->lotes_do_pedido($pedido_id);

        $contas_atuais = $this->db->select('id')
            ->from('contas_receber')
            ->where('pedidos_id', $pedido_id)
            ->get()->result();

        if(!empty($contas_atuais)){
            $ids_contas = array();
            foreach($contas_atuais as $conta_atual){
                $ids_contas[] = $conta_atual->id;
            }

$this->db->where_in('contas_receber_id', $ids_contas);
            $this->db->where_in('origem', array('taxa_maquininha', 'taxa_antecipacao'));
            $this->db->delete('contas_pagar');
        }

        $this->db->where('pedidos_id', $pedido_id);
        $this->db->delete('contas_receber');

        if(!empty($dados)){
            foreach($dados as $receita){
                $receita = $this->preencher_bandeira_cartao($receita);
                // data_pago_taxa e' um dado temporario usado somente para
                // calcular a conta da taxa. Nao e' uma coluna de
                // contas_receber e, portanto, nao pode ir para o INSERT.
                $receita_para_salvar = $receita;
                unset($receita_para_salvar['data_pago_taxa']);
                $this->db->insert('contas_receber', $receita_para_salvar);
                $conta_receber_id = $this->db->insert_id();
$taxa_conta_id = $this->criar_conta_taxa_maquininha($receita, $conta_receber_id, $pedido_id);

                if($taxa_conta_id){
                    $this->db->where('id', $conta_receber_id);
                    $this->db->update('contas_receber', array('taxa_contas_pagar_id' => $taxa_conta_id));
                }

                $lote = $this->taxas_cartao_model->lote_da_receita($receita);
                if($lote){
                    $lotes_antecipacao[] = $lote;
                }
            }
        }

        $this->taxas_cartao_model->recalcular_lotes($lotes_antecipacao);
        $liquidacoes_cartao = array();
        foreach ($dados as $receita) {
            if (in_array((int) $receita['forma_pgto'], array(3, 4, 5, 6)) && !empty($receita['data_liquidacao'])) {
                $liquidacoes_cartao[] = $receita['data_liquidacao'];
            }
        }
        $this->taxas_cartao_model->recalcular_lotes_por_liquidacao($liquidacoes_cartao);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    private function preencher_bandeira_cartao($receita)
    {
        if(!empty($receita['bandeira_cartao'])){
            $receita['bandeira_cartao'] = $this->taxas_cartao_model->normalizar_bandeira($receita['bandeira_cartao']);
            return $receita;
        }
        if(empty($receita['maquininha_taxa_id'])){
            $receita['bandeira_cartao'] = null;
            return $receita;
        }
        $grupo = $this->db->select('grupo_bandeira, bandeiras')->from('maquininhas_cartao_taxas')->where('id', $receita['maquininha_taxa_id'])->get()->row();
        $bandeira = $grupo ? trim((string) $grupo->bandeiras) : '';
        $opcoes = preg_split('/[,;\/]+/', $bandeira, -1, PREG_SPLIT_NO_EMPTY);
        if(count($opcoes) === 1) $bandeira = trim($opcoes[0]);
        elseif($grupo) $bandeira = trim((string) $grupo->grupo_bandeira);
        $receita['bandeira_cartao'] = $bandeira ? $this->taxas_cartao_model->normalizar_bandeira($bandeira) : null;
        return $receita;
    }

    private function criar_conta_taxa_maquininha($receita, $conta_receber_id, $pedido_id)
    {
        if((empty($receita['maquininha_cartao_id']) && empty($receita['maquininha_taxa_id'])) || !in_array((int) $receita['forma_pgto'], array(2, 3, 4, 5, 6))){
            return null;
        }

        $taxa_bandeira = null;
        if(!empty($receita['maquininha_taxa_id'])){
            $taxa_bandeira = $this->db->select('*')
                ->from('maquininhas_cartao_taxas')
                ->where('id', $receita['maquininha_taxa_id'])
                ->where('ativo', 1)
                ->where('deleted_at IS NULL', null, false)
                ->get()->row();

            if($taxa_bandeira){
                $receita['maquininha_cartao_id'] = $taxa_bandeira->maquininha_cartao_id;
            }
        }

        $maquininha = $this->db->select('*')
            ->from('maquininhas_cartao')
            ->where('id', $receita['maquininha_cartao_id'])
            ->where('ativo', 1)
            ->where('deleted_at IS NULL', null, false)
            ->get()->row();

        if(!$maquininha){
            return null;
        }

        $campo_taxa = $this->campo_taxa_maquininha($receita['forma_pgto']);
        $origem_taxa = $taxa_bandeira ? $taxa_bandeira : $maquininha;
        $percentual = isset($origem_taxa->{$campo_taxa}) ? (float) $origem_taxa->{$campo_taxa} : 0;
        $valor_receita = (float) $receita['valor'];
        $valor_taxa = round(($valor_receita * $percentual) / 100, 2);

        if($valor_taxa <= 0){
            return null;
        }

        $data_vencimento = !empty($receita['data_pago_taxa']) ? $receita['data_pago_taxa'] : (!empty($receita['data_pago']) ? $receita['data_pago'] : $receita['data_vencimento']);

        $conta_pagar = array(
            'descricao' => 'Taxa maquininha '.$maquininha->nome.($taxa_bandeira ? ' - '.$taxa_bandeira->grupo_bandeira : '').' - pedido #'.$pedido_id,
            'data_vencimento' => $data_vencimento,
            'valor' => $valor_taxa,
            'status' => ((int) $receita['status'] === 1) ? 1 : 0,
            'data_pago' => ((int) $receita['status'] === 1) ? $data_vencimento : null,
            'plano_contas_id' => $maquininha->plano_contas_taxa_id,
            'fornecedores_id' => $maquininha->fornecedor_id,
            // A taxa e abatida no mesmo recebimento do cartao. Mantem a
            // forma da venda para que o fluxo de caixa a considere.
            'forma_pgto' => (int) $receita['forma_pgto'],
            'contas_receber_id' => $conta_receber_id,
            'maquininha_cartao_id' => $maquininha->id,
            'bandeira_cartao' => !empty($receita['bandeira_cartao']) ? $this->taxas_cartao_model->normalizar_bandeira($receita['bandeira_cartao']) : null,
            'base_calculo' => $valor_receita,
            'percentual_taxa' => $percentual,
            'origem' => 'taxa_maquininha',
        );

        $this->db->insert('contas_pagar', $conta_pagar);
        return $this->db->insert_id();
    }

private function campo_taxa_maquininha($forma_pgto)
    {
        switch ((int) $forma_pgto) {
            case 2:
                return 'taxa_debito';
            case 3:
                return 'taxa_credito_1x';
            case 4:
                return 'taxa_credito_2x';
            case 5:
                return 'taxa_credito_3x';
            case 6:
                return 'taxa_credito_4x';
            default:
                return 'taxa_debito';
        }
    }

    public function get_contas_receber_pedidos($pedido_id)
    {

            $this->db->select('*');
            $this->db->from('contas_receber');
            $this->db->where('pedidos_id', $pedido_id);
            return $this->db->get()->result();

    }


    public function salvar_produtos_pedidos($dados, $pedido_id = null){

        if(empty($dados) || !is_array($dados)){
            log_message('error', 'Produtos do pedido preservados: tentativa de salvar lista vazia para pedido #'.(int) $pedido_id);
            return false;
        }

        // echo '<pre>';
        // print_r($dados);
        // exit;
      

            if($pedido_id){

                $this->db->where('pedidos_id', $pedido_id);
                $result = $this->db->delete('pedido_produto');

                if($result){
                    $transacao = $this->db->insert_batch('pedido_produto', $dados);
                }

            }else{
                $transacao = $this->db->insert_batch('pedido_produto', $dados);
                $pedido_id = $dados[0]['pedidos_id'];
            }

            //VAMOS MEXER NO ESTOQUE, ALIEMNTAR A SAÍDA
            if($transacao):

                //vamos pegar o id da saida primeiro
                $this->db->select('id');
                $this->db->from('produtos_entradas');
                $this->db->where('pedidos_id', $pedido_id);
                $saida_pedido = $this->db->get()->row();

                if(!empty($saida_pedido)):

                    $this->db->where('produtos_entradas_id', $saida_pedido->id);
                    $this->db->delete('produtos_entrada_produtos');

                    $this->db->where('id', $saida_pedido->id);
                    $this->db->delete('produtos_entradas');

                    $limpou_saida_vinculada = true;

                else:

                    $limpou_saida_vinculada = true;

                endif;


                if($limpou_saida_vinculada):

                    //INSERE SAÍDA

                    $saida = array(
                        'tipo' => 2,
                        'descricao' => 'Saída de produto(s) pedido:#'.$pedido_id,
                        'data_hora' => date('Y-m-d H:i:s'),
                        'usuarios_id' => $_SESSION['usuario']->id,
                        'pedidos_id' => $pedido_id,
                      );

                    //INSERE PRODUTOS SAÍDA
                    if($this->db->insert('produtos_entradas', $saida)):

                        $idsaida = $this->db->insert_id();

                        foreach($dados as $prod_saida):

                            //primeira regra vamos ver se o produto necessita de da saída no estoque
                            $this->db->select('estoque');
                            $this->db->from('produtos');
                            $this->db->where('id', $prod_saida['produtos_id']);
                            $info_prod = $this->db->get()->row();

                            //SIGNIFICA QUE TEMOS QUE DA A SAÍDA NESTE PRODUTO
                            if($info_prod->estoque == 1):

                                $saida_prod = array(
                                    'qtd' => $prod_saida['quantidade'],
                                    'produtos_id' => $prod_saida['produtos_id'],
                                    'produtos_entradas_id' => $idsaida,
                                  );
    
                                $this->db->insert('produtos_entrada_produtos', $saida_prod);

                            endif;


                            //vamos analisar se possui produtos vinculados agora para da saída nos mesmos
                            $this->db->select('*');
                            $this->db->from('produtos_vinculados');
                            $this->db->where('produtos_id', $prod_saida['produtos_id']);
                            $prods_vinculados = $this->db->get()->result();

                            if(!empty($prods_vinculados)):

                                foreach($prods_vinculados as $prod_vinculado):

                                    $saida_prod = array(
                                        'qtd' => $prod_vinculado->qtd * $prod_saida['quantidade'],
                                        'produtos_id' => $prod_vinculado->produto_vinculado_id,
                                        'produtos_entradas_id' => $idsaida,
                                      );
        
                                    $this->db->insert('produtos_entrada_produtos', $saida_prod);

                                endforeach;

                            endif;





                        endforeach;

                    endif;

                    return true;



                endif;

            endif;

     


   
    }


    public function salvar_produtos_pedidos_duplica($dados){

       foreach ($dados as $key => $value) {
           
                $this->db->insert('pedido_produto', $value);
       }
             
            

    }


    public function salvar_produtos_fotos_duplica($dados){

       foreach ($dados as $key => $value) {
           
                $this->db->insert('pedidos_fotos', $value);
       }
             
            

    }



    public function get_fotos($id=NULL){
        if($id){
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
    }


    public function get_pedidos_full(){
        $this->db->select('pedidos.*');
        $this->db->from('pedidos');
        $this->db->order_by('id','desc');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_info_usuario_logado(){
        $this->db->select('usuarios.*');
        $this->db->from('usuarios');
        $this->db->where('id', $_SESSION['usuario']->id);
        return $this->db->get()->row();
    }


    


}
