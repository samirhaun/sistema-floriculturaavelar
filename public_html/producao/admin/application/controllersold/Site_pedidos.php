<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_pedidos extends TEC_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('site/pedidos_model');
    $this->load->model('loja/produtos_model');
    $this->load->model('loja/usuarios_model');
    $this->load->model('loja/clientes_model');
    $this->load->model('site/fotos_pedidos_model');
    $this->load->helper('form');
    $this->load->helper('text');

    if($this->input->get('finalizados')){
            $this->set_menu_active(
            array(
                'menu' => 'pedidos',
                'submenu' => 'finalizados'
            )
        );    
        }else if($this->input->get('orcamento')){
            $this->set_menu_active(
            array(
                'menu' => 'pedidos',
                'submenu' => 'orcamento'
            )
        );  
        }else{
           $this->set_menu_active(
            array(
                'menu' => 'pedidos',
                'submenu' => 'andamento'
            )
        ); 
      }
  }

  public function index()
  {
    $this->orcamento();
    $this->lista();   
    
  }


  function verifica_permissoes(){

    $dados['permissoes'] = $this->usuarios_model->get_permissoes($this->auth->get_id_usuario());

    if ($dados['permissoes']->pedidos != 1) {
     $this->acesso_negado();
   }else{
     return 2;
   }

 }
 

 public function lista()
    {

      $this->set_menu_active(
        array(
            'menu' => 'loja',
            'submenu' => 'loja-pedidos'
            )
        );

        $data['pedidos'] =  $this->pedidos_model->get_pedidos_new();

        if($this->input->get('type')){
            $notification = new stdClass;
            $notification->type = $this->input->get('type');
            $notification->title = $this->input->get('title');
            $notification->message = $this->input->get('message');
            $data['notification'] = $notification;
        }
        //var_dump($data); exit;

        // echo '<pre>';
        // print_r($data);
        // exit;
    
        $this->montaTela('pedidos/lista', $data);
    }


/*
 public function lista()
 {

//   $dados['permissoes'] = $this->usuarios_model->get_permissoes($this->auth->get_id_usuario());
//   if ($dados['permissoes']->ver_pedidos == 1) {
//    $data['pedidos'] =  $this->pedidos_model->get_pedidos_andamento();
//  }else{
//    $data['pedidos'] =  $this->pedidos_model->get_pedidos_andamento($this->auth->get_id_usuario());
//  }


 $data['pedidos'] =  $this->pedidos_model->get_pedidos_full();

 if($this->input->get('type')){
  $notification = new stdClass;
  $notification->type = $this->input->get('type');
  $notification->title = $this->input->get('title');
  $notification->message = $this->input->get('message');
  $data['notification'] = $notification;
}

$this->montaTela('pedidos/lista', $data);
}
*/

public function orcamento()
 {

  $this->set_menu_active(
    array(
        'menu' => 'pedidos',
        'submenu' => 'orcamento'
    )
  );
  $dados['permissoes'] = $this->usuarios_model->get_permissoes($this->auth->get_id_usuario());
  if ($dados['permissoes']->ver_pedidos == 1) {
   $data['pedidos'] =  $this->pedidos_model->get_pedidos_orcamento();
 }else{
   $data['pedidos'] =  $this->pedidos_model->get_pedidos_orcamento($this->auth->get_id_usuario());
 }


 if($this->input->get('type')){
  $notification = new stdClass;
  $notification->type = $this->input->get('type');
  $notification->title = $this->input->get('title');
  $notification->message = $this->input->get('message');
  $data['notification'] = $notification;
}

$this->montaTela('pedidos/orcamento', $data);
}

public function lista_enderecos()
{
  $data['enderecos'] =  $this->pedidos_model->get_enderecos($this->input->get('id'));

  if($this->input->get('type')){
    $notification = new stdClass;
    $notification->type = $this->input->get('type');
    $notification->title = $this->input->get('title');
    $notification->message = $this->input->get('message');
    $data['notification'] = $notification;
  }

  $this->montaTela('pedidos/lista_enderecos', $data);
}


