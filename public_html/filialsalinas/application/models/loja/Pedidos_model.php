<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/taxas_cartao_model');
    }

    public function salvar_pedido($dados, $id=NULL)
    {


        if($id){
            $status_anterior = $this->db->select('status_pedido')->from('pedidos')->where('id', $id)->get()->row();
            $this->db->where('id', $id);
            if($this->db->update('pedidos', $dados))
            {
                //limpar saidas caso pedido seja cancelado
                if(isset($dados['status_pedido']) && $dados['status_pedido'] == '4'
                || isset($dados['status_pedido']) && $dados['status_pedido'] == '5'):
                  $this->limpa_saida_estoque_pedido($id);
                endif;

                if(isset($dados['status_pedido']) && $status_anterior){
                    $era_cancelado = in_array((int) $status_anterior->status_pedido, array(4, 5));
                    $esta_cancelado = in_array((int) $dados['status_pedido'], array(4, 5));
                    if($era_cancelado !== $esta_cancelado) $this->taxas_cartao_model->sincronizar_status_pedido($id);
                }

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

    public function get_pedidos($limite = 1000)
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

    public function get_pedidos_data_tables()
    {
        $this->db->select('pedidos.origem, 
                           pedidos.id,
                           pedidos.data_pedido,
                           ');
        // $this->db->select('clientes.nome as cliente_pedido, clientes.telefone as cliente_telefone');
        // $this->db->select('entregadores.descricao as entregador');
        $this->db->from('pedidos');
        $this->db->join('clientes', 'clientes.id=pedidos.clientes_id','left');
        $this->db->join('entregadores', 'entregadores.id=pedidos.entregadores_id','left');
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

            $data =  $query->row();

            $this->db->select('*');
            $this->db->from('contas_receber');
            $this->db->where('pedidos_id', $data->id);
            $data->receitas = $this->db->get()->result();


            $data->pedido_pago = 1;

            $this->db->select('status');
            $this->db->from('contas_receber');
            $this->db->where('pedidos_id', $data->id);
            $receitas_peiddo = $this->db->get()->result();

            foreach ($receitas_peiddo as $receita_peiddo):
                

                if($receita_peiddo->status == 0):

                    $data->pedido_pago = 0;

                endif;

                
            endforeach;


            return $data;

            
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

    public function get_entregadores()
    {
        $this->db->select('entregadores.*');
        $this->db->from('entregadores');
        $this->db->order_by('descricao','asc');
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

    function get_demonstrativo($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto){

        // echo '<pre>';
        // print_r($situacaopgto);
        // exit;

        $this->db->select('pedidos.*, "pedido" as tipo_credito');
        $this->db->select('clientes.nome as cliente_pedido, clientes.telefone as cliente_telefone,  clientes.email as cliente_email');
        $this->db->select('vendedores.descricao as vendedor_pedido');
        $this->db->select('eventos.descricao as evento_pedido');
        $this->db->from('pedidos');
        $this->db->join('clientes', 'clientes.id=pedidos.clientes_id','left');
        $this->db->join('eventos', 'eventos.id=pedidos.eventos_id','left');
        $this->db->join('vendedores', 'vendedores.id=pedidos.vendedores_id','left');
        $this->_excluir_pedidos_cancelados();
        // $this->db->where('pedidos.valor_entrada <=', 0);

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
        if(!in_array("all", $situacaopgto)){
            
            //pedido pago completo
            if(in_array(1, $situacaopgto)):
                $this->db->where('pedidos.pedido_pago', 1);
            endif;

            //pedido nao pago
            if(in_array(0, $situacaopgto)):
                $this->db->where('pedidos.pedido_pago', 0);
            endif;


            //pedido pago apenas entrada
            if(in_array(2, $situacaopgto)):
                $this->db->where('pedidos.pedido_pago', 0);
                $this->db->where('pedidos.data_pgto_entrada is NOT NULL', NULL, FALSE);
            endif;

         }

         //POR ENTREGADOR
        if($entregador != 'all'){
            $this->db->where('pedidos.entregadores_id', $entregador);
         }

//POR FORMA DE PGTO
        if(!empty($formapgto)){
            if(is_array($formapgto)){
                $this->db->where_in('contas_pagar.forma_pgto', $formapgto);
            } else {
                $this->db->where('contas_pagar.forma_pgto', $formapgto);
            }
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
            $this->_excluir_pedidos_cancelados();

            $this->db->where('pedidos.data_pgto_entrada BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
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
            if(!in_array("all", $situacaopgto)){
            
                //pedido pago completo
                if(in_array(1, $situacaopgto)):
                    $this->db->where('pedidos.pedido_pago', 1);
                endif;
    
                //pedido nao pago
                if(in_array(0, $situacaopgto)):
                    $this->db->where('pedidos.pedido_pago', 0);
                endif;
    
    
                //pedido pago apenas entrada
                if(in_array(2, $situacaopgto)):
                    $this->db->where('pedidos.pedido_pago', 0);
                    $this->db->where('pedidos.data_pgto_entrada is NOT NULL', NULL, FALSE);
                endif;
    
             }

             //POR ENTREGADOR
            if($entregador != 'all'){
                $this->db->where('pedidos.entregadores_id', $entregador);
            }

//POR FORMA DE PGTO
        if(!empty($formapgto)){
            if(is_array($formapgto)){
                $this->db->where_in('contas_pagar.forma_pgto', $formapgto);
            } else {
                $this->db->where('contas_pagar.forma_pgto', $formapgto);
            }
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

    function get_demonstrativo_pagar($inicio, $fim, $origem, $vendedor, $plano_conta, $referencia, $formapgto){


        $this->db->select('contas_pagar.*, fornecedores.nome as fornecedor,plano_contas.descricao as plano_conta, plano_contas.cod as cod_plano_conta');
        $this->db->from('contas_pagar');
        $this->db->join('fornecedores','fornecedores.id = contas_pagar.fornecedores_id');
        $this->db->join('plano_contas','plano_contas.id = contas_pagar.plano_contas_id');

        if($referencia =='emissao' || $referencia =='entrega'):

            $this->db->where('contas_pagar.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);


        else:

            $this->db->where('contas_pagar.data_pago BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
            $this->db->where('contas_pagar.status', 1);

        endif;
        

        


        //FITRO PLANO CONTA
        if($plano_conta != 'all'){
                $this->db->where('contas_pagar.plano_contas_id', $plano_conta);
        }

        //POR FORMA DE PGTO
        if(!empty($formapgto)){
            if(is_array($formapgto)){
                $this->db->where_in('contas_pagar.forma_pgto', $formapgto);
            } else {
                $this->db->where('contas_pagar.forma_pgto', $formapgto);
            }
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


    /* DEMONSTRATIVO FINANCIERO */
    function get_demonstrativo_novo($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto){

        // echo '<pre>';
        // print_r($situacaopgto);
        // exit;

        $this->db->select('contas_receber.data_vencimento as vencimento_receita, contas_receber.valor as valor_receita, contas_receber.status as status_receita,contas_receber.data_pago as data_pago_receita,contas_receber.forma_pgto as forma_pgto_receita');
        $this->db->select('CASE WHEN pedidos.status_pedido IN (4, 5) THEN 1 ELSE 0 END as cancelada_relatorio', false);
        $this->db->select('pedidos.*, "pedido" as tipo_credito');
        $this->db->select('clientes.nome as cliente_pedido, clientes.telefone as cliente_telefone,  clientes.email as cliente_email');
        $this->db->select('vendedores.descricao as vendedor_pedido');
        $this->db->select('eventos.descricao as evento_pedido');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id=contas_receber.pedidos_id','left');
        $this->db->join('clientes', 'clientes.id=pedidos.clientes_id','left');
        $this->db->join('eventos', 'eventos.id=pedidos.eventos_id','left');
        $this->db->join('vendedores', 'vendedores.id=pedidos.vendedores_id','left');
        // $this->db->where('pedidos.valor_entrada <=', 0);

        if($dtreferencia == 'pgto'):
            $this->db->where('COALESCE(contas_receber.data_liquidacao, contas_receber.data_pago) BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        elseif($dtreferencia == 'emissao'): 
            $this->db->where('contas_receber.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);

        endif;

     

         //POR PAGAMENTO
        if(!in_array("all", $situacaopgto)){
            
            //pedido pago completo
            if(in_array(1, $situacaopgto)):
                $this->db->where('contas_receber.status', 1);
            endif;

            //pedido nao pago
            if(in_array(0, $situacaopgto)):
                $this->db->where('contas_receber.status', 0);
            endif;

         }

//POR FORMA DE PGTO
         if(!empty($formapgto)){
             if(is_array($formapgto)){
                 $this->db->where_in('contas_receber.forma_pgto', $formapgto);
             } else {
                 $this->db->where('contas_receber.forma_pgto', $formapgto);
             }
          }

         //POR VENDEDOR
        if($vendedor != 'all'){
            $this->db->where('pedidos.vendedores_id', $vendedor);
         }

         return $this->db->get()->result();


    }

    function get_demonstrativo_pagar_novo($inicio, $fim, $origem, $vendedor, $plano_conta, $referencia, $formapgto,$situacaopgto){


        $this->db->select('contas_pagar.*, fornecedores.nome as fornecedor,plano_contas.descricao as plano_conta, plano_contas.cod as cod_plano_conta');
        $this->db->from('contas_pagar');
        $this->db->join('fornecedores','fornecedores.id = contas_pagar.fornecedores_id');
        $this->db->join('plano_contas','plano_contas.id = contas_pagar.plano_contas_id');

        if($referencia =='emissao'):

            $this->db->where('contas_pagar.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);


        else:

            $this->db->where('contas_pagar.data_pago BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
            $this->db->where('contas_pagar.status', 1);

        endif;
        


         //POR PAGAMENTO
         if(!in_array("all", $situacaopgto)){
            
            //pedido pago completo
            if(in_array(1, $situacaopgto)):
                $this->db->where('contas_pagar.status', 1);
            endif;

            //pedido nao pago
            if(in_array(0, $situacaopgto)):
                $this->db->where('contas_pagar.status', 0);
            endif;

         }
        


        //FITRO PLANO CONTA
        if($plano_conta != 'all'){
                $this->db->where('contas_pagar.plano_contas_id', $plano_conta);
        }

        //POR FORMA DE PGTO
        if(!empty($formapgto)){
            if(is_array($formapgto)){
                $this->db->where_in('contas_pagar.forma_pgto', $formapgto);
            } else {
                $this->db->where('contas_pagar.forma_pgto', $formapgto);
            }
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

    function get_receitas_abertas_por_vencimento($inicio, $fim, $origem, $vendedor, $entregador, $formapgto)
    {
        $this->db->select('contas_receber.id as conta_receber_id, contas_receber.data_vencimento as vencimento_receita, contas_receber.valor as valor_receita, contas_receber.forma_pgto as forma_pgto_receita');
        $this->db->select('pedidos.id as pedido_id, clientes.nome as cliente_pedido, vendedores.descricao as vendedor_pedido');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id=contas_receber.pedidos_id', 'left');
        $this->db->join('clientes', 'clientes.id=pedidos.clientes_id', 'left');
        $this->db->join('vendedores', 'vendedores.id=pedidos.vendedores_id', 'left');
        $this->db->where('contas_receber.status', 0);
        $this->_excluir_pedidos_cancelados();
        $this->db->where('contas_receber.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);

        if($origem != 'all'){
            $this->db->where('pedidos.origem', $origem);
        }

        if($vendedor != 'all'){
            $this->db->where('pedidos.vendedores_id', $vendedor);
        }

        if($entregador != 'all'){
            $this->db->where('pedidos.entregadores_id', $entregador);
        }

        if(!empty($formapgto)){
            if(is_array($formapgto)){
                $this->db->where_in('contas_receber.forma_pgto', $formapgto);
            } else {
                $this->db->where('contas_receber.forma_pgto', $formapgto);
            }
        }

        $this->db->order_by('contas_receber.data_vencimento', 'ASC');
        $this->db->order_by('pedidos.id', 'ASC');

        return $this->db->get()->result();
    }

    function get_demonstrativo_novo_count($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto){
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id=contas_receber.pedidos_id','left');
        $this->_apply_filtros_receitas($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto);
        return $this->db->count_all_results();
    }

    function get_demonstrativo_novo_paginated($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto, $limit, $offset){
        $this->db->select('contas_receber.id, contas_receber.data_vencimento as vencimento_receita, contas_receber.valor as valor_receita, contas_receber.status as status_receita,contas_receber.data_pago as data_pago_receita,contas_receber.forma_pgto as forma_pgto_receita');
        $this->db->select('CASE WHEN pedidos.status_pedido IN (4, 5) THEN 1 ELSE 0 END as cancelada_relatorio', false);
        $this->db->select('pedidos.*, "pedido" as tipo_credito');
        $this->db->select('clientes.nome as cliente_pedido, clientes.telefone as cliente_telefone, clientes.email as cliente_email');
        $this->db->select('vendedores.descricao as vendedor_pedido');
        $this->db->select('eventos.descricao as evento_pedido');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id=contas_receber.pedidos_id','left');
        $this->db->join('clientes', 'clientes.id=pedidos.clientes_id','left');
        $this->db->join('eventos', 'eventos.id=pedidos.eventos_id','left');
        $this->db->join('vendedores', 'vendedores.id=pedidos.vendedores_id','left');
        $this->_apply_filtros_receitas($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto);
        $this->db->order_by('contas_receber.data_vencimento', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    function get_demonstrativo_pagar_novo_count($inicio, $fim, $origem, $vendedor, $plano_conta, $referencia, $formapgto, $situacaopgto){
        $this->_apply_filtros_despesas($inicio, $fim, $plano_conta, $referencia, $formapgto, $situacaopgto);
        return $this->db->count_all_results('contas_pagar');
    }

    function get_demonstrativo_pagar_novo_paginated($inicio, $fim, $origem, $vendedor, $plano_conta, $referencia, $formapgto, $situacaopgto, $limit, $offset){
        $this->db->select('contas_pagar.*, fornecedores.nome as fornecedor,plano_contas.descricao as plano_conta, plano_contas.cod as cod_plano_conta');
        $this->db->from('contas_pagar');
        $this->db->join('fornecedores','fornecedores.id = contas_pagar.fornecedores_id');
        $this->db->join('plano_contas','plano_contas.id = contas_pagar.plano_contas_id');
        $this->_apply_filtros_despesas($inicio, $fim, $plano_conta, $referencia, $formapgto, $situacaopgto);
        $this->db->order_by('contas_pagar.data_vencimento', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    function get_demonstrativo_totais_por_forma_pgto($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto){
        $this->db->select('contas_receber.forma_pgto, contas_receber.status, SUM(contas_receber.valor) as total');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id=contas_receber.pedidos_id','left');
        $this->_apply_filtros_receitas($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto);
        $this->db->group_by('contas_receber.forma_pgto, contas_receber.status');
        return $this->db->get()->result();
    }

    function get_fluxo_entradas_vendido_recebido_por_forma($inicio, $fim, $origem, $vendedor, $entregador, $formapgto){
        $resultado = array();

        $this->db->select('contas_receber.forma_pgto, SUM(contas_receber.valor) as total');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id = contas_receber.pedidos_id');
        $this->_excluir_pedidos_cancelados();
        $this->db->where('contas_receber.status', 1);
        $this->db->where('contas_receber.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:59'), '', false);
        $this->_apply_filtros_comuns_entradas($origem, $vendedor, $entregador, $formapgto);
        $this->db->group_by('contas_receber.forma_pgto');
        foreach($this->db->get()->result() as $linha){
            $forma = (int) $linha->forma_pgto;
            if(!isset($resultado[$forma])) $resultado[$forma] = array('vendido' => 0, 'recebido' => 0);
            $resultado[$forma]['vendido'] = (float) $linha->total;
        }

        $this->db->select('contas_receber.forma_pgto, SUM(contas_receber.valor) as total');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id = contas_receber.pedidos_id');
        $this->_excluir_pedidos_cancelados();
        $this->db->where('contas_receber.status', 1);
        $this->db->where('COALESCE(contas_receber.data_liquidacao, contas_receber.data_pago) BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:59'), '', false);
        $this->_apply_filtros_comuns_entradas($origem, $vendedor, $entregador, $formapgto);
        $this->db->group_by('contas_receber.forma_pgto');
        foreach($this->db->get()->result() as $linha){
            $forma = (int) $linha->forma_pgto;
            if(!isset($resultado[$forma])) $resultado[$forma] = array('vendido' => 0, 'recebido' => 0);
            $resultado[$forma]['recebido'] = (float) $linha->total;
        }

        ksort($resultado);
        $linhas = array();
        foreach($resultado as $forma => $valores){
            $linha = new stdClass();
            $linha->forma_pgto = $forma;
            $linha->total_vendido = $valores['vendido'];
            $linha->total_recebido = $valores['recebido'];
            $linhas[] = $linha;
        }
        return $linhas;
    }

    private function _apply_filtros_comuns_entradas($origem, $vendedor, $entregador, $formapgto){
        if($origem && $origem != 'all') $this->db->where('pedidos.origem', $origem);
        if($vendedor && $vendedor != 'all') $this->db->where('pedidos.vendedores_id', $vendedor);
        if($entregador && $entregador != 'all') $this->db->where('pedidos.entregadores_id', $entregador);
        if(!empty($formapgto)){
            if(is_array($formapgto)) $this->db->where_in('contas_receber.forma_pgto', $formapgto);
            else $this->db->where('contas_receber.forma_pgto', $formapgto);
        }
    }

    function get_demonstrativo_totais_por_forma_pgto_despesas($inicio, $fim, $plano_conta, $referencia, $formapgto, $situacaopgto){
        $this->db->select('contas_pagar.forma_pgto, contas_pagar.status, SUM(contas_pagar.valor) as total');
        $this->db->from('contas_pagar');
        $this->_apply_filtros_despesas($inicio, $fim, $plano_conta, $referencia, $formapgto, $situacaopgto);
        $this->db->group_by('contas_pagar.forma_pgto, contas_pagar.status');
        return $this->db->get()->result();
    }

    function get_demonstrativo_totais_gerais_receitas($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto){
        $this->db->select('contas_receber.status, SUM(contas_receber.valor) as total');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id=contas_receber.pedidos_id','left');
        $this->_apply_filtros_receitas($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto);
        $this->db->group_by('contas_receber.status');
        return $this->db->get()->result();
    }

    function get_demonstrativo_totais_gerais_despesas($inicio, $fim, $plano_conta, $referencia, $formapgto, $situacaopgto){
        $this->db->select('contas_pagar.status, SUM(contas_pagar.valor) as total');
        $this->db->from('contas_pagar');
        $this->_apply_filtros_despesas($inicio, $fim, $plano_conta, $referencia, $formapgto, $situacaopgto);
        $this->db->group_by('contas_pagar.status');
        return $this->db->get()->result();
    }

    function get_demonstrativo_por_categoria($inicio, $fim, $dtreferencia){
        $this->db->select('categorias.nome as categoria, SUM(pedido_produto.quantidade * produtos.valor) as total');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id = contas_receber.pedidos_id');
        $this->db->join('pedido_produto', 'pedido_produto.pedidos_id = pedidos.id');
        $this->db->join('produtos', 'produtos.id = pedido_produto.produtos_id');
        $this->db->join('categorias', 'categorias.id = produtos.categorias_id');
        $this->_excluir_pedidos_cancelados();
        if($dtreferencia == 'pgto'):
            $this->db->where('COALESCE(contas_receber.data_liquidacao, contas_receber.data_pago) BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        else:
            $this->db->where('contas_receber.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        endif;
        $this->db->group_by('categorias.id, categorias.nome');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result();
    }

    function get_demonstrativo_detalhe_categoria($inicio, $fim, $dtreferencia){
        $this->db->select('categorias.id as cat_id, categorias.nome as categoria, pedidos.id as pedido_id, clientes.nome as cliente, produtos.titulo as produto, pedido_produto.quantidade, produtos.valor, (pedido_produto.quantidade * produtos.valor) as subtotal');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id = contas_receber.pedidos_id');
        $this->db->join('clientes', 'clientes.id = pedidos.clientes_id', 'left');
        $this->db->join('pedido_produto', 'pedido_produto.pedidos_id = pedidos.id');
        $this->db->join('produtos', 'produtos.id = pedido_produto.produtos_id');
        $this->db->join('categorias', 'categorias.id = produtos.categorias_id');
        $this->_excluir_pedidos_cancelados();
        if($dtreferencia == 'pgto'):
            $this->db->where('COALESCE(contas_receber.data_liquidacao, contas_receber.data_pago) BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        else:
            $this->db->where('contas_receber.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        endif;
        $this->db->order_by('categorias.nome, clientes.nome');
        return $this->db->get()->result();
    }

    function get_demonstrativo_por_plano_conta($inicio, $fim, $referencia){
        $this->db->select('plano_contas.cod, plano_contas.descricao as plano_conta, SUM(contas_pagar.valor) as total, COUNT(*) as qtd');
        $this->db->from('contas_pagar');
        $this->db->join('plano_contas', 'plano_contas.id = contas_pagar.plano_contas_id');
        if($referencia == 'emissao'):
            $this->db->where('contas_pagar.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        else:
            $this->db->where('contas_pagar.data_pago BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
            $this->db->where('contas_pagar.status', 1);
        endif;
        $this->db->group_by('plano_contas.id, plano_contas.cod, plano_contas.descricao');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result();
    }

    function get_demonstrativo_detalhe_plano_conta($inicio, $fim, $referencia){
        $this->db->select('plano_contas.id as plano_id, plano_contas.descricao as plano_conta, contas_pagar.id, contas_pagar.descricao, contas_pagar.data_vencimento, contas_pagar.valor, contas_pagar.status, contas_pagar.data_pago, fornecedores.nome as fornecedor');
        $this->db->from('contas_pagar');
        $this->db->join('plano_contas', 'plano_contas.id = contas_pagar.plano_contas_id');
        $this->db->join('fornecedores', 'fornecedores.id = contas_pagar.fornecedores_id', 'left');
        if($referencia == 'emissao'):
            $this->db->where('contas_pagar.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        else:
            $this->db->where('contas_pagar.data_pago BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
            $this->db->where('contas_pagar.status', 1);
        endif;
        $this->db->order_by('plano_contas.descricao, contas_pagar.data_vencimento');
        return $this->db->get()->result();
    }

    private function _apply_filtros_receitas($inicio, $fim, $origem, $vendedor, $dtreferencia, $situacaopgto, $entregador, $formapgto){
        $this->_excluir_pedidos_cancelados();

        if($dtreferencia == 'pgto'):
            $this->db->where('COALESCE(contas_receber.data_liquidacao, contas_receber.data_pago) BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        elseif($dtreferencia == 'emissao'): 
            $this->db->where('contas_receber.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        endif;

        if(!in_array("all", $situacaopgto)){
            if(in_array(1, $situacaopgto)):
                $this->db->where('contas_receber.status', 1);
            endif;
            if(in_array(0, $situacaopgto)):
                $this->db->where('contas_receber.status', 0);
            endif;
        }

        if(!empty($formapgto)){
            if(is_array($formapgto)){
                $this->db->where_in('contas_receber.forma_pgto', $formapgto);
            } else {
                $this->db->where('contas_receber.forma_pgto', $formapgto);
            }
        }

        if($vendedor != 'all'){
            $this->db->where('pedidos.vendedores_id', $vendedor);
        }
    }

    private function _excluir_pedidos_cancelados(){
        $this->db->where_not_in('pedidos.status_pedido', array(4, 5));
    }

    private function _apply_filtros_despesas($inicio, $fim, $plano_conta, $referencia, $formapgto, $situacaopgto){
        if($referencia =='emissao'):
            $this->db->where('contas_pagar.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        else:
            $this->db->where('contas_pagar.data_pago BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
            $this->db->where('contas_pagar.status', 1);
        endif;

        if(!in_array("all", $situacaopgto)){
            if(in_array(1, $situacaopgto)):
                $this->db->where('contas_pagar.status', 1);
            endif;
            if(in_array(0, $situacaopgto)):
                $this->db->where('contas_pagar.status', 0);
            endif;
        }

        if($plano_conta != 'all'){
            $this->db->where('contas_pagar.plano_contas_id', $plano_conta);
        }

        if(!empty($formapgto)){
            if(is_array($formapgto)){
                $this->db->where_in('contas_pagar.forma_pgto', $formapgto);
            } else {
                $this->db->where('contas_pagar.forma_pgto', $formapgto);
            }
        }
    }

    /* RELATÓRIO DE VENDAS */

    function get_vendas_totais($inicio, $fim){
        $totais = new stdClass();
        $totais->total_vendido = 0;
        $totais->total_itens = 0;
        $totais->total_pedidos = 0;

        // Relatorio de vendas usa a data em que o pedido foi realizado. A data
        // de pagamento pertence ao fluxo de caixa e pode ser D+1 no cartao.
        $this->db->select('COUNT(pedidos.id) as total_pedidos, SUM(pedidos.valor_total) as total_vendido');
        $this->db->from('pedidos');
        $this->_excluir_pedidos_cancelados();
        $this->db->where('pedidos.data_pedido BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:59'), '', false);
        $row = $this->db->get()->row();
        if($row){
            $totais->total_pedidos = $row->total_pedidos;
            $totais->total_vendido = $row->total_vendido;
        }

        // Soma os itens sem passar por contas_receber, evitando multiplicacao
        // quando um pedido possui pagamento dividido em duas ou mais formas.
        $this->db->select('SUM(pedido_produto.quantidade) as total_itens');
        $this->db->from('pedidos');
        $this->db->join('pedido_produto', 'pedido_produto.pedidos_id = pedidos.id');
        $this->_excluir_pedidos_cancelados();
        $this->db->where('pedidos.data_pedido BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:59'), '', false);
        $row2 = $this->db->get()->row();
        if($row2) $totais->total_itens = $row2->total_itens;

        return $totais;
    }

    function get_vendas_por_produto($inicio, $fim){
        $this->db->select('produtos.id, produtos.titulo as produto, categorias.nome as categoria, produtos.valor as preco_venda, produtos.valor_custo as preco_custo, SUM(pedido_produto.quantidade) as qtd, SUM(pedido_produto.quantidade * produtos.valor) as total, SUM(pedido_produto.quantidade * COALESCE(produtos.valor_custo, 0)) as total_custo');
        $this->db->from('pedidos');
        $this->db->join('pedido_produto', 'pedido_produto.pedidos_id = pedidos.id');
        $this->db->join('produtos', 'produtos.id = pedido_produto.produtos_id');
        $this->db->join('categorias', 'categorias.id = produtos.categorias_id', 'left');
        $this->_excluir_pedidos_cancelados();
        $this->db->where('pedidos.data_pedido BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:59'), '', false);
        $this->db->group_by('produtos.id, produtos.titulo, categorias.nome');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result();
    }

    function get_vendas_detalhe_produto($inicio, $fim){
        $this->db->select('produtos.titulo as produto, pedidos.id as pedido_id, clientes.nome as cliente, pedido_produto.quantidade, produtos.valor, (pedido_produto.quantidade * produtos.valor) as subtotal, pedidos.data_pedido as data_vencimento');
        $this->db->from('pedidos');
        $this->db->join('clientes', 'clientes.id = pedidos.clientes_id', 'left');
        $this->db->join('pedido_produto', 'pedido_produto.pedidos_id = pedidos.id');
        $this->db->join('produtos', 'produtos.id = pedido_produto.produtos_id');
        $this->_excluir_pedidos_cancelados();
        $this->db->where('pedidos.data_pedido BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:59'), '', false);
        $this->db->order_by('produtos.titulo, pedidos.id');
        return $this->db->get()->result();
    }

    function get_vendas_descontos($inicio, $fim){
        $totais = new stdClass();
        $totais->total_descontos = 0;
        $totais->qtd_pedidos = 0;

        $this->db->select('pedidos.id, pedidos.tipo_desconto, pedidos.valor_desconto, pedidos.valor, pedidos.valor_frete');
        $this->db->from('pedidos');
        $this->_excluir_pedidos_cancelados();
        $this->db->where('pedidos.data_pedido BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:59'), '', false);
        $this->db->where('pedidos.valor_desconto >', 0);
        $this->db->group_by('pedidos.id, pedidos.tipo_desconto, pedidos.valor_desconto, pedidos.valor, pedidos.valor_frete');
        $pedidos = $this->db->get()->result();

        foreach($pedidos as $pedido){
            $base_desconto = (float) $pedido->valor + (float) $pedido->valor_frete;
            if($pedido->tipo_desconto == 'porcentagem'){
                $desconto = $base_desconto > 0 ? ($base_desconto * (float) $pedido->valor_desconto / 100) : 0;
            }else{
                $desconto = (float) $pedido->valor_desconto;
            }
            if($desconto > 0){
                $totais->total_descontos += $desconto;
                $totais->qtd_pedidos++;
            }
        }

        return $totais;
    }

    function get_vendas_por_vendedor($inicio, $fim){
        $this->db->select('COALESCE(vendedores.descricao, "Sem vendedor") as vendedor, COUNT(pedidos.id) as total_pedidos, SUM(pedidos.valor_total) as total', false);
        $this->db->from('pedidos');
        $this->db->join('vendedores', 'vendedores.id = pedidos.vendedores_id', 'left');
        $this->_excluir_pedidos_cancelados();
        $this->db->where('pedidos.data_pedido BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:59'), '', false);
        $this->db->group_by('pedidos.vendedores_id, vendedores.descricao');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result();
    }

    function get_vendas_por_forma_pgto($inicio, $fim){
        $this->db->select('contas_receber.forma_pgto, COUNT(contas_receber.id) as total_parcelas, COUNT(DISTINCT pedidos.id) as total_pedidos, SUM(contas_receber.valor) as total');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id = contas_receber.pedidos_id');
        $this->_excluir_pedidos_cancelados();
        $this->db->where('pedidos.data_pedido BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:59'), '', false);
        $this->db->group_by('contas_receber.forma_pgto');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result();
    }

    /* RELATÓRIO DE PENDÊNCIAS */

    function get_pendencias($inicio, $fim){
        $this->db->select("contas_receber.*, pedidos.id as pedido_cod, pedidos.data_pedido as data_pedido, pedidos.status_pedido, clientes.nome as cliente, clientes.telefone, vendedores.descricao as vendedor, CASE WHEN pedidos.status_pedido IN (4, 5) THEN 1 ELSE 0 END as cancelada_relatorio", false);
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id = contas_receber.pedidos_id');
        $this->db->join('clientes', 'clientes.id = pedidos.clientes_id', 'left');
        $this->db->join('vendedores', 'vendedores.id = pedidos.vendedores_id', 'left');
        $this->db->where('contas_receber.status', 0);
        $this->db->group_start();
        $this->db->where_not_in('pedidos.status_pedido', array(4, 5));
        $this->db->or_group_start();
        $this->db->where_in('pedidos.status_pedido', array(4, 5));
        $this->db->group_end();
        $this->db->group_end();
        if($inicio && $fim){
            $this->db->where('contas_receber.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        }
        $this->db->order_by('cancelada_relatorio', 'ASC');
        $this->db->order_by('contas_receber.data_vencimento', 'ASC');
        return $this->db->get()->result();
    }

    function get_pendencias_totais($inicio, $fim){
        $totais = new stdClass();
        $totais->total = 0;
        $totais->qtd = 0;

        $this->db->select('SUM(contas_receber.valor) as total, COUNT(*) as qtd');
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id = contas_receber.pedidos_id');
        $this->db->where('contas_receber.status', 0);
        $this->db->where_not_in('pedidos.status_pedido', array(4, 5));
        if($inicio && $fim){
            $this->db->where('contas_receber.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        }
        $row = $this->db->get()->row();
        if($row){
            $totais->total = $row->total;
            $totais->qtd = $row->qtd;
        }
        return $totais;
    }

    function get_pendencias_por_vendedor($inicio, $fim){
        $this->db->select('COALESCE(vendedores.descricao, "Sem vendedor") as vendedor, COUNT(DISTINCT pedidos.id) as total_pedidos, COUNT(contas_receber.id) as total_parcelas, SUM(contas_receber.valor) as total', false);
        $this->db->from('contas_receber');
        $this->db->join('pedidos', 'pedidos.id = contas_receber.pedidos_id');
        $this->db->join('vendedores', 'vendedores.id = pedidos.vendedores_id', 'left');
        $this->db->where('contas_receber.status', 0);
        $this->db->where_not_in('pedidos.status_pedido', array(4, 5));
        if($inicio && $fim){
            $this->db->where('contas_receber.data_vencimento BETWEEN ' . $this->db->escape($inicio.' 00:00:00') . ' AND ' . $this->db->escape($fim.' 23:59:00'), '', false);
        }
        $this->db->group_by('pedidos.vendedores_id, vendedores.descricao');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result();
    }

    function get_itens_pedidos_lote($pedidos_ids){
        if(empty($pedidos_ids)){
            return array();
        }
        $this->db->select('pedido_produto.*, produtos.titulo as produto_nome');
        $this->db->from('pedido_produto');
        $this->db->join('produtos', 'produtos.id = pedido_produto.produtos_id', 'left');
        $this->db->where_in('pedido_produto.pedidos_id', $pedidos_ids);
        $query = $this->db->get();
        $itens = array();
        if($query->num_rows() > 0){
            foreach($query->result() as $item){
                $pedidos_id = $item->pedidos_id;
                if(!isset($itens[$pedidos_id])){
                    $itens[$pedidos_id] = array();
                }
                $itens[$pedidos_id][] = $item;
            }
        }
        return $itens;
    }

}
