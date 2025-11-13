<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site extends TEC_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loja_model');

      //$this->output->enable_profiler(true);

   }

   public function error()
       {
           show_404();
       }

   public function index(){
    $dados  = array(
        'produtos_ofertas' => $this->loja_model->get_ofertas_produtos(),
        'banners' => $this->loja_model->get_banners(),
        'categorias_produtos' => $this->loja_model->get_categorias_produtos_home(),
        'blocos_estaticos' => $this->loja_model->get_blocos_estaticos(),
        'ultimas_noticias' => $this->loja_model->get_noticias_home()
    );
   
    // $dados['marcas'] = $this->loja_model->get_marcas();
    // $dados['horario'] = $this->loja_model->get_horario();
    // $dados['secao1'] = $this->loja_model->get_secao1();
    // $dados['estrutura'] = $this->loja_model->get_estrutura();

    //$dados['ultimas_noticias'] = $this->loja_model->get_noticias();

    // echo '<pre>';
    // print_r($dados['categorias_produtos_all']);
    // exit;
    
    $this->montaTela('home/inicio', $dados);

}


public function produtos(){

  

  /*FILTROS*/

  //PESQUISA
  if(!empty($_GET['pesquisa'])){
    $dados['pesquisa_selecionada'] = $_GET['pesquisa'];
  }else{
    $dados['pesquisa_selecionada'] = '';
  }


  //CLASSIFICACAO
  if(!empty($_GET['filtro_classificacao'])){
    $dados['filtro_classificacao_selecionada'] = $_GET['filtro_classificacao'];
  }else{
    $dados['filtro_classificacao_selecionada'] = '';
  }

  //PAGINACAO
  if(!empty($_GET['paginacao'])){
    $dados['filtro_paginacao'] = $_GET['paginacao'];
  }else{
    $dados['filtro_paginacao'] = '3_0';
  }



  //CATEGORIAS
  if(!empty($_GET['categorias'])){

    //VAMOS PERCORRER PRIMEIRO PARA VERIFICAR SE NAO EXISTE PARA FILTRAR
    $categorias = explode(';', $_GET['categorias']);
    $total_categorias = sizeof($categorias);

    for ($i=0; $i < $total_categorias ; $i++) { 


          $tagsArray = $categorias;
          $termo = $categorias[$i];

          $count = 0;
          foreach ($tagsArray as $tag) {
            if ($tag == $termo) {
              $count++;
            }
          }

          if($count > 1){
            $_GET['categorias'] = str_replace($categorias[$i].';', '', $_GET['categorias']);
          }
      

    }


    $dados['categorias_selecionadas'] = $_GET['categorias'];

  }else{
    $dados['categorias_selecionadas'] = '';
  }


  //MARCAS
  if(!empty($_GET['marcas'])){

    //VAMOS PERCORRER PRIMEIRO PARA VERIFICAR SE NAO EXISTE PARA FILTRAR
    $marcas = explode(';', $_GET['marcas']);
    $total_marcas = sizeof($marcas);

    for ($i=0; $i < $total_marcas ; $i++) { 


          $tagsArray = $marcas;
          $termo = $marcas[$i];

          $count = 0;
          foreach ($tagsArray as $tag) {
            if ($tag == $termo) {
              $count++;
            }
          }

          if($count > 1){
            $_GET['marcas'] = str_replace($marcas[$i].';', '', $_GET['marcas']);
          }
      

    }


    $dados['marcas_selecionadas'] = $_GET['marcas'];

  }else{
    $dados['marcas_selecionadas'] = '';
  }


   //PREÇO
  if(!empty($_GET['preco'])){

    //VAMOS PERCORRER PRIMEIRO PARA VERIFICAR SE NAO EXISTE PARA FILTRAR
    $preco = explode(';', $_GET['preco']);
    $total_preco = sizeof($preco);

    for ($i=0; $i < $total_preco ; $i++) { 


          $tagsArray = $preco;
          $termo = $preco[$i];

          $count = 0;
          foreach ($tagsArray as $tag) {
            if ($tag == $termo) {
              $count++;
            }
          }

          if($count > 1){
            $_GET['preco'] = str_replace($preco[$i].';', '', $_GET['preco']);
          }
      

    }


    $dados['preco_selecionado'] = $_GET['preco'];

  }else{
    $dados['preco_selecionado'] = '';
  }


  //PREÇO
  if(!empty($_GET['desconto'])){

    //VAMOS PERCORRER PRIMEIRO PARA VERIFICAR SE NAO EXISTE PARA FILTRAR
    $desconto = explode(';', $_GET['desconto']);
    $total_desconto = sizeof($desconto);

    for ($i=0; $i < $total_desconto ; $i++) { 


          $tagsArray = $desconto;
          $termo = $desconto[$i];

          $count = 0;
          foreach ($tagsArray as $tag) {
            if ($tag == $termo) {
              $count++;
            }
          }

          if($count > 1){
            $_GET['desconto'] = str_replace($desconto[$i].';', '', $_GET['desconto']);
          }
      

    }


    $dados['desconto_selecionado'] = $_GET['desconto'];

  }else{
    $dados['desconto_selecionado'] = '';
  }


  /*FIM FILTROS*/


  /*ARRAYS*/

  $dados['total_produtos_paginacao'] = $this->loja_model->get_produtos_paginacao($dados);

  $dados['produtos'] = $this->loja_model->get_produtos($dados);

  $dados['categorias_produtos'] = $this->loja_model->get_categorias_produtos();

  $dados['marcas_produtos'] = $this->loja_model->get_marcas_produtos();

  $dados['precos_produtos'] = [
                                  [
                                    'valor' => '1_200',
                                    'descricao' => 'RS 1,00 a R$ 200,00'

                                  ],
                                  [
                                    'valor' => '200_300',
                                    'descricao' => 'RS 200,00 a R$ 300,00'
                                  ],
                                  [
                                    'valor' => '350_450',
                                    'descricao' => 'RS 350,00 a R$ 450,00'
                                  ],
                                  [
                                    'valor' => '500_1000',
                                    'descricao' => 'RS 500,00 a R$ 1.000,00'
                                  ],
                                  [
                                    'valor' => 'acima_1000',
                                    'descricao' => 'Acima de R$ 1.000,00'
                                  ],


                            ];

  $dados['descontros_produtos'] = [
                                  [
                                    'valor' => '1_10',
                                    'descricao' => 'até 10%'

                                  ],
                                  [
                                    'valor' => '10_15',
                                    'descricao' => 'de 10% até 15%'
                                  ],
                                  [
                                    'valor' => '15_30',
                                    'descricao' => 'de 15% até 30%'
                                  ],
                                  [
                                    'valor' => '30_50',
                                    'descricao' => 'de 30% até 50%'
                                  ],


                            ];



  // echo '<pre>';
  // print_r($dados);
  // exit;

  $this->montaTela('produtos/lista', $dados);

}


