<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja_produtos extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja/produtos_model');
        $this->load->helper('form');
        $this->load->helper('text');

        $this->set_menu_active(
                            array(
                                'menu' => 'Loja',
                                'submenu' => 'Loja-produtos'
                                )
                            );
    }

    public function index()
    {
        $this->lista();
    }


    public function lista()
    {
        $data['produtos'] =  $this->produtos_model->get_produtos();

        //  echo '<pre>';
        // print_r( $data['produtos']);
        // exit;

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }


        $this->montaTela('loja/lista', $data);
    }

    function novo_produto(){
       $data['categorias'] = $this->produtos_model->get_categorias();
       $data['marcas'] = $this->produtos_model->get_marcas();

       $data['produtos'] =  $this->produtos_model->get_produtos();


            $data['produto'] = $this->produtos_model->get_produto($this->input->get('id'));
            $data['tamanhos'] = $this->produtos_model->get_tamanhos();
                                  // TAMANHOS
            $tam_prod = array();
            if ($tams = $this->produtos_model->get_tamanhos_produto($this->input->get('id'))) {
              foreach ($tams as $key => $value) {
                $tam_prod[] = $value->tamanhos_id;
              }
            }
            $data['tamanhos_produto'] = $tam_prod;
                            // Categorias
            $cat_prod = array();
            if ($cat = $this->produtos_model->get_categorias_produto($this->input->get('id'))) {
              foreach ($cat as $key => $value) {
                $cat_prod[] = $value->categorias_id;
              }
            }
            $data['categorias_produto'] = $cat_prod;

        $this->montaTela('loja/formulario', $data);
    }

    function salvar_produto(){

        // echo '<pre>';
        // print_r($_POST);
        // exit;

        $this->load->helper(array('text','url'));


            if ($this->input->post('tamanhos')) {
                $tamanho = 1;
            }else{
                $tamanho = 0;
            }


        if($this->input->post()){
            $upload = $this->upload_imagem();
            $cats = $this->input->post('categorias');
            $dados = array(
                'titulo' => $this->input->post('nome'),
                'nome_tag' => url_title(convert_accented_characters($this->input->post('nome')), '-', true),
                'categorias_id' => $cats[0],
                'valor' => $this->input->post('valor'),
                'valor_antigo' => $this->input->post('valor_antigo'),
                'tamanhos' => $tamanho,
                'valor_frete' => $this->input->post('valor_frete'),
                'descricao' => $this->input->post('descricao'),
                'detalhes' => $this->input->post('detalhes'),
                'estoque' => $this->input->post('estoque'),
                'comprimento_embalagem' => $this->input->post('comprimento_embalagem'),
                'altura_embalagem' => $this->input->post('altura_embalagem'),
                'largura_embalagem' => $this->input->post('largura_embalagem'),
                'peso' => $this->input->post('peso'),
                'valor_forma_pagamento' => $this->input->post('valor_forma_pagamento'),
                'marcas_id' => $this->input->post('marcas_id'),
                'novo' => $this->input->post('novo'),
                'porcentagem_desconto' => $this->input->post('porcentagem_desconto'),
                'alerta_estoque_minimo' => $this->input->post('alerta_estoque_minimo'),
                'disponivel_site' => $this->input->post('disponivel_site'),
                'imagem' => $upload['file_name']

            );

            $tamanhos = array();
            $categorias = array();
            $id=NULL;
            //editar agenda
            if($this->input->post('id')){
                $id = $this->input->post('id');
            }

            if($id_produto = $this->produtos_model->salvar_produto($dados, $id))
            {   
               
                 foreach ($this->input->post('categorias') as $key => $value) {
                   array_push($categorias, array('categorias_id' => $value, 'produtos_id' => $id_produto));
                 }

                $this->produtos_model->salvar_categorias($categorias, $id);
                if (isset($_FILES['galeria'])) {
                $db_img_produtos = array();
                $files = $_FILES;
                $cont = count($_FILES['galeria']['name']);
                for ($i=0; $i < $cont; $i++) {

                    $this->load->library('upload', array(
                            'upload_path' => FCPATH.'../assets/images/product',
                            'allowed_types' => 'jpg|png|gif|jpeg',
                            'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
                            'max_size' => 8 * 1024,
                            'remove_spaces' => TRUE
                        ));

                    $_FILES['galeria']['name']= $files['galeria']['name'][$i];
                    $_FILES['galeria']['type']= $files['galeria']['type'][$i];
                    $_FILES['galeria']['tmp_name']= $files['galeria']['tmp_name'][$i];
                    $_FILES['galeria']['error']= $files['galeria']['error'][$i];
                    $_FILES['galeria']['size']= $files['galeria']['size'][$i];

                    if ($this->upload->do_upload('galeria')){
                        $file_data = $this->upload->data();
                        $this->load->library('image_lib', array(
                                'image_library' => 'gd2',
                                'source_image' => $file_data['full_path'],
                                'create_thumb' => FALSE,
                                'maintain_ratio' => TRUE,
                                //'width' => 800,
                                //'height' => 800,
                                'quality' => '100%'
                            )
                        );
                        $this->image_lib->resize();
                        array_push($db_img_produtos, array('foto' => $file_data['file_name'], 'produtos_id' => $id_produto));
                    }

                }
              }

                //´só é usado durante a edição, serve para apagar a imagem do servidor
                if($this->input->post('remove_imagem')){
                    if ($this->produtos_model->excluir_imagem($this->input->post('remove_imagem'))) {
                        foreach ($this->input->post('nome_imagem') as $imagem) {
                            $apagar = FCPATH.'../assets/images/produtos/' . $imagem;
                            @unlink($apagar);
                        }
                    }
                }



                //verifica se foi feito o upload de alguma imagem (mais usado durante a edição de produtos)
                if( isset($db_img_produtos) && count($db_img_produtos) > 0){
                    if($this->produtos_model->salvar_galeria($db_img_produtos)){
                        $_GET['type'] = 'success';
                        if($id){
                            $_GET['title'] = 'Atualização';
                            $_GET['message'] = 'Produto e galeria de imagem atualizados com sucesso!';
                        }else{
                            $_GET['title'] = 'Cadastro';
                            $_GET['message'] = 'Produto e galeria de imagem cadastrados com sucesso!';
                        }
                    }else{
                        $_GET['type'] = 'error';
                        if($id){
                            $_GET['title'] = 'Atualização';
                            $_GET['message'] = 'Ocorreu um erro ao atualizar a galeria de imagem do produto!';
                        }else{
                            $_GET['title'] = 'Cadastro';
                            $_GET['message'] = 'Ocorreu um erro ao cadastrar a galeria de imagem do produto!';
                        }
                    }
                }else{
                    $_GET['type'] = 'success';
                    if($id){
                        $_GET['title'] = 'Atualização';
                        $_GET['message'] = 'Produto atualizado com sucesso!';
                    }else{
                        $_GET['title'] = 'Cadastro';
                        $_GET['message'] = 'Produto cadastrado com sucesso!';
                    }
                }


                /*PRODUTOS VINCULADOS*/

                $produtos_count = $this->input->post('produto_collapse');

                $produtos = array();

                //VERIICANDO SE É CADASTRO OU EDIÇÃO
                if(empty($id)):
                    $idprod = $id_produto;
                else:
                    $idprod = $id;
                endif;
    
                if(count($produtos_count) > 0):

                    $dados_produto = array();

                    foreach ($produtos_count as $key => $value) {
                        if(!empty($this->input->post('produto_collapse')[$key])):

                            $dados_produto['qtd'] = $this->input->post('quantidade_collapse')[$key];
                            $dados_produto['produto_vinculado_id'] = $this->input->post('produto_collapse')[$key];
                            $dados_produto['produtos_id'] = $idprod;
                            array_push($produtos, $dados_produto);

                        endif;
                    }

               $this->produtos_model->salvar_produtos_vinculados($produtos, $idprod);

                endif;


                


                /*FIM PRODUTOS VINCULADOS */



            }

            else
            {
                $_GET['type'] = 'error';
                if($id){
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Ocorreu um erro ao atualizar o produto!';
                }else{
                    $_GET['title'] = 'Cadastro';
                    $_GET['message'] = 'Ocorreu um erro ao cadastrar o produto!';
                }
            }
            $this->lista();
            $url = base_url(array('loja/produtos'));
            $this->output->append_output('<script>window.history.replaceState("", "Line Hair", "'. $url .'")</script>');
        }
    }

    public function editar_produto()
    {
        if($this->input->get('id')){
            $dados['categorias'] = $this->produtos_model->get_categorias();
            $dados['marcas'] = $this->produtos_model->get_marcas();
            $dados['produto'] = $this->produtos_model->get_produto($this->input->get('id'));
            $dados['tamanhos'] = $this->produtos_model->get_tamanhos();


            $dados['produtos'] =  $this->produtos_model->get_produtos();
            $dados['produtos_vinculados'] =  $this->produtos_model->get_produtos_vinculados($this->input->get('id'));


            // echo '<pre>';
            // print_r($dados['produtos_vinculados']);
            // exit;
                                  // TAMANHOS
            $tam_prod = array();
            if ($tams = $this->produtos_model->get_tamanhos_produto($this->input->get('id'))) {
              foreach ($tams as $key => $value) {
                $tam_prod[] = $value->tamanhos_id;
              }
            }
            $dados['tamanhos_produto'] = $tam_prod;
                            // Categorias
            $cat_prod = array();
            if ($cat = $this->produtos_model->get_categorias_produto($this->input->get('id'))) {
              foreach ($cat as $key => $value) {
                $cat_prod[] = $value->categorias_id;
              }
            }
            $dados['categorias_produto'] = $cat_prod;

            $this->montaTela('loja/formulario', $dados);
        }
    }

    function excluir_produto(){


        if($this->produtos_model->excluir_produto($this->input->post('id'))){
                        $response['type'] = 'success';
                        $response['title'] = 'Exclusão';
                        $response['message'] = 'Produto excluído com sucesso!';
                        echo json_encode($response);
                    }else{
                        $response['type'] = 'error';
                        $response['title'] = 'Exclusão';
                        $response['message'] = 'Ocorreu um erro ao excluír o produto!';
                        echo json_encode($response);
                    }
                    exit;
        if ($this->input->post('id')) {

            if($galeria = $this->produtos_model->get_galeria_produto($this->input->post('id'))){
                if($this->produtos_model->excluir_galeria($this->input->post('id'))){
                    if($this->produtos_model->excluir_produto($this->input->post('id'))){
                        foreach ($galeria as $img) {
                            $apagar = FCPATH.'../assets/images/produtos/' . $img->imagem;
                            @unlink($apagar);
                        }
                        $response['type'] = 'success';
                        $response['title'] = 'Exclusão';
                        $response['message'] = 'Produto excluído com sucesso!';
                        echo json_encode($response);
                    }else{
                        $response['type'] = 'error';
                        $response['title'] = 'Exclusão';
                        $response['message'] = 'Ocorreu um erro ao excluír o produto!';
                        echo json_encode($response);
                    }
                }
            }else{
                if($this->produtos_model->excluir_produto($this->input->post('id'))){
                        $response['type'] = 'success';
                        $response['title'] = 'Exclusão';
                        $response['message'] = 'Produto excluído com sucesso!';
                        echo json_encode($response);
                    }else{
                        $response['type'] = 'error';
                        $response['title'] = 'Exclusão';
                        $response['message'] = 'Ocorreu um erro ao excluír o produto!';
                        echo json_encode($response);
                    }
            }
        }
        return;
    }


    public function desativar_produto(){

        if($this->input->get('id')){

            if($this->produtos_model->desativar_produto($this->input->get('id'))){
                    $_GET['type'] = 'success';
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Produto atualizado com sucesso!';
                    $this->lista();
                    $url = base_url(array('loja', 'produtos'));
                    $this->output->append_output('<script>window.history.replaceState("", "Meritus", "'. $url .'")</script>');

            }else{

                    $_GET['type'] = 'error';
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'erro ao atualizar o produto!';
                    $this->lista();
                    $url = base_url(array('loja', 'produtos'));
                    $this->output->append_output('<script>window.history.replaceState("", "Meritus", "'. $url .'")</script>');
            }

        }


    }



    public function destacar_produto(){

        if($this->input->get('id')){

            if($this->produtos_model->destaque_produto($this->input->get('id'))){
                    $_GET['type'] = 'success';
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'Produto atualizado com sucesso!';
                    $this->lista();
                    $url = base_url(array('loja', 'produtos'));
                    $this->output->append_output('<script>window.history.replaceState("", "Meritus", "'. $url .'")</script>');

            }else{

                    $_GET['type'] = 'error';
                    $_GET['title'] = 'Atualização';
                    $_GET['message'] = 'erro ao atualizar o produto!';
                    $this->lista();
                    $url = base_url(array('loja', 'produtos'));
                    $this->output->append_output('<script>window.history.replaceState("", "Meritus", "'. $url .'")</script>');
            }

        }


    }



    public function upload_imagem($value='')
    	{



    		if(!empty($_FILES['imagem']['name'])){
    			$this->load->library('upload', [
    					'upload_path' => FCPATH.'../assets/images/produtos',
    					'allowed_types' => 'jpg|png|gif|jpeg',
    					'file_name' => hash('md5', uniqid(rand(0, 500)) . time() . rand(0, 500)),
    					'max_size' => 8 * 1024,
    					'remove_spaces' => TRUE
    				]
    			);
    			if ($this->upload->do_upload('imagem')){
    				$file_data = $this->upload->data();
    				$this->load->library('image_lib', [
    						'image_library' => 'gd2',
    						'source_image' => $file_data['full_path'],
    						'create_thumb' => FALSE,
    						'maintain_ratio' => TRUE,
                // 'width' => 800,
                // 'height' => 800,
                'quality' => '100%'
    					]
    				);
            $this->image_lib->resize();
    			}

    			if($this->input->post('imagem_produto')){
    				if ($_FILES['imagem']['name'] != $this->input->post('imagem_produto')) {
    					$apagar = FCPATH.'../assets/images/produtos' . $this->input->post('imagem_produto');
    					@unlink($apagar);
    				}
    			}
    		}else{

    			if($this->input->post('imagem_produto')){
    				$file_data['file_name'] = $this->input->post('imagem_produto');
    			}else{
    				$file_data['file_name'] = '';
    			}
    		}

    		return $file_data;
        }
        


        /* VENDA AVULSA */


        function nova_venda(){
            $data['clientes'] = $this->produtos_model->get_clientes_venda();

            $data['produtos'] = $this->produtos_model->get_produtos_venda();
     
             $this->montaTela('loja/formulario', $data);
         }





         /*ENTRADA DE PRODUTOS */
         

         public function lista_entrada()
        {

            $this->set_menu_active(
                array(
                    'menu' => 'Loja',
                    'submenu' => 'Loja-produtos-entrada'
                    )
                );

            $data['produtos'] =  $this->produtos_model->get_produtos_entradas($tipo = 1);

            if($this->input->get('type')){
                $notification = new stdClass;
                $notification->type = $this->input->get('type');
                $notification->title = $this->input->get('title');
                $notification->message = $this->input->get('message');
                $data['notification'] = $notification;
            }


            $this->montaTela('loja_entrada/lista', $data);
        }

        function nova_entrada()
        {

            $this->set_menu_active(
                array(
                    'menu' => 'Loja',
                    'submenu' => 'Loja-produtos-entrada'
                    )
                );

        $data['produtos'] =  $this->produtos_model->get_produtos();


        $this->montaTela('loja_entrada/formulario', $data);

        }

        function salvar_entrada()
        {

        //     var_dump($_SESSION['usuario']->id);

        //      echo '<pre>';
        // print_r($_POST);
        // exit;

            $this->load->helper(array('text','url'));


            if ($this->input->post('tamanhos')) {
                $tamanho = 1;
            }else{
                $tamanho = 0;
            }


            if($this->input->post()){

                $dados = array(
                    'descricao' => $this->input->post('descricao'),
                    'data_hora' => inverteData($this->input->post('data'), '/'). ' '.$this->input->post('hora'),
                    'usuarios_id' => $_SESSION['usuario']->id,
                    //tipo entrada
                    'tipo' => 1

                );

                $id = NULL;

                
                if($this->input->post('id')){
                    $id = $this->input->post('id');
                }

                if($id_produto = $this->produtos_model->salvar_produto_entrada($dados, $id))
                {   
                
                    /*PRODUTOS VINCULADOS*/

                    $produtos_count = $this->input->post('produto_collapse');

                    $produtos = array();

                    //VERIICANDO SE É CADASTRO OU EDIÇÃO
                    if(empty($id)):
                        $idprod = $id_produto;
                    else:
                        $idprod = $id;
                    endif;

                    if(count($produtos_count) > 0):

                        $dados_produto = array();

                        foreach ($produtos_count as $key => $value) {
                            if(!empty($this->input->post('produto_collapse')[$key])):

                                $dados_produto['qtd'] = $this->input->post('quantidade_collapse')[$key];
                                $dados_produto['produtos_id'] = $this->input->post('produto_collapse')[$key];
                                $dados_produto['produtos_entradas_id'] = $idprod;
                                array_push($produtos, $dados_produto);

                            endif;
                        }


                    endif;


                    $this->produtos_model->salvar_produtos_vinculados_entrada($produtos, $idprod);


                    /*FIM PRODUTOS VINCULADOS */

                    $_GET['type'] = 'success';

                        if($id){
                            $_GET['title'] = 'Atualização';
                            $_GET['message'] = 'Entrada de Produto atualizada com sucesso!';
                        }else{
                            $_GET['title'] = 'Cadastro';
                            $_GET['message'] = 'Entrada de Produto cadastrada com sucesso!';
                        }



                }

                else
                {
                    $_GET['type'] = 'error';
                    if($id){
                        $_GET['title'] = 'Atualização';
                        $_GET['message'] = 'Ocorreu um erro ao atualizar a entrada!';
                    }else{
                        $_GET['title'] = 'Cadastro';
                        $_GET['message'] = 'Ocorreu um erro ao cadastrar a entrada!';
                    }
                }
                $this->lista_entrada();
                $url = base_url(array('loja/produtos-entrada'));
                $this->output->append_output('<script>window.history.replaceState("", "Line Hair", "'. $url .'")</script>');
            }

        }


        public function editar_produto_entrada()
        {

            $this->set_menu_active(
                array(
                    'menu' => 'Loja',
                    'submenu' => 'Loja-produtos-entrada'
                    )
                );

            if($this->input->get('id')){
                $dados['produto'] = $this->produtos_model->get_produto_entrada($this->input->get('id'));

                $data = explode(' ', $dados['produto']->data_hora);

                // $dados['data'] = $data[0];
                // $dados['hora'] = $data[1];

                $dados['produtos'] =  $this->produtos_model->get_produtos();
                $dados['produtos_vinculados'] =  $this->produtos_model->get_produtos_vinculados_entrada($this->input->get('id'));

        //              echo '<pre>';
        // print_r($dados);
        // exit;

                $this->montaTela('loja_entrada/formulario', $dados);
            }
        }


        function excluir_produto_entrada(){


            if($this->produtos_model->excluir_produto_entrada($this->input->post('id'))){
                            $response['type'] = 'success';
                            $response['title'] = 'Exclusão';
                            $response['message'] = 'Entrada Produto excluída com sucesso!';
                            echo json_encode($response);
                        }else{
                            $response['type'] = 'error';
                            $response['title'] = 'Exclusão';
                            $response['message'] = 'Ocorreu um erro ao excluír a entrada do produto!';
                            echo json_encode($response);
                        }
                        exit;
            return;
        }

         /*FIM ENTRADA DE PRODUTOS */


          /*SAÍDA DE PRODUTOS */
         

          public function lista_saida()
          {

            $this->set_menu_active(
                array(
                    'menu' => 'Loja',
                    'submenu' => 'Loja-produtos-saida'
                    )
                );

              $data['produtos'] =  $this->produtos_model->get_produtos_entradas($tipo = 2);

        //            echo '<pre>';
        //   print_r($data['produtos']);
        //   exit;
  
              if($this->input->get('type')){
                  $notification = new stdClass;
                  $notification->type = $this->input->get('type');
                  $notification->title = $this->input->get('title');
                  $notification->message = $this->input->get('message');
                  $data['notification'] = $notification;
              }
  
  
              $this->montaTela('loja_saida/lista', $data);
          }
  
          function nova_saida()
          {

            $this->set_menu_active(
                array(
                    'menu' => 'Loja',
                    'submenu' => 'Loja-produtos-saida'
                    )
                );
  
          $data['produtos'] =  $this->produtos_model->get_produtos();
  
  
          $this->montaTela('loja_saida/formulario', $data);
  
          }
  
          function salvar_saida()
          {
  
          //     var_dump($_SESSION['usuario']->id);
  
          //      echo '<pre>';
          // print_r($_POST);
          // exit;
  
              $this->load->helper(array('text','url'));
  
  
              if ($this->input->post('tamanhos')) {
                  $tamanho = 1;
              }else{
                  $tamanho = 0;
              }
  
  
              if($this->input->post()){
  
                  $dados = array(
                      'descricao' => $this->input->post('descricao'),
                      'data_hora' => inverteData($this->input->post('data'), '/'). ' '.$this->input->post('hora'),
                      'usuarios_id' => $_SESSION['usuario']->id,
                      //tipo saida
                      'tipo' => 2
  
                  );
  
                  $id = NULL;
  
                  
                  if($this->input->post('id')){
                      $id = $this->input->post('id');
                  }
  
                  if($id_produto = $this->produtos_model->salvar_produto_entrada($dados, $id))
                  {   
                  
                      /*PRODUTOS VINCULADOS*/
  
                      $produtos_count = $this->input->post('produto_collapse');
  
                      $produtos = array();
  
                      //VERIICANDO SE É CADASTRO OU EDIÇÃO
                      if(empty($id)):
                          $idprod = $id_produto;
                      else:
                          $idprod = $id;
                      endif;
  
                      if(count($produtos_count) > 0):
  
                          $dados_produto = array();
  
                          foreach ($produtos_count as $key => $value) {
                              if(!empty($this->input->post('produto_collapse')[$key])):
  
                                  $dados_produto['qtd'] = $this->input->post('quantidade_collapse')[$key];
                                  $dados_produto['produtos_id'] = $this->input->post('produto_collapse')[$key];
                                  $dados_produto['produtos_entradas_id'] = $idprod;
                                  array_push($produtos, $dados_produto);
  
                              endif;
                          }
  
  
                      endif;
  
  
                      $this->produtos_model->salvar_produtos_vinculados_entrada($produtos, $idprod);
  
  
                      /*FIM PRODUTOS VINCULADOS */
  
                      $_GET['type'] = 'success';
  
                          if($id){
                              $_GET['title'] = 'Atualização';
                              $_GET['message'] = 'Saída/Baixa de Produto atualizada com sucesso!';
                          }else{
                              $_GET['title'] = 'Cadastro';
                              $_GET['message'] = 'Saída/Baixa de Produto cadastrada com sucesso!';
                          }
  
  
  
                  }
  
                  else
                  {
                      $_GET['type'] = 'error';
                      if($id){
                          $_GET['title'] = 'Atualização';
                          $_GET['message'] = 'Ocorreu um erro ao atualizar a saida!';
                      }else{
                          $_GET['title'] = 'Cadastro';
                          $_GET['message'] = 'Ocorreu um erro ao cadastrar a saida!';
                      }
                  }
                  $this->lista_saida();
                  $url = base_url(array('loja/produtos-saida'));
                  $this->output->append_output('<script>window.history.replaceState("", "Line Hair", "'. $url .'")</script>');
              }
  
          }
  
  
          public function editar_produto_saida()
          {

            $this->set_menu_active(
                array(
                    'menu' => 'Loja',
                    'submenu' => 'Loja-produtos-saida'
                    )
                );
                
              if($this->input->get('id')){
                  $dados['produto'] = $this->produtos_model->get_produto_entrada($this->input->get('id'));
  
                  $data = explode(' ', $dados['produto']->data_hora);
  
                  // $dados['data'] = $data[0];
                  // $dados['hora'] = $data[1];
  
                  $dados['produtos'] =  $this->produtos_model->get_produtos();
                  $dados['produtos_vinculados'] =  $this->produtos_model->get_produtos_vinculados_entrada($this->input->get('id'));
  
          //              echo '<pre>';
          // print_r($dados);
          // exit;
  
                  $this->montaTela('loja_saida/formulario', $dados);
              }
          }
  
  
          function excluir_produto_saida(){
  
  
              if($this->produtos_model->excluir_produto_entrada($this->input->post('id'))){
                              $response['type'] = 'success';
                              $response['title'] = 'Exclusão';
                              $response['message'] = 'Saíxa/Baixa Produto excluída com sucesso!';
                              echo json_encode($response);
                          }else{
                              $response['type'] = 'error';
                              $response['title'] = 'Exclusão';
                              $response['message'] = 'Ocorreu um erro ao excluír a Saída/Baixa do produto!';
                              echo json_encode($response);
                          }
                          exit;
              return;
          }
  
           /*FIM SAÍDA DE PRODUTOS */


           /* ESTOQUE */

           function verifica_estoque_produtos()
           {

            //vamos pegar todas entradas e saídas e depois montar as regras
             $produtos_estoque = $this->produtos_model->verifica_estoque_produtos($this->input->post('idproduto'));


            //  var_dump($produtos_estoque);
            //  exit;

             //caso o produto em questao utilize estoque dele mesmo
             if($produtos_estoque->estoque == 1):

                $soma_entradas = 0;

                $soma_saidas = 0;

                if(!empty($produtos_estoque->movimentacao)):

                    foreach($produtos_estoque->movimentacao as $movimentacao):

                        if($movimentacao->tipo == 1):
                            $soma_entradas = $soma_entradas + $movimentacao->qtd;
                        else:
                            $soma_saidas = $soma_saidas + $movimentacao->qtd;
                        endif;

                    endforeach;

                endif;

                $response['tipo_estoque'] = 'unico';
                $response['qtd_disponivel'] = $soma_entradas - $soma_saidas;
            
             //TRATANDO OS PRODUTOS COMPOSTOS
             else:

                if(!empty($produtos_estoque->vinculados)):

                    for ($i=0; $i < sizeof($produtos_estoque->vinculados); $i++) { 
                        
                        $soma_entradas = 0;
                        $soma_saidas = 0;

                        //PERCORRER AS MOVIMENTAÇÕES AGORA
                        if(!empty($produtos_estoque->vinculados[$i]->movimentacao)):

                            foreach($produtos_estoque->vinculados[$i]->movimentacao as $mov_vinculado):

                                if($mov_vinculado->tipo == 1):
                                    $soma_entradas = $soma_entradas + $mov_vinculado->qtd;
                                else:
                                    $soma_saidas = $soma_saidas + $mov_vinculado->qtd;
                                endif;

                            endforeach;

                        endif;

                        $response['tipo_estoque'] = 'composto';
                        $response['itens'][$i]['qtd_disponivel'] = $soma_entradas - $soma_saidas;
                        $response['itens'][$i]['qtd_utilizada_producao'] = $produtos_estoque->vinculados[$i]->qtd;
                        $response['itens'][$i]['desc_prod'] = $produtos_estoque->vinculados[$i]->titulo;
                        

                    }

                else:

                    //MELHORAR ISTO DEPOIS
                    $response['tipo_estoque'] = 'unico';
                    $response['qtd_disponivel'] = 99999;

                endif;

             endif;

             echo json_encode($response);
           
           }


           /* FIM ESTOQUE */


           /*ESTOQUE MINIMO */
           function estoque_minimo()
           {

            $this->set_menu_active(
                array(
                    'menu' => 'Loja',
                    'submenu' => 'Loja-produtos-estoque-minimo'
                    )
                );
                

            //vamos pegar todas entradas e saídas e depois montar as regras
            $produtos_estoque = $this->produtos_model->verifica_estoque__minimo_produtos();

            if(!empty($produtos_estoque)):

                foreach($produtos_estoque as $prod_estoque):

                    $soma_entradas = 0;

                    $soma_saidas = 0;

                    if(!empty($prod_estoque->movimentacao)):

                        foreach($prod_estoque->movimentacao as $movimentacao):
        
                            if($movimentacao->tipo == 1):
                                $soma_entradas = $soma_entradas + $movimentacao->qtd;
                            else:
                                $soma_saidas = $soma_saidas + $movimentacao->qtd;
                            endif;
        
                        endforeach;
        
                    endif;

                    $qtd_disponivel = $soma_entradas - $soma_saidas;

                    if($qtd_disponivel <= $prod_estoque->alerta_estoque_minimo):
                        $response['dados'][] = array(
                            'qtd_disponivel' => $soma_entradas - $soma_saidas,
                            'desc_prod' => $prod_estoque->titulo,
                            'alerta_estoque_minimo' => $prod_estoque->alerta_estoque_minimo,
                        );

                    endif;


                endforeach;

            endif;

            // echo '<pre>';
            // print_r($response);
            // exit;

            $this->montaTela('loja_relatorios/tabela_estoque_minimo', $response);

            

           }


           /*POSIÇÃO DE ESTOQUE */
           function posicao_estoque()
           {

            $this->set_menu_active(
                array(
                    'menu' => 'Loja',
                    'submenu' => 'Loja-produtos-posicao-estoque'
                    )
                );

            //vamos pegar todas entradas e saídas e depois montar as regras
            $produtos_estoque = $this->produtos_model->posicao_estoque();

            // echo '<pre>';
            // print_r($produtos_estoque);
            // exit;

            if(!empty($produtos_estoque)):

                foreach($produtos_estoque as $prod_estoque):

                    $soma_entradas = 0;

                    $soma_saidas = 0;

                    if(!empty($prod_estoque->movimentacao)):

                        foreach($prod_estoque->movimentacao as $movimentacao):
        
                            if($movimentacao->tipo == 1):
                                $soma_entradas = $soma_entradas + $movimentacao->qtd;
                            else:
                                $soma_saidas = $soma_saidas + $movimentacao->qtd;
                            endif;
        
                        endforeach;
        
                    endif;

                    // $qtd_disponivel = $soma_entradas - $soma_saidas;

                    // if($qtd_disponivel <= $prod_estoque->alerta_estoque_minimo):
                        $response['dados'][] = array(
                            'qtd_disponivel' => $soma_entradas - $soma_saidas,
                            'desc_prod' => $prod_estoque->titulo,
                            'valor_prod' => $prod_estoque->valor,
                            'alerta_estoque_minimo' => $prod_estoque->alerta_estoque_minimo,
                        );

                    // endif;


                endforeach;

            endif;

            // echo '<pre>';
            // print_r($response);
            // exit;

            $this->montaTela('loja_relatorios/tabela_estoque_posicao', $response);

            

           }
           

}
