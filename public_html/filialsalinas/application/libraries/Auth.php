<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

    var $CI;
    var $contexto = 'salinas';

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->verifica_sessao();
    }

    public function verifica_sessao()
    {
        $_login_routes = array(
            'Admin/index',
            'Admin/login',
            'Admin/validar_login'
        );

        if($this->CI->session->userdata('usuario')){
            if(!$this->sincroniza_usuario_contexto()){
                $this->CI->session->sess_destroy();
                redirect(config_item('base_url'),'refresh');
            }
        }else{
            $base_url = config_item('base_url');
            $uri = $this->CI->router->class.'/'.$this->CI->router->method;
            if ( ! in_array($uri, $_login_routes))
            {
                redirect($base_url,'refresh');
            }
        }
    }

    private function sincroniza_usuario_contexto()
    {
        $usuario = $this->CI->session->userdata('usuario');
        $contextos = isset($usuario->contextos) ? $usuario->contextos : array();

        if(!in_array($this->contexto, $contextos)){
            return false;
        }

        if(isset($usuario->contexto_atual) && $usuario->contexto_atual == $this->contexto){
            return true;
        }

        $local_user = $this->CI->db->select('id, email, cpf, nome')
            ->from('usuarios')
            ->where('cpf', $usuario->cpf)
            ->where('status', 1)
            ->get()->row();

        if(!$local_user){
            return false;
        }

        $local_user->auth_id = isset($usuario->auth_id) ? $usuario->auth_id : $usuario->id;
        $local_user->contextos = $contextos;
        $local_user->contexto_atual = $this->contexto;
        $this->CI->session->set_userdata('usuario', $local_user);

        return true;
    }

    public function get_id_usuario()
    {
        return $this->CI->session->userdata('usuario')->id;
    }

    public function get_email_usuario()
    {
        return $this->CI->session->userdata('usuario')->email;
    }

    public function get_nome_usuario()
    {
        return $this->CI->session->userdata('usuario')->nome;
    }
    
}
