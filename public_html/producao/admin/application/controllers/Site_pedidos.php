<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_pedidos extends TEC_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('site/pedidos_model');
    $this->load->model('loja/produtos_model');
    $this->load->model('loja/usuarios_model');
    $this->load->model('loja/clientes_model');
    $this->load->model('loja/maquininhas_cartao_model');
    $this->load->model('site/fotos_pedidos_model');
    $this->load->helper('form');
    $this->load->helper('text');

    ini_set('memory_limit', '-1'); 

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


  $data['clientes'] = array();

  $data['vendedores'] = $this->produtos_model->get_vendedores();

  $data['eventos'] = $this->produtos_model->get_eventos();

  $data['maquininhas_cartao'] = $this->maquininhas_cartao_model->get_ativas();
  $data['maquininhas_cartao_taxas'] = $this->maquininhas_cartao_model->get_taxas_ativas();

  //dados usuario
  $data['usuario'] = $this->pedidos_model->get_info_usuario_logado();

  // echo '<pre>';
  // print_r($data);
  // exit;

  $this->montaTela('pedidos/formulario', $data);
}

public function buscar_clientes()
{
  $termo = $this->input->get('q');
  $clientes = $this->produtos_model->get_clientes($termo, 30);
  $resultado = array(
    array('id' => 'novo', 'text' => 'Cadastrar novo')
  );

  if(!empty($clientes)){
    foreach($clientes as $cliente){
      $texto = $cliente->nome;
      if(!empty($cliente->telefone)){
        $texto .= ' - ' . $cliente->telefone;
      }

      $resultado[] = array(
        'id' => $cliente->id,
        'text' => $texto
      );
    }
  }

  $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode(array('results' => $resultado)));
}

private function proximo_dia_util($data)
{
  $data_util = new DateTime($data);
  $data_util->modify('+1 day');

  while(!$this->eh_dia_util($data_util)){
    $data_util->modify('+1 day');
  }

  return $data_util->format('Y-m-d');
}

private function eh_dia_util(DateTime $data)
{
  $dia_semana = (int) $data->format('N');
  if($dia_semana >= 6){
    return false;
  }

  $feriados = $this->feriados_bancarios_nacionais((int) $data->format('Y'));
  return !in_array($data->format('Y-m-d'), $feriados);
}

private function feriados_bancarios_nacionais($ano)
{
  $pascoa = $this->data_pascoa($ano);

  $feriados = array(
    $ano.'-01-01',
    $ano.'-04-21',
    $ano.'-05-01',
    $ano.'-09-07',
    $ano.'-10-12',
    $ano.'-11-02',
    $ano.'-11-15',
    $ano.'-11-20',
    $ano.'-12-25',
    $this->data_relativa($pascoa, -48),
    $this->data_relativa($pascoa, -47),
    $this->data_relativa($pascoa, -2),
    $this->data_relativa($pascoa, 60),
  );

  return $feriados;
}

private function data_relativa(DateTime $data, $dias)
{
  $nova_data = clone $data;
  $nova_data->modify(($dias >= 0 ? '+' : '').$dias.' days');
  return $nova_data->format('Y-m-d');
}

