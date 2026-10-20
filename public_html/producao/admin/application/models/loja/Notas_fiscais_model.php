<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notas_fiscais_model extends CI_Model {

    private $formas_nao_dinheiro = array(2, 3, 4, 5, 6, 7, 8, 9);

    /**
     * O pedido so entra na fila se tiver uma parcela com forma diferente de
     * dinheiro. O documento fiscal, quando emitido, representa o pedido
     * inteiro; as parcelas sao preservadas na tabela de pagamentos.
     */
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

    public function pedido_pronto_para_emitir($pedido_id)
    {
        $contas = $this->db->select('forma_pgto, valor, status')
            ->from('contas_receber')->where('pedidos_id', (int) $pedido_id)
            ->get()->result();

        if (empty($contas)) return false;
        $tem_pagamento_nao_dinheiro = false;
        foreach ($contas as $conta) {
            if ((float) $conta->valor <= 0 || (int) $conta->status !== 1) return false;
            if (in_array((int) $conta->forma_pgto, $this->formas_nao_dinheiro, true)) {
                $tem_pagamento_nao_dinheiro = true;
            }
        }
        return $tem_pagamento_nao_dinheiro;
    }

    public function criar_pendente($pedido_id, $ambiente)
    {
        $pedido_id = (int) $pedido_id;
        if (!$this->pedido_elegivel($pedido_id)) {
            return false;
        }

        $existente = $this->db->where('pedidos_id', $pedido_id)
            ->where('tipo_documento', 'nfce')
            ->get('notas_fiscais')
            ->row();

        if ($existente) {
            return $existente->id;
        }

        $agora = date('Y-m-d H:i:s');
        $this->db->insert('notas_fiscais', array(
            'pedidos_id' => $pedido_id,
            'tipo_documento' => 'nfce',
            'ambiente' => $ambiente,
            'referencia' => 'pedido'.$pedido_id.'nfce',
            'status' => 'pendente',
            'created_at' => $agora,
            'updated_at' => $agora,
        ));

        return $this->db->insert_id();
    }

    public function buscar_por_pedido($pedido_id)
    {
        return $this->db->where('pedidos_id', (int) $pedido_id)
            ->where('tipo_documento', 'nfce')->get('notas_fiscais')->row();
    }

    public function atualizar($nota_id, array $dados)
    {
        $dados['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', (int) $nota_id)->update('notas_fiscais', $dados);
    }

    public function salvar_detalhes($nota_id, array $pagamentos, array $itens)
    {
        $this->db->trans_start();
        $this->db->where('notas_fiscais_id', (int) $nota_id)->delete('notas_fiscais_pagamentos');
        $this->db->where('notas_fiscais_id', (int) $nota_id)->delete('notas_fiscais_itens');
        foreach ($pagamentos as $pagamento) {
            $pagamento['notas_fiscais_id'] = (int) $nota_id;
            $pagamento['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('notas_fiscais_pagamentos', $pagamento);
        }
        foreach ($itens as $item) {
            $item['notas_fiscais_id'] = (int) $nota_id;
            $item['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('notas_fiscais_itens', $item);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function listar($inicio = null, $fim = null, $status = null)
    {
        // A nota tem seu proprio valor_total desde a migration 012; o do pedido
        // vem com apelido para nao colidir no resultado.
        $this->db->select('notas_fiscais.*, pedidos.valor_total AS valor_pedido, clientes.nome AS cliente_nome');
        $this->db->from('notas_fiscais');
        $this->db->join('pedidos', 'pedidos.id = notas_fiscais.pedidos_id');
        $this->db->join('clientes', 'clientes.id = pedidos.clientes_id', 'left');
        $this->db->where('notas_fiscais.tipo_documento', 'nfce');
        if ($inicio) $this->db->where('notas_fiscais.created_at >=', $inicio.' 00:00:00');
        if ($fim) $this->db->where('notas_fiscais.created_at <=', $fim.' 23:59:59');
        if ($status) $this->db->where('notas_fiscais.status', $status);
        $this->db->order_by('notas_fiscais.id', 'DESC');
        return $this->db->get()->result();
    }
}
