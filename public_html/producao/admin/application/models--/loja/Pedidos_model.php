<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function salvar_pedido($dados, $id=NULL)
    {


        if($id){
            $this->db->where('id', $id);
            if($this->db->update('pedidos', $dados))
            {
                //limpar saidas caso pedido seja cancelado
                if(isset($dados['status_pedido']) && $dados['status_pedido'] == '4'
                || isset($dados['status_pedido']) && $dados['status_pedido'] == '5'):
                  $this->limpa_saida_estoque_pedido($id);
                endif;

                return $id;
            }
            else{
                return false;
            }

            

            
        }else{
            if($this->db->insert('pedidos', $dados))
            {
                return $this->db->insert_id();
            }
            else
            {
                return false;
            }
        }
    }

    public function limpa_saida_estoque_pedido($pedido)
    {

         //vamos pegar o id da saida primeiro
         $this->db->select('id');
         $this->db->from('produtos_entradas');
         $this->db->where('pedidos_id', $pedido);
         $saida_pedido = $this->db->get()->row();

         if(!empty($saida_pedido)):

             $this->db->where('produtos_entradas_id', $saida_pedido->id);
             $this->db->delete('produtos_entrada_produtos');

             $this->db->where('id', $saida_pedido->id);
             $this->db->delete('produtos_entradas');

         endif;

         return true;

    }

    public function get_pedidos()
    {
        $this->db->select('pedidos.*');
        $this->db->select('clientes.nome as cliente_pedido, clientes.telefone as cliente_telefone');
        $this->db->from('pedidos');
        $this->db->join('clientes', 'clientes.id=pedidos.clientes_id','left');
        $this->db->order_by('id','desc');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    
    }

    public function finalizar_pedido($id){

       $this->db->where('id', $id);
        $result = $this->db->update('pedidos',array('status_pedido' => 10));
        if($result){
            return TRUE;
        }
    }



    public function busca_pedido($idpedido){

        $this->db->select('pedidos.*');
        $this->db->from('pedidos'); 
        $this->db->where('id', $idpedido);
         $query = $this->db->get();
        if($query->num_rows() > 0){

            return $query->row();
        }else{
            return FALSE;
        }



    }

    public function get_pedido_produtos($id){

            $this->db->select('pedido_produto.*');
            $this->db->select('produtos.*');
            $this->db->select('produto_tamanhos.id As tamanho_id,produto_tamanhos.quantidade As quantidade_tamanho,');
            $this->db->select('tamanhos.tamanho As tamanho_nome');
            $this->db->from('pedido_produto');
            $this->db->join('produtos', 'pedido_produto.produtos_id=produtos.id');
            $this->db->join('produto_tamanhos', 'pedido_produto.produto_tamanhos_id=produto_tamanhos.id', 'left');
            $this->db->join('tamanhos', 'produto_tamanhos.tamanhos_id=tamanhos.id', 'left');

 /*      $this->db->join('perfis', 'perfis.id=pedidos.perfis_id');
        $this->db->join('produtos', 'pedidos.produtos_id=produtos.id');
        $this->db->join('perfil_endereco', 'perfil_endereco.perfis_id=perfis.id');
        $this->db->join('cidades', 'cidades.id=perfil_endereco.cidade_id');
        $this->db->join('estados', 'estados.id=perfil_endereco.uf_id');*/




        $this->db->where('pedido_produto.pedidos_id', $id);
        $query = $this->db->get();
    
        if($query->num_rows() > 0){

            return $query->result();
        }else{
            return FALSE;
        }
    }



    public function get_pedido($idpedido)
    {
        $this->db->select('pedidos.*');

        $this->db->select('clientes.nome as cliente_pedido, clientes.telefone as cliente_telefone,  clientes.email as cliente_email');
        $this->db->select('vendedores.descricao as vendedor_pedido');
        $this->db->select('eventos.descricao as evento_pedido');
        $this->db->from('pedidos');
        $this->db->join('clientes', 'clientes.id=pedidos.clientes_id','left');
        $this->db->join('eventos', 'eventos.id=pedidos.eventos_id','left');
        $this->db->join('vendedores', 'vendedores.id=pedidos.vendedores_id','left');
 /*      $this->db->join('perfis', 'perfis.id=pedidos.perfis_id');
        $this->db->join('produtos', 'pedidos.produtos_id=produtos.id');
        $this->db->join('perfil_endereco', 'perfil_endereco.perfis_id=perfis.id');
        $this->db->join('cidades', 'cidades.id=perfil_endereco.cidade_id');
        $this->db->join('estados', 'estados.id=perfil_endereco.uf_id');*/




        $this->db->where('pedidos.id', $idpedido);
        $query = $this->db->get();
        
        
        if($query->num_rows() > 0){

            return $query->row();
        }else{
            return FALSE;
        }
    }

    public function get_itens_pedidos($idpedido)
    {
        $this->db->select('produtos_pedido.valor, produtos_pedido.quantidade, produtos_pedido.produtos_id');
        $this->db->select('produtos.id, produtos.nome, produtos.nome_url, produtos.descricao_breve');
        $this->db->from('produtos_pedido');
        $this->db->join('produtos', 'produtos_pedido.produtos_id=produtos.id');
        $this->db->where('produtos_pedido.pedidos_id', $idpedido);

        $query = $this->db->get();
        if($query->num_rows() > 0){
            $data = $query->result();
            return $data;
        }else{
            return FALSE;
        }
    }


    public function get_perfis(){

        $this->db->select('perfis.*');
        $this->db->from('perfis');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }

    }


      public function get_produtos(){

        $this->db->select('produtos.*');
        $this->db->from('produtos');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }

    }

    public function alterar_qtd_produto($id){

        $this->db->select('pedidos.*');
        $this->db->from('pedidos');
        $this->db->where('id', $id);
        $query = $this->db->get();

        $this->db->set('estoque', 'estoque-'.$query->row('quantidade').'', false);

        $this->db->where('id',$query->row('produtos_id'));

        $this->db->update('produtos');

    }



    public function get_clientes()
    {
        $this->db->select('clientes.*');
        $this->db->from('clientes');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }


    public function get_vendedores()
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

    /* RELATORIOS */

    function get_demonstrativo($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto){

        $this->db->select('pedidos.*, "pedido" as tipo_credito');
        $this->db->select('clientes.nome as cliente_pedido, clientes.telefone as cliente_telefone,  clientes.email as cliente_email');
        $this->db->select('vendedores.descricao as vendedor_pedido');
        $this->db->select('eventos.descricao as evento_pedido');
        $this->db->from('pedidos');
        $this->db->join('clientes', 'clientes.id=pedidos.clientes_id','left');
        $this->db->join('eventos', 'eventos.id=pedidos.eventos_id','left');
        $this->db->join('vendedores', 'vendedores.id=pedidos.vendedores_id','left');

        if($dtreferencia == 'pgto'):
            $this->db->where('pedidos.data_pago BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        elseif($dtreferencia == 'emissao'): 
            $this->db->where('pedidos.data_pedido BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        elseif($dtreferencia == 'entrega'):  
            $this->db->where('pedidos.data_entrega BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        endif;

        //FITRO ORIGEM
        if($origem != 'all'){
                $this->db->where('pedidos.origem', $origem);
        }

        //POR USUARIO/PROFISSIONAL
        if($vendedor != 'all'){
            $this->db->where('pedidos.vendedores_id', $vendedor);
         }

         //POR PAGAMENTO
        if($situacaopgto != 'all'){
            $this->db->where('pedidos.pedido_pago', $situacaopgto);
         }


         $pedidos = $this->db->get()->result();




         //AGORA TEMOS QUE JUNTAR COM QUEM PAGOU SOMENTE ENTRADA
         $entradas = array();

         if($dtreferencia == 'pgto'):

            $this->db->select('pedidos.*, "entrada" as tipo_credito');
            $this->db->select('clientes.nome as cliente_pedido, clientes.telefone as cliente_telefone,  clientes.email as cliente_email');
            $this->db->select('vendedores.descricao as vendedor_pedido');
            $this->db->select('eventos.descricao as evento_pedido');
            $this->db->from('pedidos');
            $this->db->join('clientes', 'clientes.id=pedidos.clientes_id','left');
            $this->db->join('eventos', 'eventos.id=pedidos.eventos_id','left');
            $this->db->join('vendedores', 'vendedores.id=pedidos.vendedores_id','left');

            $this->db->where('pedidos.data_pedido BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
            // $this->db->where('pedidos.data_pago', null);
            $this->db->where('pedidos.valor_entrada >', 0);

            //FITRO ORIGEM
            if($origem != 'all'){
                    $this->db->where('pedidos.origem', $origem);
            }

            //POR USUARIO/PROFISSIONAL
            if($vendedor != 'all'){
                $this->db->where('pedidos.vendedores_id', $vendedor);
            }

            //POR PAGAMENTO
            if($situacaopgto != 'all'){
                $this->db->where('pedidos.pedido_pago', $situacaopgto);
            }


            $entradas = $this->db->get()->result();

            
        // echo '<pre>';
        // print_r($entradas);
        // exit;


         endif;


         //JUNTANDO OS ARRAUS
        if(!empty($entradas)):
            $data = array_merge($pedidos,$entradas);
        else:
            $data = $pedidos;
        endif;


            for ($i=0; $i < sizeof($data); $i++) {
                $data[$i]->produtos = $this->get_pedido_produtos($data[$i]->id);
            }


        //       echo '<pre>';
        // print_r($data);
        // exit;


            return $data;


    }

    function get_demonstrativo_pagar($inicio, $fim, $origem, $vendedor, $plano_conta){


        $this->db->select('contas_pagar.*, fornecedores.nome as fornecedor,plano_contas.descricao as plano_conta, plano_contas.cod as cod_plano_conta');
        $this->db->from('contas_pagar');
        $this->db->join('fornecedores','fornecedores.id = contas_pagar.fornecedores_id');
        $this->db->join('plano_contas','plano_contas.id = contas_pagar.plano_contas_id');
        $this->db->where('contas_pagar.data_pago BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        $this->db->where('contas_pagar.status', 1);


        //FITRO PLANO CONTA
        if($plano_conta != 'all'){
                $this->db->where('contas_pagar.plano_contas_id', $plano_conta);
        }

        // //POR USUARIO/PROFISSIONAL
        // if($vendedor != 'all'){
        //     $this->db->where('pedidos.vendedores_id', $vendedor);
        //  }


         $data = $this->db->get()->result();


            // for ($i=0; $i < sizeof($data); $i++) {
            //     $data[$i]->produtos = $this->get_pedido_produtos($data[$i]->id);
            // }


            return $data;


    }


}