private function data_pascoa($ano)
{
  $a = $ano % 19;
  $b = (int) floor($ano / 100);
  $c = $ano % 100;
  $d = (int) floor($b / 4);
  $e = $b % 4;
  $f = (int) floor(($b + 8) / 25);
  $g = (int) floor(($b - $f + 1) / 3);
  $h = (19 * $a + $b - $d - $g + 15) % 30;
  $i = (int) floor($c / 4);
  $k = $c % 4;
  $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
  $m = (int) floor(($a + 11 * $h + 22 * $l) / 451);
  $mes = (int) floor(($h + $l - 7 * $m + 114) / 31);
  $dia = (($h + $l - 7 * $m + 114) % 31) + 1;

  return new DateTime(sprintf('%04d-%02d-%02d', $ano, $mes, $dia));
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


    // VALIDACAO DESCONTO MAXIMO (% E R$)
    $desconto_valor = (float) str_replace(',', '.', $this->input->post('valor_desconto'));
    $tipo_desconto = $this->input->post('tipo_desconto');
    $valor_base = (float) str_replace(',', '.', $this->input->post('valor'));
    $valor_frete_val = (float) str_replace(',', '.', $this->input->post('valor_frete'));
    $base_desconto = $valor_base + $valor_frete_val;

    if($tipo_desconto == 'porcentagem'){
      $pct_aplicado = $desconto_valor;
      $desconto_em_reais = $base_desconto > 0 ? ($base_desconto * $desconto_valor / 100) : 0;
    } else {
      $pct_aplicado = $base_desconto > 0 ? ($desconto_valor / $base_desconto) * 100 : 0;
      $desconto_em_reais = $desconto_valor;
    }

    $usuario_logado = $this->pedidos_model->get_info_usuario_logado();
    $desconto_maximo = (float) $usuario_logado->desconto_maximo;
    $desconto_maximo_valor = isset($usuario_logado->desconto_maximo_valor) ? (float) $usuario_logado->desconto_maximo_valor : 0;
    $excedeu_pct = $desconto_maximo > 0 && $pct_aplicado > $desconto_maximo;
    $excedeu_valor = $desconto_maximo_valor > 0 && $desconto_em_reais > $desconto_maximo_valor;

    if($excedeu_pct || $excedeu_valor){
      $autorizado = (isset($_SESSION['auth_desconto_autorizado']) && $_SESSION['auth_desconto_autorizado'] === true);
      if(!$autorizado){
        $motivos = array();
        if($excedeu_pct) $motivos[] = number_format($pct_aplicado, 1) . '% excede o limite de ' . number_format($desconto_maximo, 0) . '%';
        if($excedeu_valor) $motivos[] = 'R$ ' . number_format($desconto_em_reais, 2, ',', '.') . ' excede o limite de R$ ' . number_format($desconto_maximo_valor, 2, ',', '.');
        $msg = urlencode(implode(' e ', $motivos) . ' do seu usuario.');
        if($this->input->post('id')){
          redirect('site/editar-pedido?id=' . $this->input->post('id') . '&type=error&title=Desconto+não+permitido&message=' . $msg);
        } else {
          redirect('site/novo-pedido?type=error&title=Desconto+não+permitido&message=' . $msg);
        }
      }
      unset($_SESSION['auth_desconto_codigo'], $_SESSION['auth_desconto_expira'], $_SESSION['auth_desconto_autorizado']);
    }


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
      'tipo_desconto' => str_replace(',', '.', $this->input->post('tipo_desconto')),
      'valor_desconto' => str_replace(',', '.', $this->input->post('valor_desconto')),
      'valor_total' => str_replace(',', '.', $this->input->post('valor_total')),
      // 'data_pedido' => date('Y-m-d H:i:s'),
      'forma_pagamento_balcao' => $this->input->post('forma_pagamento'),
      'pessoa_entrega' => $this->input->post('nome_cliente'),
      'clientes_id' => $id_cliente,
      'data_entrega' => ($this->input->post('data_entrega')) ? inverteData($this->input->post('data_entrega'),'/') : null ,
      'hora_entrega' => $this->input->post('hora_entrega'),
      'cep_entrega' => $this->input->post('cep_entrega'),
      'rua_entrega' => $this->input->post('rua_entrega'),
      'numero_entrega' => $this->input->post('numero_entrega'),
      'bairro_entrega' => $this->input->post('bairro_entrega'),
      'cidade_entrega' => $this->input->post('cidade_entrega'),
      'estado_entrega' => $this->input->post('estado_entrega'),
      'complemento_entrega' => $this->input->post('complemento_entrega'),
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
    if(!empty($this->input->post('possui_bilhete')) && $this->input->post('possui_bilhete') == 1):
      $dados['bilhete'] = $this->input->post('bilhete');
    else:
      $dados['bilhete'] = '';
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



  /* SALAVANDO AS CONTAS A RECEBER DO PEDIDO */


  $contas_receber_count = $this->input->post('valor_receita');
  $data_base_cartao = ($this->input->post('data_solicitacao')) ? inverteData($this->input->post('data_solicitacao'), '/') : date('Y-m-d');
  $data_recebimento_cartao = $this->proximo_dia_util($data_base_cartao);
    
    if(count($contas_receber_count) > 0){

      $dados_receita = array();
      $receitas = array();

      foreach ($contas_receber_count as $key => $value) {

        if($this->input->post('valor_receita')[$key] != 0):


        $dados_receita['descricao'] = 'Parcela de pedido';
        $dados_receita['data_vencimento'] = inverteData($this->input->post('data_vencimento_receita')[$key],'/');
        $dados_receita['valor'] = str_replace(',', '.', $this->input->post('valor_receita')[$key]) ;
        $forma_pgto_receita = ($this->input->post('forma_pgto_receita')[$key]) ? $this->input->post('forma_pgto_receita')[$key] : null;
        $eh_credito = in_array((int) $forma_pgto_receita, array(3, 4, 5, 6));
        $dados_receita['status'] = $eh_credito ? 1 : $this->input->post('status_receita')[$key];
        $dados_receita['data_pago'] = $eh_credito ? $data_recebimento_cartao : (($this->input->post('data_pago_receita')[$key]) ? inverteData($this->input->post('data_pago_receita')[$key],'/') : null);
        //ESSE PLANO MANUAL
        $dados_receita['plano_contas_id'] = 33;
        $dados_receita['pedidos_id'] = $id_pedido;
        $dados_receita['forma_pgto'] = $forma_pgto_receita;
        $maquininhas = $this->input->post('maquininha_cartao_id_receita');
        $dados_receita['maquininha_cartao_id'] = (isset($maquininhas[$key]) && $maquininhas[$key]) ? $maquininhas[$key] : null;
        $taxas_maquininhas = $this->input->post('maquininha_taxa_id_receita');
        $dados_receita['maquininha_taxa_id'] = (isset($taxas_maquininhas[$key]) && $taxas_maquininhas[$key]) ? $taxas_maquininhas[$key] : null;
      
        array_push($receitas, $dados_receita);

        endif;

    }


  }


  $this->pedidos_model->salvar_contas_receber($receitas, $id_pedido);


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
        'complemento' => $this->input->post('complemento_entrega'),
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

// echo 'salvo';
// exit;

// $this->lista();
//         $url = base_url(array('loja',  'pedidos'));
//         $this->output->append_output('<script>window.history.replaceState("", "Albedo", "'. $url .'")</script>');

//         echo ''

//                 . '<script>'

//                 . 'if (confirm("IMPRIMIR PEDIDO?")){'

//                 . 'window.open("' . base_url() . 'loja/imprimir?print=1&id=' . $id_return . '","_blank")'

//                 . '}</script>';

  redirect('loja/pedidos');

        
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


    $cliente_selecionado = $this->produtos_model->get_cliente_resumo($dados['pedido']->clientes_id);
    $dados['clientes'] = (!empty($cliente_selecionado)) ? array($cliente_selecionado) : array();
  
    $dados['vendedores'] = $this->produtos_model->get_vendedores();
  
    $dados['eventos'] = $this->produtos_model->get_eventos();

    $dados['maquininhas_cartao'] = $this->maquininhas_cartao_model->get_ativas();
    $dados['maquininhas_cartao_taxas'] = $this->maquininhas_cartao_model->get_taxas_ativas();


    $dados['enderecos'] =  $this->clientes_model->get_enderecos_vinculados_por_pedido($this->input->get('id'));


    $dados['contas_receber'] =  $this->pedidos_model->get_contas_receber_pedidos($this->input->get('id'));

    //dados usuario
    $dados['usuario'] = $this->pedidos_model->get_info_usuario_logado();
    


    // echo '<pre>';
    // print_r($dados['contas_receber']);
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

//SOLICITAR CODIGO DE AUTORIZACAO DE DESCONTO
function solicitar_codigo_desconto()
{
    $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
    $expiresAt = time() + 900; // 15 minutos

    $_SESSION['auth_desconto_codigo'] = $code;
    $_SESSION['auth_desconto_expira'] = $expiresAt;
    $_SESSION['auth_desconto_autorizado'] = false;

    $pct = $this->input->post('pct');
    $vendedor = $this->auth->get_nome_usuario();

    $text = "🔐 *AUTORIZACAO DE DESCONTO*\n\n"
        . "Vendedor: *{$vendedor}*\n"
        . "Desconto solicitado: *{$pct}%*\n\n"
        . "Codigo: *{$code}*\n"
        . "Valido por 15 minutos.";

    $apiUrl = 'https://avelar.atenderbem.com/int/enqueueMessageToSend';
    $numeros = ['38984011923', '38988519293'];
    $erros = [];

    foreach ($numeros as $numero) {
        $postData = [
            "queueId" => 98,
            "apiKey" => 'ca21b412646e426c862dc63f9f374c68',
            "templateId" => 0,
            "headerFile" => "",
            "varsdata" => "",
            "number" => $numero,
            "country" => "BR",
            "clientId" => uniqid(),
            "text" => $text,
            "fileId" => 1768299,
            "buttonsConfig" => null,
            "urlButtonConfig" => null,
            "listConfig" => null,
            "campaignName" => "",
            "extData" => "",
            "extFlag" => 0,
            "hidden" => false
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        $log = date('Y-m-d H:i:s') . " | numero: {$numero} | HTTP: {$httpCode} | response: {$response}";
        if ($curlError) {
            $log .= " | curl_error: {$curlError}";
        }
        error_log("WHATSAPP_DESCONTO: " . $log);

        if ($httpCode < 200 || $httpCode >= 300) {
            $erros[] = "HTTP {$httpCode} para {$numero}" . ($response ? ": {$response}" : "");
        }
    }

    if (count($erros) === count($numeros)) {
        echo json_encode(['success' => false, 'message' => implode(' | ', $erros)]);
    } else {
        echo json_encode(['success' => true]);
    }
}

//VALIDAR CODIGO DE AUTORIZACAO
function validar_codigo_desconto()
{
    $codigo = $this->input->post('codigo');

    if (isset($_SESSION['auth_desconto_codigo']) &&
        isset($_SESSION['auth_desconto_expira']) &&
        $_SESSION['auth_desconto_expira'] > time() &&
        strtoupper($codigo) === $_SESSION['auth_desconto_codigo']) {

        $_SESSION['auth_desconto_autorizado'] = true;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Código inválido ou expirado.']);
    }
}

//BUSCANDO CUPOM
function get_cupom()
{

    $dados['cupom'] =  $this->clientes_model->get_cupom($this->input->post('cupom'));

     echo json_encode($dados);

}




}