function novo_pedido(){

  // $this->verifica_permissoes();
  $data['produtos'] =  $this->produtos_model->get_produtos($apenas_pedidos = 1);


  $data['clientes'] = $this->produtos_model->get_clientes();

  $data['vendedores'] = $this->produtos_model->get_vendedores();

  $data['eventos'] = $this->produtos_model->get_eventos();

  //dados usuario
  $data['usuario'] = $this->pedidos_model->get_info_usuario_logado();

  // echo '<pre>';
  // print_r($data);
  // exit;

  $this->montaTela('pedidos/formulario', $data);
}

public function modelo_pedidos(){

  $dados['modelos'] = $this->produtos_model->get_modelo_valor($this->input->post('id'));                    

  echo json_encode($dados['modelos']);
}

function novo_orcamento(){

  $this->verifica_permissoes();

  $data['produtos'] =  $this->produtos_model->get_produtos();
  $data['modelos'] = $this->produtos_model->get_modelos();

  $data['clientes'] = $this->produtos_model->get_clientes();
  $data['tecidos'] = $this->produtos_model->get_tecidos();
  $data['cores'] = $this->produtos_model->get_cores();

  $data['tamanhos'] = $this->produtos_model->get_tamanhos();

  foreach ( $data['tamanhos']  as $key => $value) {
      $data['tamanhos'] [$key]->tamanho = str_replace(' ','_',$value->tamanho);
  }

  $this->set_menu_active(
            array(
                'menu' => 'pedidos',
                'submenu' => 'orcamento'
            )
        );

  $this->montaTela('pedidos/formulario-orcamento', $data);
}

