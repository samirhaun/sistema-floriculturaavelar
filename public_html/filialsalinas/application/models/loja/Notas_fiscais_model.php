<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notas_fiscais_model extends CI_Model {

    private $formas_nao_dinheiro = array(2, 3, 4, 5, 6, 7, 8, 9);

    public function pedido_elegivel($pedido_id)
    {
        $contas = $this->db->select('id, forma_pgto, valor')
            ->from('contas_receber')
            ->where('pedidos_id', (int) $pedido_id)
            ->get()->result();

        foreach ($contas as $conta) {
            if (in_array((int) $conta->forma_pgto, $this->formas_nao_dinheiro, true)
                && (float) $conta->valor > 0) {
                return true;
            }
        }

        return false;
    }

    public function criar_pendente($pedido_id, $ambiente)
    {
        $pedido_id = (int) $pedido_id;
        if (!$this->pedido_elegivel($pedido_id)) {
            return false;
        }

        $existente = $this->db->where('pedidos_id', $pedido_id)
            ->where('tipo_documento', 'nfe')
            ->get('notas_fiscais')
            ->row();
        if ($existente) {
            return $existente->id;
        }

        $agora = date('Y-m-d H:i:s');
        $this->db->insert('notas_fiscais', array(
            'pedidos_id' => $pedido_id,
            'tipo_documento' => 'nfe',
            'ambiente' => $ambiente,
            'referencia' => 'pedido'.$pedido_id.'nfe',
            'status' => 'pendente',
            'created_at' => $agora,
            'updated_at' => $agora,
        ));

        return $this->db->insert_id();
    }
}