public function detalhes_produto(){

  $dados['produto'] = $this->loja_model->get_produto($this->input->get('nome_tag'));
  $dados['galeria'] = $this->loja_model->get_galeria_produtos($dados['produto']->id);

  $dados['outros_produtos'] = $this->loja_model->get_outros_produto($this->input->get('nome_tag'));


  // echo '<pre>';
  // print_r($dados['produto']);
  // exit;


  $this->montaTela('produtos/detalhes', $dados);

}


function sobre(){

  $dados['pagina'] = $this->loja_model->get_bloco_estatico('sobre');

  $this->montaTela('sobre/pagina', $dados);

}


function fotografia(){

  $dados['pagina'] = $this->loja_model->get_bloco_estatico('fotografia');

  $this->montaTela('fotografia/pagina', $dados);

}


function contato(){

  $dados['pagina'] = '';
  $this->montaTela('contato/pagina', $dados);

}

function produtosDEL(){

  $dados['pagina'] = '';
  $this->montaTela('produtos/lista', $dados);

}

function detalhes_produtoDEL(){

  $dados['pagina'] = '';
  $this->montaTela('produtos/detalhes', $dados);

}


function finalizar_pedido(){
  $dados['pagina'] = '';
  $this->montaTela('carrinho/finalizar_pedido', $dados);
}