function salvar_pedido(){


  // echo '<pre>';
  // print_r($_POST);
  // exit;


  // $this->verifica_permissoes();
  if($this->input->post()){


    /* SALVANDO NOVO CLIENTE CASO TENHA */
    if($this->input->post('clientes_id') == 'novo'):

      $dados = array(
        'origem' => 2,
        'nome' => $this->input->post('nome_cliente_new'),
        'email' => $this->input->post('email_cliente_new'),
        'telefone' => $this->input->post('telefone_cliente_new'),
        'cpf' => $this->input->post('cpf_cliente_new'),
        'nascimento' => ($this->input->post('nascimento_cliente_new')) ? inverteData($this->input->post('nascimento_cliente_new'),'/') : null,

    );

    $id_cliente = $this->clientes_model->salvar_cliente($dados, null);

    else:

      $id_cliente = $this->input->post('clientes_id');

    endif;


    if(!empty($this->input->post('data_pago'))):
      $data_pago = inverteData($this->input->post('data_pago'),'/');
      $pedido_pago = 1;
    elseif($this->input->post('pedido_pago') == 1):
      $data_pago = date('Y-m-d');
      $pedido_pago = 1;
    else:
      $data_pago = null;
      $pedido_pago = 0;
    endif;


    //DATA PAGO ENTRADA
    if(!empty($this->input->post('data_pgto_entrada'))):
      $pgto_entrada = inverteData($this->input->post('data_pgto_entrada'),'/');
    else:
      $pgto_entrada = null;
    endif;



    $dados = array(
      'origem' => 1,
      'valor' => str_replace(',', '.', $this->input->post('valor')),
      'valor_frete' => str_replace(',', '.', $this->input->post('valor_frete')),
      'valor_entrada' => str_replace(',', '.', $this->input->post('valor_entrada')),
      'valor_desconto' => str_replace(',', '.', $this->input->post('valor_desconto')),
      'valor_total' => str_replace(',', '.', $this->input->post('valor_total')),
      // 'data_pedido' => date('Y-m-d H:i:s'),
      'forma_pagamento_balcao' => $this->input->post('forma_pagamento'),
      'pessoa_entrega' => $this->input->post('nome_cliente'),
      'clientes_id' => $id_cliente,
      'data_entrega' => inverteData($this->input->post('data_entrega'),'/') ,
      'hora_entrega' => $this->input->post('hora_entrega'),
      'cep_entrega' => $this->input->post('cep_entrega'),
      'rua_entrega' => $this->input->post('rua_entrega'),
      'numero_entrega' => $this->input->post('numero_entrega'),
      'bairro_entrega' => $this->input->post('bairro_entrega'),
      'cidade_entrega' => $this->input->post('cidade_entrega'),
      'estado_entrega' => $this->input->post('estado_entrega'),
      'obs' => $this->input->post('obs'),
      'eventos_id' => $this->input->post('eventos_id'),
      'vendedores_id' => $this->input->post('vendedores_id'),
      'cupom_aplicado' => $this->input->post('cupom_aplicado'),
      'pedido_pago' => $pedido_pago,
      'data_pago' => $data_pago,
      'data_pgto_entrada' => $pgto_entrada,
      'forma_pgto_entrada' => $this->input->post('forma_pgto_entrada'),
    );

    //BILHETE
    if(!empty($this->input->post('bilhete'))):
      $dados['bilhete'] = $this->input->post('bilhete');
    endif;

    


            //editar pedido
 if($this->input->post('id')){
  $id = $this->input->post('id');

  $id_return = $id;

}else{

  $id = null;

  $id_return = null;

  $dados['data_pedido'] = date('Y-m-d H:i:s');

}




if($id_pedido = $this->pedidos_model->salvar_pedido($dados, $id))
{

  if(empty($id_return)):
    $id_return = $id_pedido;
  endif;


  /* SLAVANDO OS PRODUTOS DO PEDIDO */


  $produtos_count = $this->input->post('produto_collapse');
    
    if(count($produtos_count) > 0){

      $dados_produto = array();
      $produtos = array();

      foreach ($produtos_count as $key => $value) {


        $dados_produto['quantidade'] = $this->input->post('quantidade_collapse')[$key];
        $dados_produto['produtos_id'] = $this->input->post('produto_collapse')[$key];
        $dados_produto['valor_total'] = $this->input->post('valor_total_collapse')[$key];
        $dados_produto['pedidos_id'] = $id_pedido;
      
        array_push($produtos, $dados_produto);

    }


  }


  $this->pedidos_model->salvar_produtos_pedidos($produtos, $id);


  /* SALVANDO NOVO ENDEREÇO DO CLIENTE CASO TENHA */
  if($this->input->post('endereco_cliente') == 'novo'):

      $array[] = array(
        'bairro' => $this->input->post('bairro_entrega'),
        'cep' => $this->input->post('cep_entrega'),
        'cidade' => $this->input->post('cidade_entrega'),
        'clientes_id' => $id_cliente,
        'descricao' => $this->input->post('descricao_novo_endereco'),
        'estado' => $this->input->post('estado_entrega'),
        'numero' => $this->input->post('numero_entrega'),
        'rua' => $this->input->post('rua_entrega'),
        'ideditando' => 0,
        'complemento' => '',
    );

    $this->clientes_model->salvar_enderecos_vinculados($array);

  endif;


  $_GET['type'] = 'success';

  if($id){
    $_GET['title'] = 'Atualização';
    $_GET['message'] = 'pedido atualizado com sucesso!';
  }else{
    $_GET['title'] = 'Cadastro';
    $_GET['message'] = 'pedido cadastrado com sucesso!';
  }
}
else
{
  $_GET['type'] = 'error';
  if($id){
    $_GET['title'] = 'Atualização';
    $_GET['message'] = 'Ocorreu um erro ao atualizar o pedido!';
  }else{
    $_GET['title'] = 'Cadastro';
    $_GET['message'] = 'Ocorreu um erro ao cadastrar o pedido!';
  }
}

$this->lista();
        $url = base_url(array('loja',  'pedidos'));
        $this->output->append_output('<script>window.history.replaceState("", "Albedo", "'. $url .'")</script>');

        echo ''

                . '<script>'

                . 'if (confirm("IMPRIMIR PEDIDO?")){'

                . 'window.open("' . base_url() . 'loja/imprimir?print=1&id=' . $id_return . '","_blank")'

                . '}</script>';

  // redirect('loja/pedidos');

}
}

