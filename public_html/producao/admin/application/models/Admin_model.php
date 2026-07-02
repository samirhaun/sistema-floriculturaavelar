<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

    private $contexto = 'producao';

    public function __construct() {
        parent::__construct();
        $this->ensure_local_schema();
        $this->ensure_auth_schema();
        $this->sync_local_users_context();
    }

    private function ensure_local_schema()
    {
        if(!$this->db->field_exists('desconto_maximo_valor', 'usuarios')){
            $dbforge = $this->load->dbforge($this->db, TRUE);
            $dbforge->add_column('usuarios', array(
                'desconto_maximo_valor' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0,
                    'null' => FALSE
                )
            ));
        }
    }

    public function validar_login($cpf='', $senha='')
    {
        $auth = $this->load->database('auth', TRUE);
        $auth->select('id, email, cpf, nome');
        $auth->from('usuarios');
        $auth->where('cpf', $cpf);
        $auth->where('senha', $senha);
        $auth->where('status', 1);
        $query = $auth->get();

        if($query->num_rows() == 1){
            $auth_user = $query->row();

            if(!$this->usuario_tem_contexto($auth_user->id, $this->contexto)){
                return FALSE;
            }

            $local_user = $this->get_local_user_by_cpf($auth_user->cpf);
            if(!$local_user){
                return FALSE;
            }

            $local_user->auth_id = $auth_user->id;
            $local_user->contextos = $this->get_contextos_usuario($auth_user->id);
            $local_user->contexto_atual = $this->contexto;
            return $local_user;
        }else{
            return FALSE;
        }
    }

    private function ensure_auth_schema()
    {
        $auth = $this->load->database('auth', TRUE);
        if(!$auth->field_exists('desconto_maximo_valor', 'usuarios')){
            $dbforge = $this->load->dbforge($auth, TRUE);
            $dbforge->add_column('usuarios', array(
                'desconto_maximo_valor' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0,
                    'null' => FALSE
                )
            ));
        }
        $auth->query("
            CREATE TABLE IF NOT EXISTS usuarios_contextos (
                id INT NOT NULL AUTO_INCREMENT,
                usuarios_id INT NOT NULL,
                contexto VARCHAR(30) NOT NULL,
                status TINYINT(1) NOT NULL DEFAULT 1,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY usuario_contexto (usuarios_id, contexto)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ");
    }

    private function sync_local_users_context()
    {
        $auth = $this->load->database('auth', TRUE);

        $ja_sincronizado = $auth->select('id')->from('usuarios_contextos')
            ->where('contexto', $this->contexto)
            ->limit(1)
            ->get()->row();

        if($ja_sincronizado){
            return;
        }

        $usuarios = $this->db->select('id, nome, email, cpf, senha, status, desconto_maximo, desconto_maximo_valor')
            ->from('usuarios')
            ->where('cpf IS NOT NULL', null, false)
            ->where('cpf !=', '')
            ->get()->result();

        foreach($usuarios as $usuario){
            $auth_user = $auth->select('id')->from('usuarios')->where('cpf', $usuario->cpf)->get()->row();

            if(!$auth_user){
                $auth->insert('usuarios', array(
                    'nome' => $usuario->nome,
                    'email' => $usuario->email,
                    'cpf' => $usuario->cpf,
                    'senha' => $usuario->senha,
                    'status' => $usuario->status,
                    'desconto_maximo' => isset($usuario->desconto_maximo) ? $usuario->desconto_maximo : 0,
                    'desconto_maximo_valor' => isset($usuario->desconto_maximo_valor) ? $usuario->desconto_maximo_valor : 0
                ));
                $auth_user_id = $auth->insert_id();
            }else{
                $auth_user_id = $auth_user->id;
                $auth->where('id', $auth_user_id)->update('usuarios', array(
                    'nome' => $usuario->nome,
                    'email' => $usuario->email,
                    'cpf' => $usuario->cpf,
                    'status' => $usuario->status,
                    'desconto_maximo' => isset($usuario->desconto_maximo) ? $usuario->desconto_maximo : 0,
                    'desconto_maximo_valor' => isset($usuario->desconto_maximo_valor) ? $usuario->desconto_maximo_valor : 0
                ));
            }

            $this->grant_contexto($auth_user_id, $this->contexto);
        }
    }

    private function get_local_user_by_cpf($cpf)
    {
        return $this->db->select('id, email, cpf, nome')
            ->from('usuarios')
            ->where('cpf', $cpf)
            ->where('status', 1)
            ->get()->row();
    }

    private function usuario_tem_contexto($usuario_id, $contexto)
    {
        $auth = $this->load->database('auth', TRUE);
        return (bool) $auth->select('id')
            ->from('usuarios_contextos')
            ->where('usuarios_id', $usuario_id)
            ->where('contexto', $contexto)
            ->where('status', 1)
            ->get()->row();
    }

    private function grant_contexto($usuario_id, $contexto)
    {
        $auth = $this->load->database('auth', TRUE);
        $exists = $auth->select('id')->from('usuarios_contextos')
            ->where('usuarios_id', $usuario_id)
            ->where('contexto', $contexto)
            ->get()->row();

        if($exists){
            $auth->where('id', $exists->id)->update('usuarios_contextos', array('status' => 1));
        }else{
            $auth->insert('usuarios_contextos', array(
                'usuarios_id' => $usuario_id,
                'contexto' => $contexto,
                'status' => 1
            ));
        }
    }

    private function get_contextos_usuario($usuario_id)
    {
        $auth = $this->load->database('auth', TRUE);
        $rows = $auth->select('contexto')->from('usuarios_contextos')
            ->where('usuarios_id', $usuario_id)
            ->where('status', 1)
            ->get()->result();

        $contextos = array();
        foreach($rows as $row){
            $contextos[] = $row->contexto;
        }
        return $contextos;
    }
}
