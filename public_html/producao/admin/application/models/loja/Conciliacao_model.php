<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Conciliacao bancaria: compara, dia a dia, o que o sistema espera receber
 * (PIX e cartao liquido) com os creditos do extrato Sicoob importado.
 */
class Conciliacao_model extends CI_Model {

    const FORMA_PIX = 9;

    /** Substitui o extrato do periodo pelas transacoes recebidas da API. */
    public function salvar_extrato($conta, $inicio, $fim, $transacoes, $classificador)
    {
        $agora = date('Y-m-d H:i:s');
        $this->db->trans_start();
        $this->db->where('conta', $conta)->where('data >=', $inicio)->where('data <=', $fim)->delete('extrato_bancario');
        $total = 0;
        foreach ($transacoes as $t) {
            if ($t['data'] < $inicio || $t['data'] > $fim) {
                continue;
            }
            $t['conta'] = $conta;
            $t['banco'] = 'sicoob';
            $t['categoria'] = ($t['tipo'] === 'C') ? $classificador($t['descricao'], $t['complemento']) : 'outros';
            $t['importado_em'] = $agora;
            $this->db->insert('extrato_bancario', $t);
            $total++;
        }
        $this->db->trans_complete();
        return $total;
    }

    public function ultima_importacao()
    {
        $row = $this->db->select_max('importado_em')->get('extrato_bancario')->row();
        return $row ? $row->importado_em : null;
    }

    public function extrato($inicio, $fim)
    {
        return $this->db->from('extrato_bancario')
            ->where('data >=', $inicio)->where('data <=', $fim)
            ->order_by('data, id')->get()->result();
    }

    /** Resumo diario: esperado pelo sistema x creditado no banco. */
    public function resumo($inicio, $fim)
    {
        $dias = array();
        $garantir = function ($d) use (&$dias) {
            if (!isset($dias[$d])) {
                $dias[$d] = array(
                    'pix_esperado' => 0, 'pix_banco' => 0,
                    'cartao_bruto' => 0, 'cartao_taxas' => 0, 'cartao_esperado' => 0, 'cartao_banco' => 0,
                    'outros_banco' => 0,
                );
            }
        };

        // PIX recebido, pela data do pagamento
        $pix = $this->db->query(
            "SELECT cr.data_pago AS dia, SUM(cr.valor) AS total
               FROM contas_receber cr JOIN pedidos p ON p.id = cr.pedidos_id
              WHERE cr.forma_pgto = ? AND cr.status = 1 AND p.status_pedido NOT IN (4,5)
                AND cr.data_pago BETWEEN ? AND ?
              GROUP BY cr.data_pago", array(self::FORMA_PIX, $inicio, $fim))->result();
        foreach ($pix as $r) { $garantir($r->dia); $dias[$r->dia]['pix_esperado'] = (float) $r->total; }

        // Cartao bruto pela data de liquidacao (D+1)
        $cartao = $this->db->query(
            "SELECT COALESCE(cr.data_liquidacao, cr.data_pago) AS dia, SUM(cr.valor) AS total
               FROM contas_receber cr JOIN pedidos p ON p.id = cr.pedidos_id
              WHERE cr.forma_pgto IN (2,3,4,5,6) AND cr.status = 1 AND p.status_pedido NOT IN (4,5)
                AND COALESCE(cr.data_liquidacao, cr.data_pago) BETWEEN ? AND ?
              GROUP BY COALESCE(cr.data_liquidacao, cr.data_pago)", array($inicio, $fim))->result();
        foreach ($cartao as $r) { $garantir($r->dia); $dias[$r->dia]['cartao_bruto'] = (float) $r->total; }

        // Taxas de maquininha e antecipacao abatidas na mesma liquidacao
        $taxas = $this->db->query(
            "SELECT data_vencimento AS dia, SUM(valor) AS total FROM contas_pagar
              WHERE origem IN ('taxa_maquininha','taxa_antecipacao') AND data_vencimento BETWEEN ? AND ?
              GROUP BY data_vencimento", array($inicio, $fim))->result();
        foreach ($taxas as $r) { $garantir($r->dia); $dias[$r->dia]['cartao_taxas'] = (float) $r->total; }

        $banco = $this->db->query(
            "SELECT data AS dia, categoria, SUM(valor) AS total FROM extrato_bancario
              WHERE tipo = 'C' AND data BETWEEN ? AND ? GROUP BY data, categoria", array($inicio, $fim))->result();
        foreach ($banco as $r) {
            $garantir($r->dia);
            $dias[$r->dia][$r->categoria.'_banco'] += (float) $r->total;
        }

        foreach ($dias as $d => &$v) {
            $v['cartao_esperado'] = round($v['cartao_bruto'] - $v['cartao_taxas'], 2);
            $v['pix_dif'] = round($v['pix_banco'] - $v['pix_esperado'], 2);
            $v['cartao_dif'] = round($v['cartao_banco'] - $v['cartao_esperado'], 2);
        }
        unset($v);
        ksort($dias);
        return $dias;
    }
}
