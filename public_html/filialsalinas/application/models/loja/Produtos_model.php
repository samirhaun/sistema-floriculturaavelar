<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produtos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function salvar_produto($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('produtos', $dados))
            {
                return $id;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert('produtos', $dados))
            {
                return $this->db->insert_id();
            }
            else
            {
                return false;
            }
        }
    }

    public function salvar_galeria($dados)
    {
        // var_dump($dados);
        // exit;
        if($this->db->insert_batch('produtos_fotos', $dados))
            {
                return true;
            }
            else
            {
                return false;
            }
    }

    public function salvar_tamanhos($dados, $id=NULL)
    {
        // var_dump($dados);
        // exit;

        if ($id) {
          $this->db->select('produto_tamanhos.*');
          $this->db->from('produto_tamanhos');
          $this->db->where('produtos_id', $id);
          $query = $this->db->get();
          if($query->num_rows()>0){

            foreach ($query->row() as $key => $value) {
              $this->db->where('produtos_id', $id);
              $this->db->delete('produto_tamanhos');
            }
          }
          if (count($dados)>0) {

          $this->db->insert_batch('produto_tamanhos', $dados);
        }

        }else {
          if($this->db->insert_batch('produto_tamanhos', $dados))
              {
                  return true;
              }
              else
              {
                  return false;
              }
        }


    }

    public function salvar_categorias($dados, $id=NULL)
    {
        // var_dump($dados);
        // exit;

        if ($id) {
          $this->db->select('categorias_produtos.*');
          $this->db->from('categorias_produtos');
          $this->db->where('produtos_id', $id);
          $query = $this->db->get();
          if($query->num_rows()>0){

            foreach ($query->row() as $key => $value) {
              $this->db->where('produtos_id', $id);
              $this->db->delete('categorias_produtos');
            }
          }
          if (count($dados)>0) {

          $this->db->insert_batch('categorias_produtos', $dados);
        }

        }else {
          if($this->db->insert_batch('categorias_produtos', $dados))
              {
                  return true;
              }
              else
              {
                  return false;
              }
        }


    }

    public function get_produtos($apenas_pedidos = null)
    {
        $this->db->select('produtos.*');
        $this->db->from('produtos');
        $this->db->order_by('titulo asc');

        if(!empty($apenas_pedidos)):
            $this->db->where('exibir_em_pedidos', 1);
        endif;

        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_produtos_vinculados($idproduto)
    {
        $this->db->select('produtos_vinculados.*');
        $this->db->from('produtos_vinculados');
        $this->db->where('produtos_id', $idproduto);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_tamanhos()
    {
        $this->db->select('tamanhos.*');
        $this->db->from('tamanhos');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_tamanhos_produto($id){
      $this->db->select('produto_tamanhos.tamanhos_id');
      $this->db->from('produto_tamanhos');
      $this->db->where('produtos_id', $id);
      $query = $this->db->get();
      if($query->num_rows() > 0){
          return $query->result();
      }else{
          return FALSE;
      }
    }

    public function get_categorias_produto($id){
      $this->db->select('categorias_produtos.categorias_id');
      $this->db->from('categorias_produtos');
      $this->db->where('produtos_id', $id);
      $query = $this->db->get();
      if($query->num_rows() > 0){
          return $query->result();
      }else{
          return FALSE;
      }
    }

    public function get_categorias()
    {
        $this->db->select('categorias.*');
        $this->db->from('categorias');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

     public function get_marcas()
    {
        $this->db->select('marcas.*');
        $this->db->from('marcas');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_produto($id=null)
    {
        if($id){
            $this->db->select('produtos.*');
            $this->db->from('produtos');
            $this->db->where('produtos.id', $id);
            $query = $this->db->get();
            if($query->num_rows() > 0){
                $data = $query->row();
                $this->db->select('produtos_fotos.*');
                $this->db->from('produtos_fotos');
                $this->db->where('produtos_fotos.produtos_id', $id);
                $query_galeria = $this->db->get();
                if($query_galeria->num_rows() > 0){
                    $data->galeria = $query_galeria->result();
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    public function get_galeria_produto($id_produto='')
    {
        $this->db->select('galeria_produtos.*');
        $this->db->from('galeria_produtos');
        $this->db->where('galeria_produtos.produtos_id', $id_produto);
        $query_galeria = $this->db->get();
        if($query_galeria->num_rows() > 0){
            return $query_galeria->result();
        }else{
            return FALSE;
        }
    }

    public function excluir_imagem($imagem)
    {
        $this->db->trans_start();
            foreach ($imagem as $id) {
                $this->db->where('id', $id);
                $this->db->delete('produtos_fotos');
            }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return FALSE;
        }else{
            return TRUE;
        }
    }

    public function excluir_galeria($id_produto='')
    {
        $this->db->where('produtos_id', $id_produto);
        if($this->db->delete('galeria_produtos')){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function excluir_produto($id){
        $this->db->where('id', $id);
        if($this->db->delete('produtos')){
            return TRUE;
        }else{
            return FALSE;
        }
    }


   


    function desativar_produto($id){

        $this->db->select('produtos.*');
        $this->db->from('produtos');
        $this->db->where('id', $id);

        $dados = array();

       if($this->db->get()->row()->ativo == 0){

            $dados['ativo'] = 1;

       }else{

        $dados['ativo'] = 0;

       }
      $this->db->where('id', $id);
      if($this->db->update('produtos', $dados)){
        return true;
      }else{
        return false;
      }


    }

    function destaque_produto($id){

        $this->db->select('produtos.*');
        $this->db->from('produtos');
        $this->db->where('id', $id);

        $dados = array();

       if($this->db->get()->row()->destaque == 0){

            $dados['destaque'] = 1;

       }else{

        $dados['destaque'] = 0;

       }
      $this->db->where('id', $id);
      if($this->db->update('produtos', $dados)){
        return true;
      }else{
        return false;
      }


    }

    /* VENDAS */

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

    public function get_eventos()
    {
        $this->db->select('eventos.*');
        $this->db->from('eventos');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }


    public function salvar_produtos_vinculados($produtos_vincular, $idproduto = null){

                //vamos apagar primeiro
                $this->db->where('produtos_id', $idproduto);
                if($this->db->delete('produtos_vinculados')):

                    //agora inserios caso tenha
                    if(!empty($produtos_vincular)):
                        return $this->db->insert_batch('produtos_vinculados', $produtos_vincular);
                    endif;


                endif;

                return true;

   
    }


    /* ENTRADA DE PRODUTOS */
    function get_produtos_entradas($tipo)
    {

        $this->db->select('produtos_entradas.*, usuarios.nome as usuario');
        $this->db->from('produtos_entradas');
        $this->db->where('tipo', $tipo);
        $this->db->join('usuarios', 'usuarios.id = produtos_entradas.usuarios_id');
        $query = $this->db->get();
        if($query->num_rows() > 0){

            $dados = $query->result();

            for ($i=0; $i < sizeof($dados); $i++) { 
                $this->db->select('produtos_entrada_produtos.*, produtos.titulo as produto');
                $this->db->from('produtos_entrada_produtos');
                $this->db->join('produtos', 'produtos.id = produtos_entrada_produtos.produtos_id');
                $this->db->where('produtos_entrada_produtos.produtos_entradas_id', $dados[$i]->id);
                $dados[$i]->produtos_entrada = $this->db->get()->result();
            }

            return $dados;

        }else{
            return FALSE;
        }

    }

    public function salvar_produto_entrada($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('produtos_entradas', $dados))
            {
                return $id;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert('produtos_entradas', $dados))
            {
                return $this->db->insert_id();
            }
            else
            {
                return false;
            }
        }
    }

    public function salvar_produtos_vinculados_entrada($produtos_vincular, $idproduto_entrada = null){

            //vamos apagar primeiro
            $this->db->where('produtos_entradas_id', $idproduto_entrada);
            if($this->db->delete('produtos_entrada_produtos')):

                //agora inserios caso tenha
                if(!empty($produtos_vincular)):
                    return $this->db->insert_batch('produtos_entrada_produtos', $produtos_vincular);
                endif;


            endif;

            return true;


    }

    public function get_produtos_vinculados_entrada($idproduto_entrada)
    {
        $this->db->select('produtos_entrada_produtos.*');
        $this->db->from('produtos_entrada_produtos');
        $this->db->where('produtos_entradas_id', $idproduto_entrada);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_produto_entrada($id=null)
    {
        if($id){
            $this->db->select('*');
            $this->db->from('produtos_entradas');
            $this->db->where('id', $id);
            return $this->db->get()->row();
            
        }
    }


    function excluir_produto_entrada($id){

        $this->db->where('produtos_entradas_id', $id);
        $this->db->delete('produtos_entrada_produtos');

        $this->db->where('id', $id);
        if($this->db->delete('produtos_entradas')){
            return TRUE;
        }else{
            return FALSE;
        }
    }






    /*END */


    /* ESTOQUE */

    function verifica_estoque_produtos($idproduto){

        //PEGANDO O PRODUTO DE ORIGEM

        // var_dump($idproduto);
        // exit;

        $this->db->select('*');
        $this->db->from('produtos');
        $this->db->where('id', $idproduto);
        $produto = $this->db->get()->row();

        //VAMOS PEGAR ENTRADAS E SAÍDAS DESTE PRODUTO PARA OLHAR O SALDO
        $this->db->select('produtos.titulo, produtos_entradas.tipo, produtos_entrada_produtos.qtd');
        $this->db->from('produtos_entrada_produtos');
        $this->db->join('produtos_entradas', 'produtos_entradas.id = produtos_entrada_produtos.produtos_entradas_id');
        $this->db->join('produtos', 'produtos.id = produtos_entrada_produtos.produtos_id');
        $this->db->where('produtos_entrada_produtos.produtos_id', $idproduto);
        $produto->movimentacao = $this->db->get()->result();


        //VAMOS OLHAR SE POSSUI VINCULADOS
        $this->db->select('produtos_vinculados.*,produtos.titulo');
        $this->db->from('produtos_vinculados');
        $this->db->join('produtos', 'produtos.id = produtos_vinculados.produto_vinculado_id');
        $this->db->where('produtos_vinculados.produtos_id', $idproduto);
        $produto->vinculados = $this->db->get()->result();

        if(!empty($produto->vinculados)):

            //PROCURAR A MOVIMENTAÇÃO DOS VINCULADOS
            for ($i=0; $i < sizeof($produto->vinculados); $i++) { 

                $this->db->select('produtos.titulo, produtos_entradas.tipo, produtos_entrada_produtos.qtd');
                $this->db->from('produtos_entrada_produtos');
                $this->db->join('produtos_entradas', 'produtos_entradas.id = produtos_entrada_produtos.produtos_entradas_id');
                $this->db->join('produtos', 'produtos.id = produtos_entrada_produtos.produtos_id');
                $this->db->where('produtos_entrada_produtos.produtos_id', $produto->vinculados[$i]->produto_vinculado_id);
                $produto->vinculados[$i]->movimentacao = $this->db->get()->result();
                
            }

        endif;

        // echo '<pre>';
        // print_r($produto);
        // exit;


        return $produto;


           

    }


    function verifica_estoque__minimo_produtos()
    {

        $this->db->select('*');
        $this->db->from('produtos');
        $this->db->where('alerta_estoque_minimo >', 0);
        $produtos = $this->db->get()->result();

        if(!empty($produtos)):

            for ($i=0; $i < sizeof(($produtos)); $i++) { 

                //VAMOS PEGAR ENTRADAS E SAÍDAS DESTE PRODUTO PARA OLHAR O SALDO
                $this->db->select('produtos.titulo, produtos_entradas.tipo, produtos_entrada_produtos.qtd');
                $this->db->from('produtos_entrada_produtos');
                $this->db->join('produtos_entradas', 'produtos_entradas.id = produtos_entrada_produtos.produtos_entradas_id');
                $this->db->join('produtos', 'produtos.id = produtos_entrada_produtos.produtos_id');
                $this->db->where('produtos_entrada_produtos.produtos_id', $produtos[$i]->id);
                $produtos[$i]->movimentacao = $this->db->get()->result();  
                

            }

        endif;

        // echo '<pre>';
        // print_r($produtos);
        // exit;

        return $produtos;


    }


    function posicao_estoque()
    {

        $this->db->select('*');
        $this->db->from('produtos');
        $this->db->order_by('titulo asc');
        $produtos = $this->db->get()->result();

        if(!empty($produtos)):

            for ($i=0; $i < sizeof(($produtos)); $i++) { 

                //VAMOS PEGAR ENTRADAS E SAÍDAS DESTE PRODUTO PARA OLHAR O SALDO
                $this->db->select('produtos.titulo, produtos_entradas.tipo, produtos_entrada_produtos.qtd');
                $this->db->from('produtos_entrada_produtos');
                $this->db->join('produtos_entradas', 'produtos_entradas.id = produtos_entrada_produtos.produtos_entradas_id');
                $this->db->join('produtos', 'produtos.id = produtos_entrada_produtos.produtos_id');
                $this->db->where('produtos_entrada_produtos.produtos_id', $produtos[$i]->id);
                $produtos[$i]->movimentacao = $this->db->get()->result();  
                

            }

        endif;

        // echo '<pre>';
        // print_r($produtos);
        // exit;

        return $produtos;


    }



}
