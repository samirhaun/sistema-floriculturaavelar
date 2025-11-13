<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends TEC_Controller {

  public function __construct() {
    parent::__construct();

    $this->load->model('Loja_model');
  }


  function login_registrar(){

    $dados['pagina'] = '';
    $this->montaTela('perfil/login_registrar', $dados);

  }

  public function tela_loginOLD(){
    if(!$this->auth->existe_sessao()){
      $dados = array();

        if ($this->input->get('finalizar')) {
          $dados['finalizar'] = true;
        }

      $this->montaTela('site/conta/login', $dados);
    }else{
      $this->perfil();
    }

  }

  public function cadastro(){

    $this->montaTela('perfil/login_registrar');

  }

  public function perfil(){
    $order = null;
    if ($this->input->get('ordem')) {
      $order=$this->input->get('ordem');
    }

    $pedidos = $this->loja_model->get_meus_pedidos($this->auth->get_id_perfil(), $order);
    $perfil = $this->loja_model->get_usuario($this->auth->get_id_perfil());

    // echo '<pre>';
    // print_r($perfil);
    // exit;
     //  echo "<pre>";
     // var_dump($pedidos);
     // exit;

    redirect(base_url());

    //$this->montaTela('site/conta/perfil', compact('pedidos', 'order', 'perfil'));
  }

  function meus_dados(){

    if (!$this->auth->existe_sessao()) {
      redirect(base_url('login-registrar').'?finalizar=true');
    }

    $dados['perfil'] = $this->loja_model->get_usuario($this->auth->get_id_perfil());

    $this->montaTela('perfil/meus_dados', $dados);

  }

  public function modal_pedido(){
    $pedido = $this->loja_model->get_pedido($this->input->post('id'));
    $produtos = $this->loja_model->get_meus_pedidos_produtos($pedido->id);
  
    $this->load->view('pedido_modal', compact('pedido', 'produtos'));
  }


  public function salvar_cadastro(){

    // echo '<pre>';
    // print_r($_POST);
    // exit;

    if($this->input->post('finalizar')){
      $_GET['finalizar'] = true;
    }


    $_GET['cadastrando'] = true;


    //REGRAS PARA VALIDAÇÃO
    $regras = [
        [
            'field' => 'nome',
            'label' => 'nome',
            'rules' => [
                'required',
                'trim'
            ],
        ],
        [
            'field' => 'email',
            'label' => 'email',
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
            'field' => 'senha',
            'label' => 'senha',
            'rules' => [
                'required',
                'trim'
            ],
        ],
        [
            'field' => 'confirma_senha',
            'label' => 'confirma_senha',
            'rules' => [
                'required',
                'trim'
            ],
        ],
    ];


    $this->form_validation->set_rules($regras);

    if (FALSE === $this->form_validation->run()) {

          $this->cadastro();

          $this->output->append_output("<script>
            swal('Por favor, preencha os campos obrigatórios.');
            </script>");
   

    }else{
      
    


    if($this->loja_model->verifica_email_existente($this->input->post('email'))){


      $this->cadastro();
      $url = base_url('cadastro');
      $this->output->append_output("<script>
        swal('Email já cadastrado, faça login no formulário ao lado, ou, caso esqueceu sua senha, clique no link Esqueceu Senha.');
        </script>");

    }else{

            //  $nome = explode(" ", $this->input->post('nome'));
            // if(count($nome) > 1){
            

            // }else{
            //   $this->cadastro();
            //   $url = base_url('cadastro');
            //   $this->output->append_output("<script>
            //     swal('Nome inválido');
            //     </script>");
            //   return 0;
            // }
            

        if($this->input->post('senha') == $this->input->post('confirma_senha')){

          $db_array['nome'] = $this->input->post('nome');
          $db_array['email'] = $this->input->post('email');
          $db_array['telefone'] = $this->input->post('telefone');
          $db_array['senha'] = md5($this->input->post('senha'));


          if($id_cli = $this->loja_model->salvar_cadastro($db_array)){

            if($perfil = $this->loja_model->login($db_array['email'], $db_array['senha'])){
              

              $this->auth->criar_sessao($perfil);

              if($this->input->post('finalizar')){
                redirect('meus-dados?finalizar=true');
              }else{
                redirect(base_url().'?cadastro_concluido=true');
              }
              

              


            }
          }
        }else{

          $this->cadastro();

          $this->output->append_output("<script>
            swal('As senhas não conferem!');
            </script>");
        }


      
    }




  }



  }



  public function login()
  {

    // echo '<pre>';
    // print_r($_POST);
    // exit;
    

    $finalizar = null;
    if ($this->input->post('finalizar')) {
      $finalizar = true;
    }
    
   if($this->input->post()){
    if( $perfil = $this->loja_model->login( $this->input->post('email'), md5( $this->input->post('password') ) ) ){
      $this->auth->criar_sessao($perfil);
      if ($finalizar) {
        redirect('finalizar-pedido');   
     }else{
      $this->perfil();
      $url = base_url('perfil');        
      $this->output->append_output('<script>window.history.replaceState("", "IEA", "'. $url .'")</script>');
      }
    }else{

      $_GET['error_login'] = 'true';
      
      if ($finalizar) {
          $_GET['finalizar'] = 'true';
          $this->cadastro();
     }else{
        $this->cadastro();     
      }
     
      //$this->output->append_output('<script>window.history.replaceState("", "IEA", "'. $url .'"); swal("Erro ao fazer login", "E-mail ou senha incorretos")</script>');
    }
  }
}


public function login_modal()
{

 if($this->input->post()){
  if( $perfil = $this->loja_model->login( $this->input->post('email'), md5( $this->input->post('senha') ) ) ){
    $this->auth->criar_sessao($perfil);
    echo json_encode(true);
  }else{
    echo json_encode(false);
  }
}
}




public function editar_perfil(){
  $dados['perfil'] = $this->loja_model->get_usuario($this->auth->get_id_perfil());

  $this->montaTela('site/conta/editar_meus_dados', $dados);
}




public function salvar_editar_perfil(){

 if($this->input->post('finalizar')){
   $finalizar = 1;
 }else{
   $finalizar = 0;
 }



  if($this->input->post()){
    $db_array['nome'] = $this->input->post('nome');
    $db_array['numero'] = $this->input->post('numero');
    //$db_array['email'] = $this->input->post('email');
    $db_array['telefone'] = $this->input->post('telefone');
    $db_array['cpf'] = $this->input->post('cpf');
    $db_array['cep'] = $this->input->post('cep');
    $db_array['rua'] = $this->input->post('rua');
    $db_array['bairro'] = $this->input->post('bairro');
    $db_array['cidade'] = $this->input->post('cidade');
    $db_array['estado'] = $this->input->post('estado');
    $db_array['complemento'] = $this->input->post('complemento');


    if($id_cli = $this->loja_model->salvar_cadastro($db_array, $this->auth->get_id_perfil())){
      $this->auth->atualizar_sessao();
      unset($_POST);

      if($finalizar == 1){
        redirect('carrinho');
      }else{
          $this->meus_dados();
          $url = base_url('editar-perfil');
          $this->output->append_output("<script>
            swal('Dados atualizados com sucesso!');
            </script>");
      }






    }
  }
}



public function recuperar_senha()
{
  $this->montaTela('site/conta/esqueci-senha');
}



public function nova_senha(){

  if ($_POST['email']) {
    $cria_token = $this->loja_model->criar_token_perfil($_POST['email']);
    $this->load->library('email');
    $this->email->set_newline("\r\n");
    $config['charset'] = 'utf-8';
    $config['mailtype'] = 'html';
    $this->email->initialize($config);

    $mensagem = base_url().'atualizar-senha?email='.$_POST['email'].'&token='.$cria_token;

    $this->email->from('contato@institutoespiritadoamor.com', 'IEA');
    $this->email->to($_POST['email']);
    $this->email->reply_to($_POST['email'], 'Contato pelo Site');

    $this->email->subject('Recuperar senha');


    $nome = $this->loja_model->get_nome_by_email($_POST['email']);
    $data = array(
      'mensagem' => $mensagem,
      'nome' => $nome->nome
    );

    $this->email->message($this->load->view('site/mensagem_email_senha', $data, TRUE));


    $return = $this->email->send();
    if($return){
      $this->recuperar_senha();
      $url = base_url('recuperar-senha');
      $this->output->append_output('<script>window.history.replaceState("", "IEA", "'. $url .'"); swal("Recuperação de Senha", "Confira sua caixa de email", "success")</script>');
    }
  }
}


function atualizar_senha(){
  $token = $_GET['token'];
  if ( ! empty($token) && is_string($token) && $this->loja_model->verifica_token($_GET['email'],$token)){
    $dados['email'] = $_GET['email'];
    $dados['token'] = $token;
    $this->montaTela('site/conta/atualizar-senha',$dados);
  }
  else
  {
    redirect('recuperar-senha?return=bad_token');
  }
}





function salvar_atualizacao_senha(){

  if($_POST['senha'] != $_POST['re_senha']){

   $url =  base_url('atualizar-senha').'?email='.$this->input->post('email_original').'&token='.$this->input->post('token').'&error=true';
   $_GET['email'] = $this->input->post('email_original');
   $_GET['token'] = $this->input->post('token');
   $this->atualizar_senha();
   $this->output->append_output('<script>window.history.replaceState("", "Loja IEA", "'. $url .'"); swal("Recuperação de Senha", "Senhas não conferem");</script>');

 }else{

  unset($_POST['re_senha']);

  if($this->loja_model->atualizar_senha_perfil($_POST)){

    $this->tela_login();
    $url = base_url('login');
   $this->output->append_output('<script>window.history.replaceState("", "Loja IEA", "'. $url .'"); swal("Recuperação de Senha", "Senha atualizada com sucesso!");</script>');

  }
}


}



public function sair()
{
  $this->auth->destruir_sessao();
  delete_cookie("login");
  redirect(base_url());
}



}