function salvar_pedido_orcamento(){




  $this->verifica_permissoes();
  if($this->input->post()){
    $dados = array(
      'clientes_id' => $this->input->post('cliente'),
      'data_solicitacao' => $this->input->post('data_solicitacao'),
      'data_entrega' => $this->input->post('data_entrega'),
      'valor' => str_replace(',', '.', $this->input->post('valor')),
      'observacao' => $this->input->post('observacao'),
      'prazos_pagamento' => $this->input->post('prazos_pagamento'),
      'forma_pagamento' => $this->input->post('forma_pagamento'),
      'codigo_pedido' => uniqid('P-'),
      'status_pedido' => 5,
    );

    $tamanhos = $this->produtos_model->get_tamanhos();

    foreach ( $tamanhos as $key => $value) {
        $tamanhos[$key]->tamanho = str_replace(' ','_',$value->tamanho);
    }

    $produtos_count = $this->input->post('modelo_collapse');
    if(count($produtos_count) > 0){
      $dados_produto = array();
      $produtos = array();
      foreach ($produtos_count as $key => $value) {
       $dados_produto['modelo'] = $this->input->post('modelo_collapse')[$key];

       $tams = null;
       $total_qtd = 0;
       $dados_produto['tamanho'] = null;
       foreach ($tamanhos as $key_tam => $value) {
         if ($this->input->post('tamanho_'.$value->tamanho)[$key] != '') {

          $tams = array(
            'tam' => $value->tamanho,
            'qtd' => $this->input->post('tamanho_'.$value->tamanho)[$key],
          );

         // $total_qtd += $tams['qtd'];
          $dados_produto['tamanho'][] = $tams;

        }

      }

      $dados_produto['quantidade'] = $this->input->post('quantidade_collapse')[$key];
      $dados_produto['observacao'] = $this->input->post('observacao_collapse')[$key];
      $dados_produto['tecido'] = $this->input->post('tecido_collapse')[$key];
      $dados_produto['cor'] = $this->input->post('cor_collapse')[$key];
      $dados_produto['id'] = ($this->input->post('id_item')[$key])? $this->input->post('id_item')[$key] : NULL;
      $dados_produto['valor'] = str_replace(',', '.', $this->input->post('valor_collapse')[$key]);
      array_push($produtos, $dados_produto);

    }


  }

  foreach ($produtos as $key => $value) {
   $produtos[$key]['tamanho'] = (json_encode($value['tamanho']));
 }



 $id = NULL;
            //editar pedido
 if($this->input->post('id')){
  $id = $this->input->post('id');
}else{
 $dados['usuarios_id'] = $this->auth->get_id_usuario();
 $dados['nome_usuario'] = $this->auth->get_nome_usuario();
}




if($id_pedido = $this->pedidos_model->salvar_pedido($dados, $id))
{

  $this->pedidos_model->salvar_produtos_pedidos($produtos, $id_pedido);


  $_GET['type'] = 'success';
  if($id){
    $_GET['title'] = 'Atualização';
    $_GET['message'] = 'pedido atualizado com sucesso!';
  }else{
    $_GET['title'] = 'Cadastro';
    $_GET['message'] = 'pedido cadastrado com sucesso!';
  }
}
else
{
  $_GET['type'] = 'error';
  if($id){
    $_GET['title'] = 'Atualização';
    $_GET['message'] = 'Ocorreu um erro ao atualizar o pedido!';
  }else{
    $_GET['title'] = 'Cadastro';
    $_GET['message'] = 'Ocorreu um erro ao cadastrar o pedido!';
  }
}

$this->orcamento();
$url = base_url(array('loja',  'pedidos-orcamento'));

$this->output->append_output('<script>window.history.replaceState("", "Albedo", "'. $url .'")</script>');
}
}

public function editar_pedido()
{
  // $data ['verifica'] = $this->fotos_pedidos_model->get_status_pedido($this->input->get('id'));
  //   if($data['verifica']->status_pedido == 5){

  //       $this->set_menu_active(
  //           array(
  //               'menu' => 'pedidos',
  //               'submenu' => 'orcamento'
  //           )
  //       ); 

  //   }else {

  //       $this->set_menu_active(
  //           array(
  //               'menu' => 'pedidos',
  //               'submenu' => 'andamento'
  //           )
  //       ); 
  //   }

  // $this->verifica_permissoes();

  if($this->input->get('id')){
            // $dados['categorias'] = $this->pedidos_model->get_categorias();
    $dados['pedido'] = $this->pedidos_model->get_pedido($this->input->get('id'));

    $dados['produtos_pedidos'] = $this->pedidos_model->get_produtos($this->input->get('id'));

    $dados['produtos'] =  $this->produtos_model->get_produtos($apenas_pedidos = 1);


    $dados['clientes'] = $this->produtos_model->get_clientes();
  
    $dados['vendedores'] = $this->produtos_model->get_vendedores();
  
    $dados['eventos'] = $this->produtos_model->get_eventos();


    $dados['enderecos'] =  $this->clientes_model->get_enderecos_vinculados_por_pedido($this->input->get('id'));

    //dados usuario
    $dados['usuario'] = $this->pedidos_model->get_info_usuario_logado();
    


    // echo '<pre>';
    // print_r($dados);
    // exit;


  }




  $this->montaTela('pedidos/formulario', $dados);



}