/*FIM MUUV*/

public function detalhes_produtoOLD(){

        $dados['banners_slide'] = $this->loja_model->get_promocoes_slides();
        $id = $this->input->get('produto');
        if ($dados['produto'] = $this->loja_model->get_produto($id)) {
        $dados['galeria'] = $this->loja_model->get_galeria_produtos($id);
        $dados['categorias'] = $this->loja_model->get_categorias_produto($id);
        $dados['recomendados'] = $this->loja_model->get_recomendados_produto($dados['produto']->categoria_sexo, $id);

        foreach ($dados['recomendados'] as $key => $value) {

          $fotos['galeria'] = $this->loja_model->get_galeria_produtos($value->id);
          $dados['recomendados'][$key]= (object)array_merge((array)$dados['recomendados'][$key], $fotos);

        }

        if ($dados['produto']->tamanhos == 1) {
          
          $dados['tamanhos'] = $this->loja_model->get_tamanhos($dados['produto']->id);

        }
      
        $this->montaTela('site/produtos/detalhes', $dados);
        }else{
          show_404();
        }
}




public function modal_endereço(){
    $this->load->view('site/modal_endereco');
}




public function modal_login(){
  $this->load->view('modal_login');
}



public function pesquisar_produtos(){
    $dados['busca'] = $this->input->post('busca');
    $dados['produtos'] = $this->loja_model->get_all_produtos($dados['busca']);
    $this->load->view('site/lista_pesquisa', $dados);
}


public function sobreOLD(){
  $dados['sobre'] = $this->loja_model->get_sobre();
    $dados['contato'] = $this->loja_model->get_contato();
    $dados['ultimas_noticias'] = $this->loja_model->get_noticias();
    
    $this->montaTela('site/sobre/sobre', $dados);
}

public function contatoOLD(){
  $dados['banners_slide'] = $this->loja_model->get_promocoes_slides();
  $dados['sobre'] = $this->loja_model->get_sobre();
  $dados['contato'] = $this->loja_model->get_contato();
    $this->montaTela('site/contato/contato', $dados);
}


public function produtosOLD(){

                          //BUSCA POR CATEGORIAS
    $categorias = null;
    if ($busca = $this->input->get('busca_categorias')) {
      $categorias = explode(":", $busca);

      if($categorias[0]==''){
        array_splice($categorias, 0, 1);
      }
    };

              //BUSCA POR PREÇO
      $ordem_preco = null;
      if($this->input->get('ordem')){
        $ordem_preco = $this->input->get('ordem');
      }
              //BUSCA POR TAMANHO
      $tamanho = null;
      if($this->input->get('tamanho')){
        $tamanho = $this->input->get('tamanho');
      }

              //BUSCA POR FAIXA DE PRECO
      $faixa_preco = null;
      if($this->input->get('min_preco') && $this->input->get('max_preco')){
        $faixa_preco = array(
          'min_preco' => $this->input->get('min_preco'),
          'max_preco' => $this->input->get('max_preco'),
        );
      }

//--------------------------------------------------
      $categorias = null;
      if ($this->input->get('busca_categorias')) {
          $categorias = $this->input->get('busca_categorias');
      }

      $busca = null;
      if ($this->input->get('busca')) {
      $busca = $this->input->get('busca');
        
      }
      $sexo=null;

            //INICIA A PAGINACAO
    $this->load->library('pagination');
    $segment = 2;
    $pagina = ($this->uri->segment($segment)) ? (int) $this->uri->segment($segment) - 1: 0;
    $itens_pagina = 12;//quantidade por pagina

    $dados['produtos'] = $this->loja_model->get_all_produtos($categorias, $sexo, $ordem_preco, $tamanho, $faixa_preco, $busca); // Pega os produtos

    $num_itens =  count($dados['produtos']); // quantidade de produtos

    
    $dados['categorias'] = $this->loja_model->get_categorias_sexo($sexo);

    $dados['produtos'] = $this->loja_model->get_all_produtos($categorias, $sexo, $ordem_preco, $tamanho, $faixa_preco, $pagina, $itens_pagina, $busca);
    $dados['paginacao'] = $this->pagination->generate(array('produtos/'), $num_itens, $itens_pagina, $segment);


    foreach ($dados['produtos'] as $key => $value) { // GALERIA DE FOTOS DOS PRODUTOS
      $fotos['galeria'] = $this->loja_model->get_galeria_produtos($value->id);
      $dados['produtos'][$key]= (object)array_merge((array)$dados['produtos'][$key], $fotos);
    }

    $dados['termo_busca'] = $busca;
    $dados['categorias_busca'] = $categorias;
    $dados['preco'] = $ordem_preco;
    $dados['tamanho'] = $tamanho;
    $dados['min_preco'] = $faixa_preco['min_preco'];
    $dados['max_preco'] = $faixa_preco['max_preco'];
  


     $this->montaTela('site/produtos/lista', $dados);
}


