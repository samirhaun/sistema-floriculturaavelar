

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->ensure_desconto_valor_column($this->db);
    }

    private function ensure_desconto_valor_column($db)
    {
        if(!$db->field_exists('desconto_maximo_valor', 'usuarios')){
            $dbforge = $this->load->dbforge($db, TRUE);
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

    public function salvar_usuario($dados, $id=NULL)
    {
        if($id){
            $this->db->where('id', $id);
            if($this->db->update('usuarios', $dados))
            {
                return $id;
            }
            else{
                return false;
            }
        }else{
            if($this->db->insert('usuarios', $dados))
            {
                return $this->db->insert_id();
            }
            else
            {
                return false;
            }
        }
    }

    public function get_contextos_usuario($id)
    {
        $usuario = $this->get_usuario($id);
        if(!$usuario || empty($usuario->cpf)){
            return array();
        }

        $auth = $this->load->database('auth', TRUE);
        $this->ensure_desconto_valor_column($auth);
        $cpf_normalizado = preg_replace('/[^0-9]/', '', $usuario->cpf);
        $auth_user = $auth->select('id')->from('usuarios')
            ->where("(cpf = ".$auth->escape($usuario->cpf)." OR REPLACE(REPLACE(cpf, '.', ''), '-', '') = ".$auth->escape($cpf_normalizado).")", null, false)
            ->get()->row();
        if(!$auth_user){
            return array();
        }

        $rows = $auth->select('contexto')->from('usuarios_contextos')
            ->where('usuarios_id', $auth_user->id)
            ->where('status', 1)
            ->get()->result();

        $contextos = array();
        foreach($rows as $row){
            $contextos[] = $row->contexto;
        }
        return $contextos;
    }

    public function salvar_contextos_usuario($id, $contextos)
    {
        $usuario = $this->get_usuario($id);
        if(!$usuario || empty($usuario->cpf)){
            return false;
        }

        $auth = $this->load->database('auth', TRUE);
        $this->ensure_desconto_valor_column($auth);
        $cpf_normalizado = preg_replace('/[^0-9]/', '', $usuario->cpf);
        $auth_user = $auth->select('id')->from('usuarios')
            ->where("(cpf = ".$auth->escape($usuario->cpf)." OR REPLACE(REPLACE(cpf, '.', ''), '-', '') = ".$auth->escape($cpf_normalizado).")", null, false)
            ->get()->row();

        if(!$auth_user){
            $auth->insert('usuarios', array(
                'nome' => $usuario->nome,
                'email' => $usuario->email,
                'cpf' => $usuario->cpf,
                'senha' => isset($usuario->senha) ? $usuario->senha : '',
                'status' => isset($usuario->status) ? $usuario->status : 1,
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
                'status' => isset($usuario->status) ? $usuario->status : 1,
                'desconto_maximo' => isset($usuario->desconto_maximo) ? $usuario->desconto_maximo : 0,
                'desconto_maximo_valor' => isset($usuario->desconto_maximo_valor) ? $usuario->desconto_maximo_valor : 0
            ));
        }

        $contextos_validos = array('producao', 'salinas');
        foreach($contextos_validos as $contexto){
            $exists = $auth->select('id')->from('usuarios_contextos')
                ->where('usuarios_id', $auth_user_id)
                ->where('contexto', $contexto)
                ->get()->row();

            $status = in_array($contexto, $contextos) ? 1 : 0;
            if($exists){
                $auth->where('id', $exists->id)->update('usuarios_contextos', array('status' => $status));
            }else{
                $auth->insert('usuarios_contextos', array(
                    'usuarios_id' => $auth_user_id,
                    'contexto' => $contexto,
                    'status' => $status
                ));
            }
        }

        if(in_array('salinas', $contextos)){
            $this->sincronizar_usuario_salinas($usuario);
        }

        return true;
    }

    private function sincronizar_usuario_salinas($usuario)
    {
        $salinas = $this->load->database('salinas', TRUE);
        $this->ensure_desconto_valor_column($salinas);
        $cpf_normalizado = preg_replace('/[^0-9]/', '', $usuario->cpf);
        $usuario_salinas = $salinas->select('id')->from('usuarios')
            ->where("(cpf = ".$salinas->escape($usuario->cpf)." OR REPLACE(REPLACE(cpf, '.', ''), '-', '') = ".$salinas->escape($cpf_normalizado).")", null, false)
            ->get()->row();

        $dados = array(
            'nome' => $usuario->nome,
            'email' => $usuario->email,
            'cpf' => $usuario->cpf,
            'status' => isset($usuario->status) ? $usuario->status : 1,
            'desconto_maximo' => isset($usuario->desconto_maximo) ? $usuario->desconto_maximo : 0,
            'desconto_maximo_valor' => isset($usuario->desconto_maximo_valor) ? $usuario->desconto_maximo_valor : 0
        );

        if(isset($usuario->senha) && $usuario->senha){
            $dados['senha'] = $usuario->senha;
        }

        if($usuario_salinas){
            $salinas->where('id', $usuario_salinas->id)->update('usuarios', $dados);
            return $usuario_salinas->id;
        }

        $salinas->insert('usuarios', $dados);
        $usuario_salinas_id = $salinas->insert_id();

        $habilidades = $this->db->select('habilidades_id')
            ->from('habilidades_has_usuarios')
            ->where('usuarios_id', $usuario->id)
            ->get()->result();

        foreach($habilidades as $habilidade){
            $salinas->insert('habilidades_has_usuarios', array(
                'usuarios_id' => $usuario_salinas_id,
                'habilidades_id' => $habilidade->habilidades_id
            ));
        }

        return $usuario_salinas_id;
    }



    public function salvar_permissoes($id=NULL)
    {
        
            $dados['modelos'] = 0;
            $dados['tecidos'] = 0;
            $dados['pedidos'] = 0;
            $dados['categorias'] = 0;
            $dados['clientes'] = 0;
            $dados['usuarios'] = 0;
            $dados['usuarios_id'] = $id;
            $dados['produtos'] = 0;
    

            if($this->db->insert('permisoes', $dados))
            {
                return true;
            }
            else
            {
                return false;
            }
        
    }

    public function get_usuarios()
    {
        $this->db->select('usuarios.*');
        $this->db->from('usuarios');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_enderecos($idusuario)
    {
        $this->db->select('enderecos.*');
        $this->db->from('enderecos');
        $this->db->where('usuarios_id', $idusuario);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_usuario($id=null)
    {
        if($id){
            $this->db->select('usuarios.*');
            $this->db->from('usuarios');
            $this->db->where('usuarios.id', $id);
            $query = $this->db->get();
            if($query->num_rows() > 0){
                $data = $query->row();

                //PEGANDO HABILIDADES
                $this->db->select('habilidades_has_usuarios.*');
                $this->db->from('habilidades_has_usuarios');
                $this->db->where('usuarios_id', $data->id);
                $data->habilidades = $this->db->get()->result();

                return $data;
            }else{
                return FALSE;
            }
        }
    }

    public function muda_permissaoOLD($id=null, $tipo=NULL)
    {
        if($id){
            $this->db->select('permisoes.'.$tipo);
            $this->db->from('permisoes');
            $this->db->where('id', $id);
            $query = $this->db->get();

           
            if($query->row()->$tipo == 1) {

                $status = 0;

            }else{
                $status = 1;
            }


            $this->db->set($tipo, $status);
            $this->db->where('id', $id);
            if($this->db->update('permisoes'))
            {
                return true;
            }
            else{
                return false;
            }


        }
    }

    public function muda_permissao($usuario=null, $habilidade=null, $status)
    {

        if($status == 1):

            $this->db->where('usuarios_id', $usuario);
            $this->db->where('habilidades_id', $habilidade);
            return $this->db->delete('habilidades_has_usuarios');
        else:

            return $this->db->insert('habilidades_has_usuarios', array('usuarios_id' =>$usuario, 'habilidades_id' =>$habilidade));

        endif;

    }

    public function muda_permissao_all($status, $usuario)
    {

        if($status == 0):

            $this->db->where('usuarios_id', $usuario);
            return $this->db->delete('habilidades_has_usuarios');
        else:

            $this->db->select('*');
            $this->db->from('habilidades');
            $habilidades = $this->db->get()->result();

            $this->db->where('usuarios_id', $usuario);
            $this->db->delete('habilidades_has_usuarios');

            foreach($habilidades as $habilidade):
                $this->db->insert('habilidades_has_usuarios', array('usuarios_id' =>$usuario, 'habilidades_id' =>$habilidade->id));
            endforeach;

            return true;

        endif;

    }

    public function get_permissoes($id=null)
    {

        if($id){
            $this->db->select('permisoes.*');
            $this->db->from('permisoes');
            $this->db->where('usuarios_id', $id);
            $query = $this->db->get();
            if($query->num_rows() > 0){
                $data = $query->row();                    
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    public function get_habilidades()
    {

            $this->db->select('habilidades.*');
            $this->db->from('habilidades');
            $this->db->order_by('id','asc');
            return $this->db->get()->result();


    }

    public function get_galeria_usuario($id_usuario='')
    {
        $this->db->select('galeria_usuarios.*');
        $this->db->from('galeria_usuarios');
        $this->db->where('galeria_usuarios.usuarios_id', $id_usuario);
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
            $this->db->delete('galeria_usuarios');
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            return FALSE;
        }else{
            return TRUE;
        }
    }

    public function excluir_galeria($id_usuario='')
    {
        $this->db->where('usuarios_id', $id_usuario);
        if($this->db->delete('galeria_usuarios')){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function excluir_usuario($id){
        $this->db->where('id', $id);
        if($this->db->delete('usuarios')){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    function excluir_permissoes($id){
        $this->db->where('usuarios_id', $id);
        if($this->db->delete('permisoes')){
            return TRUE;
        }else{
            return FALSE;
        }
    }




}