function excluir_pedido(){


  if($this->verifica_permissoes()){

    if ($this->input->post('id')) {

      $pedido = $this->pedidos_model->get_pedido($this->input->post('id'));

      if($pedido->status_pedido == 0 || $pedido->status_pedido == 5 || $pedido->status_pedido == 2){

        if($this->pedidos_model->excluir_itens_pedido($this->input->post('id'))){

          if($this->pedidos_model->excluir_pedido($this->input->post('id'))){

            $response['type'] = 'success';
            $response['title'] = 'Exclusão';
            $response['message'] = 'pedido excluído com sucesso!';
            echo json_encode($response);
          }else{
            $response['type'] = 'error';
            $response['title'] = 'Exclusão';
            $response['message'] = 'Ocorreu um erro ao excluír o pedido!';
            echo json_encode($response);
          }

        }
      }else{
        $response['type'] = 'warning';
        $response['title'] = 'Exclusão';
        $response['message'] = 'O pedido já entrou em processo de produção!';
        echo json_encode($response);

      }

    }
  }else{
    $response['type'] = 'error';
    $response['title'] = 'Acesso Negado';
    $response['message'] = 'Você nao tem permissão para realizar esta ação!';
    $this->output->set_output(json_encode($response));
  }
}

function excluir_item(){

  $this->verifica_permissoes();

  if ($this->input->post('id_item')) {


    if($this->pedidos_model->excluir_item_pedido($this->input->post('id_item'))){

      $dados['type'] = 'success';
      $dados['title'] = 'Apagado!';
      $dados['message']= 'Item Apagado com Sucesso';

      echo json_encode($dados);
    }else{
      $dados['type'] = 'error';
      $dados['title'] = 'Exclusão';
      $dados['message'] = 'Ocorreu um erro ao excluír o pedido!';
      echo json_encode($dados);
    }


  }


}

public function upload_imagem($value='')
{



  if(!empty($_FILES['imagem']['name'])){
   $this->load->library('upload', [
     'upload_path' => FCPATH.'../assets/images/product',
     'allowed_types' => 'jpg|png|gif',
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
      'width' => 800,
      'height' => 800,
      'quality' => '100%'
    ]
  );
    $this->image_lib->resize();
  }

  if($this->input->post('imagem_produto')){
    if ($_FILES['imagem']['name'] != $this->input->post('imagem_produto')) {
     $apagar = FCPATH.'../assets/images/product' . $this->input->post('imagem_produto');
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


function duplica_pedido(){


  $dados['pedido'] = $this->pedidos_model->get_pedido($this->input->post('id'));
  $dados['produtos_pedidos'] = $this->pedidos_model->get_produtos($this->input->post('id'));
  $dados['fotos_pedidos'] = $this->pedidos_model->get_fotos($this->input->post('id'));
  unset($dados['pedido']->id);
  $dados['pedido']->codigo_pedido = uniqid('P-');
  $dados['pedido']->status_pedido = 0;
  $dados['pedido']->data_solicitacao = date('d/m/Y');

  $id = $this->pedidos_model->salvar_pedido($dados['pedido']);

  foreach ($dados['produtos_pedidos'] as $key => $value) {
    unset($value->id);
    $value->pedidos_id = $id;
  }

  foreach ($dados['fotos_pedidos'] as $key => $value) {
    unset($value->id);
    $value->pedidos_id = $id;
  }



  $this->pedidos_model->salvar_produtos_pedidos_duplica($dados['produtos_pedidos']);
  $this->pedidos_model->salvar_produtos_fotos_duplica($dados['fotos_pedidos']);

}

//pegando endereços do cliente

function enderecos_cliente()
{
     $dados['enderecos'] =  $this->clientes_model->get_clientes_vinculados($this->input->post('id'));

     echo json_encode($dados);




}

//setando endereço do cliente

function enderecos_cliente_setar()
{
     $dados['endereco'] =  $this->clientes_model->get_cliente_endereco($this->input->post('id'));

     echo json_encode($dados);

}

//BUSCANDO CUPOM
function get_cupom()
{

    $dados['cupom'] =  $this->clientes_model->get_cupom($this->input->post('cupom'));

     echo json_encode($dados);

}




}
