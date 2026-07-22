<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taxas_cartao_model extends CI_Model {

    public function lote_da_receita($receita)
    {
        $forma_pgto = (int) $this->campo($receita, 'forma_pgto');
        if (!in_array($forma_pgto, array(3, 4, 5, 6))) return null;

        $maquininha_id = (int) $this->campo($receita, 'maquininha_cartao_id');
        $taxa_id = (int) $this->campo($receita, 'maquininha_taxa_id');
        if (!$maquininha_id && $taxa_id) {
            $taxa = $this->db->select('maquininha_cartao_id')->from('maquininhas_cartao_taxas')->where('id', $taxa_id)->get()->row();
            $maquininha_id = $taxa ? (int) $taxa->maquininha_cartao_id : 0;
        }

        $bandeira = $this->normalizar_bandeira($this->campo($receita, 'bandeira_cartao'));
        $data_pagamento = $this->campo($receita, 'data_liquidacao');
        if (!$data_pagamento) $data_pagamento = $this->campo($receita, 'data_pago');
        if (!$data_pagamento) $data_pagamento = $this->campo($receita, 'data_vencimento');
        // A receita ja foi agendada para D+1 no cadastro do pedido. A
        // antecipacao e descontada nessa mesma liquidacao, sem outro D+1.
        $data_lote = $data_pagamento ?: null;
        if (!$maquininha_id || !$bandeira || !$data_lote) return null;

        return array('data_lote' => $data_lote, 'data_origem_lote' => $this->data_origem_lote($receita, $data_lote), 'maquininha_cartao_id' => $maquininha_id, 'bandeira_cartao' => $bandeira);
    }

    public function lotes_do_pedido($pedido_id)
    {
        $receitas = $this->db->select('forma_pgto, data_vencimento, data_pago, data_liquidacao, maquininha_cartao_id, maquininha_taxa_id, bandeira_cartao')->from('contas_receber')->where('pedidos_id', $pedido_id)->get()->result();
        $lotes = array();
        foreach ($receitas as $receita) {
            $lote = $this->lote_da_receita($receita);
            if ($lote) $lotes[] = $lote;
        }
        return $lotes;
    }

    public function recalcular_lotes($lotes)
    {
        $unicos = array();
        foreach ((array) $lotes as $lote) if ($lote) $unicos[$this->chave_lote($lote)] = $lote;
        foreach ($unicos as $lote) $this->recalcular_lote($lote);
    }

    public function recalcular_lotes_por_liquidacao($datas)
    {
        $lotes = array();
        foreach (array_unique(array_filter((array) $datas)) as $data) {
            $receitas = $this->db->select('forma_pgto, data_vencimento, data_pago, data_liquidacao, maquininha_cartao_id, maquininha_taxa_id, bandeira_cartao')
                ->from('contas_receber')->where_in('forma_pgto', array(3, 4, 5, 6))
                ->where('COALESCE(data_liquidacao, data_pago, data_vencimento) = '.$this->db->escape($data), null, false)->get()->result();
            foreach ($receitas as $receita) {
                $lote = $this->lote_da_receita($receita);
                if ($lote) $lotes[] = $lote;
            }
        }
        $this->recalcular_lotes($lotes);
    }

    public function sincronizar_status_pedido($pedido_id)
    {
        $pedido = $this->db->select('id, status_pedido')->from('pedidos')->where('id', $pedido_id)->get()->row();
        if (!$pedido) return false;

        $receitas = $this->db->select('*')->from('contas_receber')->where('pedidos_id', $pedido_id)->get()->result();
        $lotes = array(); $ids = array();
        foreach ($receitas as $receita) {
            $ids[] = (int) $receita->id;
            $lote = $this->lote_da_receita($receita);
            if ($lote) $lotes[] = $lote;
        }

        if (in_array((int) $pedido->status_pedido, array(4, 5))) {
            if (!empty($ids)) {
                $this->db->where_in('contas_receber_id', $ids)->where_in('origem', array('taxa_maquininha', 'taxa_antecipacao'))->delete('contas_pagar');
                $this->db->where_in('id', $ids)->update('contas_receber', array('taxa_contas_pagar_id' => null));
            }
        } else {
            foreach ($receitas as $receita) $this->garantir_taxa_maquininha($receita, $pedido_id);
        }
        $this->recalcular_lotes($lotes);
        return true;
    }

    public function excluir_financeiro_pedido($pedido_id)
    {
        $lotes = $this->lotes_do_pedido($pedido_id);
        $receitas = $this->db->select('id')->from('contas_receber')->where('pedidos_id', $pedido_id)->get()->result();
        $ids = array();
        foreach ($receitas as $receita) $ids[] = (int) $receita->id;
        if (!empty($ids)) {
            $this->db->where_in('contas_receber_id', $ids)->where_in('origem', array('taxa_maquininha', 'taxa_antecipacao'))->delete('contas_pagar');
        }
        $this->db->where('pedidos_id', $pedido_id)->delete('contas_receber');
        $this->recalcular_lotes($lotes);
    }

    public function normalizar_bandeira($bandeira)
    {
        $bandeira = trim((string) $bandeira);
        $normalizada = strtolower($bandeira);
        if ($normalizada === 'master' || $normalizada === 'master card' || $normalizada === 'mastercard') return 'Mastercard';
        if ($normalizada === 'visa' || $normalizada === 'visa electron') return 'Visa';
        if ($normalizada === 'elo') return 'Elo';
        return $bandeira;
    }

    private function garantir_taxa_maquininha($receita, $pedido_id)
    {
        $forma_pgto = (int) $receita->forma_pgto;
        if (!in_array($forma_pgto, array(2, 3, 4, 5, 6))) return;
        $taxa_bandeira = null;
        if (!empty($receita->maquininha_taxa_id)) {
            $taxa_bandeira = $this->db->select('*')->from('maquininhas_cartao_taxas')->where('id', $receita->maquininha_taxa_id)->where('ativo', 1)->where('deleted_at IS NULL', null, false)->get()->row();
        }
        $maquininha_id = $taxa_bandeira ? (int) $taxa_bandeira->maquininha_cartao_id : (int) $receita->maquininha_cartao_id;
        if (!$maquininha_id) return;
        $maquininha = $this->db->select('*')->from('maquininhas_cartao')->where('id', $maquininha_id)->where('ativo', 1)->where('deleted_at IS NULL', null, false)->get()->row();
        if (!$maquininha) return;
        $campo = $this->campo_taxa_maquininha($forma_pgto); $origem_taxa = $taxa_bandeira ?: $maquininha;
        $percentual = isset($origem_taxa->{$campo}) ? (float) $origem_taxa->{$campo} : 0;
        $valor = round(((float) $receita->valor * $percentual) / 100, 2);
        if ($valor <= 0) return;
        $existente = !empty($receita->taxa_contas_pagar_id) ? $this->db->select('id')->from('contas_pagar')->where('id', $receita->taxa_contas_pagar_id)->where('origem', 'taxa_maquininha')->get()->row() : null;
        if (!$existente) $existente = $this->db->select('id')->from('contas_pagar')->where('contas_receber_id', $receita->id)->where('origem', 'taxa_maquininha')->get()->row();
        $data = !empty($receita->data_liquidacao) ? $receita->data_liquidacao : (!empty($receita->data_pago) ? $receita->data_pago : $receita->data_vencimento);
        $conta = array(
            'descricao' => 'Taxa maquininha '.$maquininha->nome.($taxa_bandeira ? ' - '.$taxa_bandeira->grupo_bandeira : '').' - pedido #'.$pedido_id,
            'data_vencimento' => $data, 'valor' => $valor, 'status' => ((int) $receita->status === 1) ? 1 : 0,
            'data_pago' => ((int) $receita->status === 1) ? $data : null, 'plano_contas_id' => $maquininha->plano_contas_taxa_id,
            'fornecedores_id' => $maquininha->fornecedor_id, 'forma_pgto' => $forma_pgto, 'contas_receber_id' => $receita->id,
            'maquininha_cartao_id' => $maquininha->id, 'bandeira_cartao' => $this->normalizar_bandeira($receita->bandeira_cartao),
            'base_calculo' => $receita->valor, 'percentual_taxa' => $percentual, 'origem' => 'taxa_maquininha'
        );
        if ($existente) { $this->db->where('id', $existente->id)->update('contas_pagar', $conta); $taxa_id = $existente->id; }
        else { $this->db->insert('contas_pagar', $conta); $taxa_id = $this->db->insert_id(); }
        $this->db->where('id', $receita->id)->update('contas_receber', array('taxa_contas_pagar_id' => $taxa_id));
    }

    private function campo_taxa_maquininha($forma_pgto)
    {
        $campos = array(2 => 'taxa_debito', 3 => 'taxa_credito_1x', 4 => 'taxa_credito_2x', 5 => 'taxa_credito_3x', 6 => 'taxa_credito_4x');
        return isset($campos[(int) $forma_pgto]) ? $campos[(int) $forma_pgto] : 'taxa_debito';
    }

    private function recalcular_lote($lote)
    {
        $chave = $this->chave_lote($lote);
        $chave_legada = $this->chave_lote_legada($lote);
        if ($chave_legada !== $chave) $this->db->where('chave_agrupamento', $chave_legada)->delete('contas_pagar');
        $maquininha = $this->db->select('*')->from('maquininhas_cartao')->where('id', $lote['maquininha_cartao_id'])->get()->row();
        $taxa_grupo = $this->db->select('taxa_antecipacao')->from('maquininhas_cartao_taxas')->where('maquininha_cartao_id', $lote['maquininha_cartao_id'])->where('grupo_bandeira', $lote['bandeira_cartao'])->where('ativo', 1)->where('deleted_at IS NULL', null, false)->get()->row();
        $percentual_nominal = $taxa_grupo ? (float) $taxa_grupo->taxa_antecipacao : ($maquininha ? (float) $maquininha->taxa_antecipacao : 0);

        $this->db->select('cr.valor, cr.data_vencimento, cr.data_pago, cr.data_liquidacao, COALESCE(tm.valor, 0) AS taxa_maquininha', false)->from('contas_receber cr');
        $this->db->join('contas_pagar tm', 'tm.id = cr.taxa_contas_pagar_id AND tm.origem = "taxa_maquininha"', 'left');
        $this->db->join('pedidos p', 'p.id = cr.pedidos_id');
        $this->db->where_in('cr.forma_pgto', array(3, 4, 5, 6))->where('cr.status', 1)->where_not_in('p.status_pedido', array(4, 5))->where('cr.maquininha_cartao_id', $lote['maquininha_cartao_id'])->where('cr.bandeira_cartao', $lote['bandeira_cartao']);
        // A antecipacao acompanha a liquidacao da receita.
        $data_inicio = date('Y-m-d', strtotime($lote['data_lote'].' -7 days'));
        $this->db->where('COALESCE(cr.data_liquidacao, cr.data_pago, cr.data_vencimento) >= '.$this->db->escape($data_inicio), null, false);
        $this->db->where('COALESCE(cr.data_liquidacao, cr.data_pago, cr.data_vencimento) <= '.$this->db->escape($lote['data_lote']), null, false);
        $receitas_candidatas = $this->db->get()->result();
        $base = 0; $valor_taxa_bruto = 0;
        foreach ($receitas_candidatas as $receita) {
            $data_pagamento = !empty($receita->data_liquidacao) ? $receita->data_liquidacao : (!empty($receita->data_pago) ? $receita->data_pago : $receita->data_vencimento);
            if ($data_pagamento !== $lote['data_lote']) continue;
            if ($this->data_origem_lote($receita, $data_pagamento) !== $lote['data_origem_lote']) continue;
            $base_receita = round((float) $receita->valor - (float) $receita->taxa_maquininha, 2);
            $dias = $this->dias_antecipados($this->data_base_antecipacao($receita, $data_pagamento), $lote['data_lote']);
            $base += $base_receita;
            // A SIPAG arredonda cada transacao antes de somar o lote.
            $valor_taxa_bruto += round(($base_receita * $percentual_nominal * $dias) / 3000, 2);
        }
        $base = round($base, 2); $valor_taxa = round($valor_taxa_bruto, 2);
        $percentual_efetivo = $base > 0 ? round(($valor_taxa / $base) * 100, 4) : 0;
        $existente = $this->db->select('id')->from('contas_pagar')->where('chave_agrupamento', $chave)->get()->row();

        if (!$maquininha || $percentual_nominal <= 0 || $base <= 0 || $valor_taxa <= 0) {
            if ($existente) $this->db->where('id', $existente->id)->delete('contas_pagar');
            return;
        }

        $conta_pagar = array(
            'descricao' => 'Taxa antecipacao '.$maquininha->nome.' - '.$lote['bandeira_cartao'].' - venda '.date('d/m/Y', strtotime($lote['data_origem_lote'])).' / lote '.date('d/m/Y', strtotime($lote['data_lote'])),
            'data_vencimento' => $lote['data_lote'], 'valor' => $valor_taxa, 'status' => 1, 'data_pago' => $lote['data_lote'],
            'plano_contas_id' => $maquininha->plano_contas_taxa_id, 'fornecedores_id' => $maquininha->fornecedor_id,
            // O lote pode reunir parcelas diferentes; usa a categoria de credito do fluxo.
            'forma_pgto' => 3, 'contas_receber_id' => null, 'maquininha_cartao_id' => $lote['maquininha_cartao_id'],
            'bandeira_cartao' => $lote['bandeira_cartao'], 'base_calculo' => $base, 'percentual_taxa' => $percentual_efetivo,
            'origem' => 'taxa_antecipacao', 'chave_agrupamento' => $chave,
        );
        if ($existente) $this->db->where('id', $existente->id)->update('contas_pagar', $conta_pagar);
        else $this->db->insert('contas_pagar', $conta_pagar);
    }

    private function dias_antecipados($data_venda, $data_liquidacao)
    {
        if (!$data_venda || !$data_liquidacao) return 0;
        $vencimento = new DateTime($data_venda); $vencimento->modify('+30 days');
        $this->ajustar_para_dia_util($vencimento); $liquidacao = new DateTime($data_liquidacao);
        return $vencimento > $liquidacao ? (int) $liquidacao->diff($vencimento)->format('%a') : 0;
    }

    private function ajustar_para_dia_util(DateTime $data)
    {
        while ((int) $data->format('N') >= 6 || in_array($data->format('Y-m-d'), $this->feriados_bancarios((int) $data->format('Y')))) $data->modify('+1 day');
    }

    private function proximo_dia_util($data)
    {
        $proximo = new DateTime($data);
        $proximo->modify('+1 day');
        $this->ajustar_para_dia_util($proximo);
        return $proximo->format('Y-m-d');
    }

    private function feriados_bancarios($ano)
    {
        $pascoa = $this->data_pascoa($ano);
        $datas = array($ano.'-01-01', $ano.'-04-21', $ano.'-05-01', $ano.'-09-07', $ano.'-10-12', $ano.'-11-02', $ano.'-11-15', $ano.'-11-20', $ano.'-12-25');
        foreach (array(-48, -47, -2, 60) as $dias) { $data = clone $pascoa; $data->modify(($dias >= 0 ? '+' : '').$dias.' days'); $datas[] = $data->format('Y-m-d'); }
        return $datas;
    }

    private function data_pascoa($ano)
    {
        $a=$ano%19; $b=(int)floor($ano/100); $c=$ano%100; $d=(int)floor($b/4); $e=$b%4;
        $f=(int)floor(($b+8)/25); $g=(int)floor(($b-$f+1)/3); $h=(19*$a+$b-$d-$g+15)%30;
        $i=(int)floor($c/4); $k=$c%4; $l=(32+2*$e+2*$i-$h-$k)%7; $m=(int)floor(($a+11*$h+22*$l)/451);
        $mes=(int)floor(($h+$l-7*$m+114)/31); $dia=(($h+$l-7*$m+114)%31)+1;
        return new DateTime(sprintf('%04d-%02d-%02d',$ano,$mes,$dia));
    }

    private function chave_lote($lote)
    {
        $bandeira = preg_replace('/[^a-z0-9]+/', '-', strtolower($lote['bandeira_cartao']));
        return 'antecipacao:'.$lote['data_lote'].':'.$lote['data_origem_lote'].':'.(int) $lote['maquininha_cartao_id'].':'.$bandeira;
    }

    private function chave_lote_legada($lote)
    {
        $bandeira = preg_replace('/[^a-z0-9]+/', '-', strtolower($lote['bandeira_cartao']));
        return 'antecipacao:'.$lote['data_lote'].':'.(int) $lote['maquininha_cartao_id'].':'.$bandeira;
    }

    private function data_origem_lote($receita, $data_lote)
    {
        $data_pago = $this->campo($receita, 'data_pago');
        $data_vencimento = $this->campo($receita, 'data_vencimento');
        // data_pago identifica o lote de origem dos novos registros; os
        // historicos sem essa informacao continuam agrupados.
        return $data_pago ?: ($data_vencimento ?: $data_lote);
    }

    private function data_base_antecipacao($receita, $data_lote)
    {
        $data_pago = $this->campo($receita, 'data_pago');
        if ($data_pago && $data_pago !== $data_lote) return $data_pago;
        return $this->campo($receita, 'data_vencimento') ?: $data_pago;
    }

    private function campo($registro, $campo)
    {
        if (is_array($registro)) return isset($registro[$campo]) ? $registro[$campo] : null;
        return isset($registro->{$campo}) ? $registro->{$campo} : null;
    }
}