public function envia_contato(){


  $this->load->library('email');
  $this->email->set_newline("\r\n");
  $config['charset'] = 'utf-8';
  $config['mailtype'] = 'html';
  $this->email->initialize($config);


  $this->email->from($_POST['email'], $_POST['nome']);
  $this->email->to('contato@institutoespiritadoamor.com.br');
  $this->email->reply_to($_POST['email'], 'Contato pelo Site');

  $this->email->subject($this->input->post('assunto'));

  $dados = array(
      'mensagem' => $this->input->post('mensagem'),
      'nome' => $this->input->post('nome'),
      'email' => $_POST['email']
  );

  
$this->email->message($this->load->view('site/mensagem_email', $dados, TRUE));

$this->email->send();

echo true;

}


public function assina_newsletter(){

            $email = $this->input->post('email');

          if($this->loja_model->salvar_newsletter($email)){
              $result = TRUE;
          }else{
              $result = FALSE;
          }

          echo json_encode($result);

}


public function meus_pedidos(){

    $dados = array(
        'pedidos' => $this->loja_model->get_meus_pedidos($this->auth->get_id_perfil())
    );

    // echo '<pre>';
    // print_r($dados);
    // exit;
    $this->montaTela('perfil/meus_pedidos', $dados);
}


public function pedido_detalhes(){

    $dados = array(
        'produtos' => $this->loja_model->get_meus_pedidos_produtos($this->input->get('id')),
        'pedido' => $this->loja_model->get_pedido($this->input->get('id'))
    );

    $this->montaTela('site/pedido-detalhes', $dados);
}

 





  public function salvar_comentario(){

    $dados = array();
    if ($_POST) {
      $dados['comentario'] = $this->input->post('comentario');
      $dados['produtos_id'] = $this->input->post('id');
      $dados['classificacao'] = $this->input->post('classificacao');
      $dados['clientes_id'] = $this->auth->get_id_perfil();
      $dados['data'] = date("Y-m-d");
    }

    if ($this->loja_model->salva_comentario($dados)) {
      $dados['nome'] = $this->auth->get_nome_perfil();
      $_GET['produto'] =   $dados['produtos_id'];
      $this->detalhes_produto();
      $url = base_url('detalhes-produto');
      $this->output->append_output('<script>window.history.replaceState("", "Lamitié", "'. $url .'"); swal("", "Comentário enviado")</script>');
    }
  }



  /*NOTICIAS*/


  public function noticias(){

    $dados['noticias'] = $this->loja_model->get_noticias();
    $dados['ultimas_ofertas'] = $this->loja_model->get_ultimas_ofertas();
    $dados['categorias_produtos'] = $this->loja_model->get_categorias_produtos();


    $this->montaTela('noticias/lista', $dados);

}

  public function detalhes_noticia(){

    $dados['noticia'] = $this->loja_model->get_noticia($this->input->get('tag'));
    $dados['ultimas_ofertas'] = $this->loja_model->get_ultimas_ofertas();
    $dados['categorias_produtos'] = $this->loja_model->get_categorias_produtos();


    $this->montaTela('noticias/detalhes', $dados);

}




}
