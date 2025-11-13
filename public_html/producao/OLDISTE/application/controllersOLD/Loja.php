<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loja extends TEC_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('Loja_model');
  }

 

  public function calcula_frete(){
    $ids = $this->session->userdata('carrinho_gury_informatica_1a2s3d4f');

    if (is_null($ids)) {
      return $this->output->set_output(json_encode('vazio'));
    }
    $frete_PAC = 0.0;
    $frete_SEDEX = 0.0;
    foreach ($ids as $key => $id) {

      $data['produto'] = $this->Loja_model->get_produto($id->produto); //DADOS DO PRODUTO
      $cep = $this->input->post('cep');

      $resultado = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string'); //VERIFICA O CEP

      if(!$resultado){  //SE O CEP NAO FOR VÁLIDO
        $resultado = 0;
      }
      parse_str($resultado, $retorno);

      if($retorno['resultado']!=0){ //SE O CEP FOR VÁLIDO

        $this->load->library('frete');

        //array com os dados para busca do valor do frete
        $array_correios = array (
          'nCdEmpresa'          =>  "", // Código da empresa  (opcional)
          'sDsSenha'            =>  "" , // Senha da empresa   (opcional)
          'sCepOrigem'          =>  "39400005"  , // Cep de origem    (sem - hífen)
          'sCepDestino'         =>  $cep, // Cep de destino   (sem - hífen)
          'nVlPeso'             =>  $data['produto']->peso, // Peso da embalagem  (em kilogramas)
          'nCdFormato'          =>  "1" , // Formato da encomenda (1 para caixa/pacote, 2 para rolo/prisma)
          'nVlComprimento'      =>  $data['produto']->comprimento_embalagem, // Comprimento      (em cm somente números)
          'nVlAltura'           =>  $data['produto']->altura_embalagem,   // Altura
          'nVlLargura'          =>  $data['produto']->largura_embalagem, // Largura
          'nVlDiametro'         =>  "0" , // Diametro
          'sCdMaoPropria'       =>  "n" , // Mão própria      (s == sim, n == não)
          'nVlValorDeclarado'   =>  "0" , // Valor declarado    (valor ou 0)
          'sCdAvisoRecebimento' =>  "n"   , // Aviso de recebimento (s == sim, n == não)
          'StrRetorno'          =>  'xml'       , // Tipo de retorno    (xml / popup / url)
          'nCdServico'          =>  '41106,40010'    // Código do serviço
        );

        $fretes = $this->frete->calcularFrete($array_correios, false);

        foreach ($fretes as $key => $value) {

          $frete[] = ($value->Valor);

        }
        foreach ($fretes as $key => $value) {

          $prazo[] = ($value->PrazoEntrega);

        }
        $valores_frete = array(

          'fretePac' => $frete[0],
          'freteSedex' => $frete[1]
        );
        $prazos_frete = array(

          'prazoPac' => $prazo[0],
          'prazoSedex' => $prazo[1]
        );
        $frete['fretes'] = (object) $valores_frete;
        $frete['prazos'] = (object) $prazos_frete;

      }else{
        return $this->output->set_output(json_encode('error'));;
      }

      if (!isset($frete['fretes'])) {
         return $this->output->set_output(json_encode('error'));
      }
    }

    $fretes_todos = array(
      'sedex' => number_format((float)$frete['fretes']->freteSedex[0],2,',', ''),
      'pac' => number_format((float)$frete['fretes']->fretePac[0],2,',', ''),
      'sedex_prazo' => (string)$frete['prazos']->prazoSedex[0],
      'pac_prazo' => (string)$frete['prazos']->prazoPac[0]
    );

    echo json_encode($fretes_todos);
  }




  public function calcula_frete_finalizar($cep){
    $ids = $this->session->userdata('carrinho_gury_informatica_1a2s3d4f');

    if (is_null($ids)) {
      return $this->output->set_output(json_encode('vazio'));
    }
    $frete_PAC = 0.0;
    $frete_SEDEX = 0.0;
    foreach ($ids as $key => $id) {

      $data['produto'] = $this->Loja_model->get_produto($id->produto); //DADOS DO PRODUTO


      $resultado = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string'); //VERIFICA O CEP

      if(!$resultado){  //SE O CEP NAO FOR VÁLIDO
        $resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";
      }
      parse_str($resultado, $retorno);

      if($retorno['resultado']!=0){ //SE O CEP FOR VÁLIDO

        $this->load->library('frete');

        //array com os dados para busca do valor do frete
        $array_correios = array (
          'nCdEmpresa'          =>  "", // Código da empresa  (opcional)
          'sDsSenha'            =>  "" , // Senha da empresa   (opcional)
          'sCepOrigem'          =>  "39400059"  , // Cep de origem    (sem - hífen)
          'sCepDestino'         =>  $cep, // Cep de destino   (sem - hífen)
          'nVlPeso'             =>  $data['produto']->peso, // Peso da embalagem  (em kilogramas)
          'nCdFormato'          =>  "1" , // Formato da encomenda (1 para caixa/pacote, 2 para rolo/prisma)
          'nVlComprimento'      =>  $data['produto']->comprimento_embalagem, // Comprimento      (em cm somente números)
          'nVlAltura'           =>  $data['produto']->altura_embalagem,   // Altura
          'nVlLargura'          =>  $data['produto']->largura_embalagem, // Largura
          'nVlDiametro'         =>  "0" , // Diametro
          'sCdMaoPropria'       =>  "n" , // Mão própria      (s == sim, n == não)
          'nVlValorDeclarado'   =>  "0" , // Valor declarado    (valor ou 0)
          'sCdAvisoRecebimento' =>  "n"   , // Aviso de recebimento (s == sim, n == não)
          'StrRetorno'          =>  'xml'       , // Tipo de retorno    (xml / popup / url)
          'nCdServico'          =>  '41106,40010'    // Código do serviço
        );

        $fretes = $this->frete->calcularFrete($array_correios, false);

        foreach ($fretes as $key => $value) {

          $frete[] = ($value->Valor);

        }

        foreach ($fretes as $key => $value) {

          $prazo[] = ($value->PrazoEntrega);

        }



        $valores_frete = array(

          'fretePac' => $frete[0],
          'freteSedex' => $frete[1]
        );

        $prazos_frete = array(

          'prazoPac' => $prazo[0],
          'prazoSedex' => $prazo[1]
        );

        $preco = $data['produto']->valor;

        $total = array(

          'totalPac' =>  number_format( str_replace(',' , '.', $frete[0]) + str_replace(',' , '.', $preco), 2, '.', ','),
          'totalSedex' =>  number_format(str_replace(',' , '.', $frete[1]) + str_replace(',' , '.', $preco), 2, '.', ',')
        );



        $frete['fretes'] = (object) $valores_frete;
        $frete['prazos'] = (object) $prazos_frete;
        $frete['totalFrete'] = (object) $total;


      }else{

        $frete = 'error';
      }

      $frete_PAC = (str_replace(',', '.', $frete['fretes']->fretePac[0])) + (float)$frete_PAC;
      $frete_SEDEX = (str_replace(',', '.', $frete['fretes']->freteSedex[0])) + (float)$frete_SEDEX;

    }

    $fretes_todos = array(
      'sedex' => number_format($frete_SEDEX,2,'.', ''),
      'pac' => number_format($frete_PAC,2,'.', ''),
      'sedex_prazo' => (string)$frete['prazos']->prazoSedex[0],
      'pac_prazo' => (string)$frete['prazos']->prazoPac[0]

    );

    return $fretes_todos;
  }


  public function confirma_carrinho(){


    $carrinho = $this->session->userdata('carrinho_gury_informatica_1a2s3d4f');

    if (!$this->auth->existe_sessao()) {
////
      redirect(base_url('login-registrar').'?finalizar=true');
    }

    if($carrinho){
      $produtos = array();
      foreach ($carrinho as $key => $produto) {

        $prod = $this->loja_model->produtos_carrinho($produto->produto); // BUSCA O PRODUTO
        $prod->{'quantidade'} = $produto->quantidade; //ADICIONA A QUANTIDADE
        $produtos[] = $prod;
      }

      $carrinho['carrinho'] =  $produtos;
      $dados['usuario'] = $this->loja_model->get_usuario($this->auth->get_id_perfil());
      //$dados['frete'] = $this->calcularFrete_unico();
      $dados['produtos'] = $carrinho['carrinho'];

      $this->montaTela('carrinho/finalizar_pedido', $dados);

    }else {

      $this->carrinho();


    }


  }


  public function fechar_pedido(){

    /*VARIÁVEL PARA O FRETE DA LOJA*/
    if($this->input->post('payment_method') == 2){
      $frete_loja = 0;
    }else{
      $frete_loja = 7;
    }
    

    //REGRAS PARA VALIDAÇÃO
    $regras = [
        [
            'field' => 'rua',
            'label' => 'rua',
            'rules' => [
                'required',
                'trim'
            ],
        ],
        [
            'field' => 'numero',
            'label' => 'numero',
            'rules' => [
                'required',
                'trim'
            ],
        ],
        [
            'field' => 'bairro',
            'label' => 'bairro',
            'rules' => [
                'required',
                'trim'
            ],
        ],
        [
            'field' => 'cidade',
            'label' => 'cidade',
            'rules' => [
                'required',
                'trim'
            ],
        ],
        [
            'field' => 'estado',
            'label' => 'estado',
            'rules' => [
                'required',
                'trim'
            ],
        ],
        [
            'field' => 'telefone',
            'label' => 'telefone',
            'rules' => [
                'required',
                'trim'
            ],
        ],
        [
            'field' => 'payment_method',
            'label' => 'payment_method',
            'rules' => [
                'required',
                'trim'
            ],
        ],
        // [
        //     'field' => 'forma_pagamento',
        //     'label' => 'forma_pagamento',
        //     'rules' => [
        //         'required',
        //         'trim'
        //     ],
        // ],
    ];


    $this->form_validation->set_rules($regras);

    if (FALSE === $this->form_validation->run()) {

        $this->confirma_carrinho();

        $this->output->append_output("<script>
          swal('Por favor, preencha os campos obrigatórios.');
          </script>");

    }else{

      /*VAMOS FINALZIAR O PEDIDO*/

      $produtos = $this->session->userdata('carrinho_gury_informatica_1a2s3d4f');

      $usuario = $this->loja_model->get_usuario($this->auth->get_id_perfil());

      //PEGA OS PRODUTOS DO CARRINHO

      // echo '<pre>';
      // print_r($produtos);
      // exit;

      $total_pedido = 0;

      $dados['produtos_vendidos'] = null;
      foreach ($produtos as $key => $produto) {
        $prod = $this->Loja_model->get_produto_id($produto->produto);
        $prod->{'qtd'} = $produto->quantidade;
        $dados['produtos_vendidos'][] = $prod;

        $total_pedido +=  $prod->valor*$produto->quantidade;

      }

      if($this->input->post('forma_pagamento') == 1){
        $troco = '<br><b>*******TROCO PARA:</b>'.$this->input->post('troco');
      }else{
        $troco = '';
      }

      $db_pedido = array(
        //'perfis_id' =>$this->auth->get_id_perfil(),

        'origem' => 2,
        'valor_total' => $total_pedido + $frete_loja,
        'valor' => $total_pedido + $frete_loja,
        'data_pedido' => date('Y-m-d H:i:s'),
        //'valor_frete' => $request['shippingCost'],
        //'codigo_pag' => $response,
        // 'tipo_frete' => $tipo_frete,
        //'quantidade' => $this->input->post('quantidade'),
        'forma_pagamento' => $this->input->post('forma_pagamento'),
        'tipo_pagamento' => $this->input->post('payment_method'),
        //'codigo_pedido' => $request['reference'],
        'email_cliente' => $usuario->email,
        'telefone_cliente' => $this->input->post('telefone'),
        'rua_entrega' => $this->input->post('rua'),
        'nome_cliente' => $usuario->nome,
        'numero_entrega' => $this->input->post('numero'),
        'bairro_entrega' => $this->input->post('bairro'),
        'cep_entrega' => $this->input->post('cep'),
        'cidade_entrega' => $this->input->post('cidade'),
        'estado_entrega' => $this->input->post('estado'),
        'complemento_entrega' => $this->input->post('complemento'),
        'clientes_id' => $this->auth->get_id_perfil(),
        'obs' => $this->input->post('observacoes').''.$troco
      );
      if($id_pedido = $this->Loja_model->salvar_pedido($db_pedido)){
        //Se a operação tiver sido bem sucedida, redirecionamos o cliente para o //
        //ambiente de pagamento.//
        if($this->Loja_model->salvar_pedido_produtos($dados['produtos_vendidos'], $id_pedido)){

          $this->Loja_model->atualiza_estoque_produto($dados['produtos_vendidos']);

          unset($_SESSION['carrinho_gury_informatica_1a2s3d4f']);

          if($this->input->post('payment_method') == 1 && $this->input->post('forma_pagamento') == 3){
            redirect('meus-pedidos?efetuado=true&pagar=1&cod_ped='.$id_pedido.'');
          } else{
            redirect('meus-pedidos?efetuado_not_on=true');
          }         

             

        }
      }else{
        echo 'error';
      }



    }





  }


  public function fechar_pedidoOLD()
  {
  

    $produtos = $this->session->userdata('carrinho_gury_informatica_1a2s3d4f');//produtos do carrinho

    if ($this->input->post('tipo_endereco')){
      $usuario = $this->loja_model->get_usuario($this->auth->get_id_perfil());
      $endereco = array(
        'rua' => $usuario->rua,
        'cep' => $usuario->cep,
        'numero' => $usuario->numero,
        'telefone' => $usuario->telefone,
        'bairro' => $usuario->bairro,
        'complemento' => $usuario->complemento,
        'cidade' => $usuario->cidade,
        'uf' =>  $usuario->estado
      );

    }else{
      //DADOS DO ENDEREÇO
      $endereco = array(
        'rua' => $this->input->post('rua'),
        'cep' => $this->input->post('cep'),
        'numero' => $this->input->post('numero'),
        'telefone' => $this->input->post('telefone'),
        'bairro' => $this->input->post('bairro'),
        'complemento' => $this->input->post('complemento'),
        'cidade' => $this->input->post('cidade'),
        'uf' =>  $this->input->post('estado')
      );
    }
    //PEGA OS PRODUTOS DO CARRINHO
    $dados['produtos_vendidos'] = null;
    foreach ($produtos as $key => $produto) {
      $prod = $this->Loja_model->get_produto($produto->produto);
      $prod->{'qtd'} = $produto->quantidade;
      $prod->{'tamanhos_id'} = $produto->tamanho;
      $dados['produtos_vendidos'][] = $prod;
    }




    $this->load->library('Pagseguro');
    if ($this->input->post('tipo_endereco')) {
      $cliente = $usuario->nome;
    }else{
      $cliente = $this->input->post('nome_completo');
    }

    $sandbox = false;
    $credenciais = $this->pagseguro->getCredenciais($sandbox);

    $email = $credenciais['email'];
    $token = $credenciais['token'];
    $url_request = $credenciais['url_request'];

    //Campos da requisição pagseguro
    $request = array(
      'email'           => $email,
      'token'           => $token,
      //'receiverEmail'   => $email,
      'currency'        => 'BRL',
      //'redirectURL'     => base_url(array('loja', 'pagamento-finalizado')),
      //'notificationURL' => base_url(array('loja', 'notificacoes-pag'))
    );

    //nome do comprador
    $request['senderName'] = $cliente;
    //email
    $request['senderEmail'] = $this->auth->get_email_perfil();
    //dados do produto
    $valor_total = 0;
    foreach ($dados['produtos_vendidos'] as $key => $produto) {
      $request['itemId'.($key+1)] = $produto->id;
      $request['itemDescription'.($key+1)] = $produto->titulo;
      $request['itemQuantity'.($key+1)] = $produto->qtd;
      $request['itemAmount'.($key+1)] = $produto->valor;
      $valor_total=$valor_total+($produto->qtd*$produto->valor);
    }

    //calcula o frete
    $fretes = $this->calcularFrete_unico();
    $request['shippingCost'] = number_format((float)$fretes, 2, '.', '');

    // if($tipo_frete = $this->input->post('frete_tipo') == 'pac'){
    //   $prazo = $fretes['pac_prazo'];
    // }else{
    //   $request['shippingCost'] = number_format((float)$fretes['sedex'], 2, '.', '');
    //   $prazo = $fretes['sedex_prazo'];
    // }

    //Numero do pedido
    $request['reference'] = uniqid('U'.$fretes['sedex'].'-');

    // $request['paymentMethodGroup1'] = 'CREDIT_CARD';
    // $request['paymentMethodConfigKey1_1'] = 'DISCOUNT_PERCENT';
    // $request['paymentMethodConfigValue1_1'] = '25.00';


    // echo '<pre>';
    // var_dump($request);
    // exit;
    //Envia a requisição e obtém a resposta do pagseguro

    $response = $this->pagseguro->sendRequestPag($request, $sandbox);

    if( strlen($response)>3){
      $db_pedido = array(
        //'perfis_id' =>$this->auth->get_id_perfil(),

        'valor_total' => $valor_total+$request['shippingCost'],
        'valor' => $valor_total,
        'data' => date('Ymd'),
        'valor_frete' => $request['shippingCost'],
        'codigo_pag' => $response,
        // 'tipo_frete' => $tipo_frete,
        //'quantidade' => $this->input->post('quantidade'),
        'forma_pagamento' => '0',
        'codigo_pedido' => $request['reference'],
        'email_cliente' => $request['senderEmail'],
        'telefone_cliente' => $endereco['telefone'],
        'rua_entrega' => $endereco['rua'],
        'nome_cliente' => $cliente,
        'numero_entrega' => $endereco['numero'],
        'bairro_entrega' => $endereco['bairro'],
        'cep_entrega' => $endereco['cep'],
        'cidade_entrega' => $endereco['cidade'],
        'estado_entrega' => $endereco['uf'],
        'complemento_entrega' => $endereco['complemento'],
        'clientes_id' => $this->auth->get_id_perfil()
      );
      if($id_pedido = $this->Loja_model->salvar_pedido($db_pedido)){
        //Se a operação tiver sido bem sucedida, redirecionamos o cliente para o //
        //ambiente de pagamento.//
        if($this->Loja_model->salvar_pedido_produtos($dados['produtos_vendidos'], $id_pedido)){
          $this->Loja_model->atualiza_estoque_produto($dados['produtos_vendidos']);
          unset($_SESSION['carrinho_gury_informatica_1a2s3d4f']);
          echo json_encode($response);
        }
      }else{
      echo 'error';
      }
    }else{
      echo "error";
    }

  }



  public function notificacoes_pagseguro()
  {
    
      if($this->input->post('notificationType') && $this->input->post('notificationType') == 'transaction'){
          $sandbox = false;
          $this->load->library('Pagseguro');
          $result = $this->pagseguro->verificarNotificacoes($this->input->post('notificationCode'), $sandbox);
          $status = '';
        
          switch ($result->status) {
              case '1':
                      //aguardando pagamento
              $status = '1';
              break;
              case '2':
                      //em analise
              $status = '2';
              break;
              case '3':
                      //pago
              $status = '3';
              break;
              case '4':
                      //disponivel
              $status = '4';
              break;
              case '5':
                      //em disputa
              $status = '5';
              break;
              case '6':
                      //Devolvida
              $status = '6';
              break;
              case '7':
                      //cancelado
              $status = '7';
              break;
          }

          $tipo_pagamento = '';
          switch ($result->paymentMethod->type) {
              case '1':
                      //Cartão de crédito
              $tipo_pagamento = '1';
              break;
              case '2':
                      //Boleto
              $tipo_pagamento = '2';
              break;
              case '3':
                      //Débito online
              $tipo_pagamento = '3';
              break;
              case '4':
                      //Saldo pagSeguro
              $tipo_pagamento = '4';
              break;
              case '5':
                      //Oi Paggo
              $tipo_pagamento = '5';
              break;
              case '7':
                      //Depósito em conta
              $tipo_pagamento = '7';
              break;
          }
          if(strlen($status) > 0){
              if($this->Loja_model->existe_transacao(($result->code), $result->reference)){
                  $array = array('status_pagseguro' => $status, 'tipo_pagamento_pagseguro' => $tipo_pagamento);
                  $this->Loja_model->update_status_pagamento($result->reference, $array);
                  if($status == '3'){
                  // $this->Loja_model->atualiza_estoque($result->reference);
               }
           }else{
              $array = array('status_pagseguro' => $status, 'tipo_pagamento_pagseguro' => $tipo_pagamento, 'id_transacao' => str_replace("-", "", $result->code));
              $id_pedido = $this->Loja_model->update_status_pagamento($result->reference, $array);

              if($id_pedido){
                if($status == '3'){
                 //$this->Loja_model->atualiza_estoque($id_pedido->id);
             }
              }
       }
   }
  }else{

  }
  }



  public function pagamento_finalizado(){

    if(!empty($_GET['transaction_id']) && $this->session->userdata('pedido_atual')){
      //Atualizando status da inscrição para inscrição finalizada
      if($this->Loja_model->finalizar_pedido($this->session->userdata('pedido_atual'), str_replace("-", "", $_GET['transaction_id']))){
        //APAGANDO AS SESSÕES
        $this->session->unset_userdata('pedido_atual');
        if($this->auth->existe_sessao()){
          $this->pedido_finalizado();
        }else{
          redirect(base_url(),'refresh');
        }
      }
    }else{
      $this->session->unset_userdata('pedido_atual');
      if($this->auth->existe_sessao()){
        $this->pedido_finalizado();
      }else{
        redirect(base_url(),'refresh');
      }
    }
  }


  public function pedido_finalizado()
  {
    $this->montaTela('site/pedido-finalizado');
  }


  public function meus_pedidos()
  {
    $data['pedidos'] = $this->Loja_model->get_meus_pedidos($this->auth->get_id_perfil());
    $data['acao_historico'] = $this->rede_model->get_acao_historico();
    //var_dump($data);exit;
    $this->montaTela('site/meus_pedidos', $data);
  }

public function calcularFrete_unico(){
   $ids = $this->session->userdata('carrinho_gury_informatica_1a2s3d4f');

    if (is_null($ids)) {
      return $this->output->set_output(json_encode('vazio'));
    }
    $frete = 0;
    foreach ($ids as $key => $id) {
      
        $data['produto'] = $this->loja_model->get_produto($id->produto); //DADOS DO PRODUTO

        $frete+=$data['produto']->valor_frete * $id->quantidade;
    }
    return $frete;
}

public function atualiza_pagseguro(){

  $this->loja_model->atualiza_pagseguro($this->input->post('id_pedido'));

}




/* RECEBER VIA PAGSEGURO */

function pagar_pelo_pagseguro(){


  // $cliente = $this->geral_model->get_dados_cliente_pgtos($_POST['idcliente_titular']);

  $pedido = $this->Loja_model->get_pedido_pagar($this->input->post('id_pedido'));

  $cliente = $pedido->cliente;


  // echo '<pre>';
  // print_r($pedido);
  // exit;



  $this->load->library('Pagseguro');

  $sandbox = false;

  $credenciais = $this->pagseguro->getCredenciais($sandbox);

  $email = $credenciais['email'];

  $token = $credenciais['token'];

  $url_request = $credenciais['url_request'];


  //Campos da requisição pagseguro

  $request = array(

    'email'           => $email,

    'token'           => $token,

    //'receiverEmail'   => $email,

    'currency'        => 'BRL',

    //'redirectURL'     => base_url(array('loja', 'pagamento-finalizado')),

    //'notificationURL' => base_url(array('loja', 'notificacoes-pag'))

  );


  //nome do comprador



  //email

  //$telefone = str_replace('(', $cliente->nome)

  $cpf = str_replace('.','',$cliente->cpf);
  $cpf = str_replace('-','',$cpf);
  $cep = str_replace('-','', $pedido->cep_entrega);

  $telefone = substr($cliente->telefone, 5, 11);
  $telefone = str_replace('-','',$telefone);
  $telefone = str_replace(' ','',$telefone);

  $request['senderName'] = $cliente->nome;
  $request['senderPhone'] = $telefone;
  $request['senderAreaCode'] = substr($cliente->telefone, 1, 2);
  $request['senderCPF'] = $cpf;
  $request['senderEmail'] = $cliente->email;
  $request['shippingType'] = 1;
  $request['shippingAddressStreet'] = $pedido->rua_entrega;
  $request['shippingAddressNumber'] = $pedido->numero_entrega;
  $request['shippingAddressComplement'] = $pedido->complemento_entrega;
  $request['shippingAddressDistrict'] = $pedido->bairro_entrega;
  $request['shippingAddressPostalCode'] = $cep;
  $request['shippingAddressCity'] = $pedido->cidade_entrega;
  $request['shippingAddressState'] = $pedido->estado_entrega;
  $request['shippingAddressCountry'] = 'BRA';
  $request['redirectURL'] = base_url().'meus-pedidos';
  //FRETE
  // $request['shippingCost'] = 7;

  // 

  



// &itemId1=0001
// &itemDescription1=Notebook Prata
// &itemAmount1=24300.00
// &itemQuantity1=1
// &itemWeight1=1000
// &reference=REF1234
// &senderName=Jose Comprador
// &senderEmail=comprador@uol.com.br
// &shippingType=1
// &shippingAddressStreet=Av. Brig. Faria Lima
// &shippingAddressNumber=1384
// &shippingAddressComplement=5o andar
// &shippingAddressDistrict=Jardim Paulistano
// &shippingAddressPostalCode=01452002
// &shippingAddressCity=Sao Paulo
// &shippingAddressState=SP
// &shippingAddressCountry=BRA
// &excludePaymentMethodGroup=CREDIT_CARD,BOLETO
// &excludePaymentMethodName=DEBITO_ITAU,DEBITO_BRADESCO


  // echo '<pre>';
  // print_r($request);
  // exit;

  //$request['shippingAddressPostalCode'] = '01452002';

  

  

   

  

  //dados do produto

  $valor_total = $pedido->valor_total;

  
$request['itemId1'] = 1;

$request['itemDescription1'] = 'Compa em Floricultura Jardins';

$request['itemQuantity1'] = '1';

$request['itemAmount1'] = $pedido->valor_total;

$request['reference'] = $pedido->id;



$response = $this->pagseguro->sendRequestPag($request, $sandbox);

echo json_encode($response);


}


/* FIM RECEBER VIA PAGSEGURO */


}
