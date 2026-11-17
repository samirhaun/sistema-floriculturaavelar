<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Consultas do arquivo SINTEGRA.
 *
 * A base e' o que este sistema emite: NFC-e (modelo 65). O arquivo oficial
 * usa a data real de autorizacao/emissao do documento, conforme o leiaute do
 * registro 61. A conferencia comercial por data da venda e' um relatorio
 * separado e nao pode alterar a competencia fiscal do arquivo.
 */
class Sintegra_model extends CI_Model {

    /** Resumo diario por modelo e serie -> registro 61. */
    public function resumo_diario($inicio, $fim)
    {
        $this->db->select("DATE(notas_fiscais.autorizada_em) AS dia, notas_fiscais.serie,
            MIN(CAST(notas_fiscais.numero AS UNSIGNED)) AS numero_inicial,
            MAX(CAST(notas_fiscais.numero AS UNSIGNED)) AS numero_final,
            COUNT(*) AS documentos,
            SUM(notas_fiscais.valor_total) AS valor_total", false);
        $this->aplicar_filtro_notas($inicio, $fim);
        $this->db->group_by('DATE(notas_fiscais.autorizada_em), notas_fiscais.serie');
        $this->db->order_by('dia', 'ASC');
        $this->db->order_by('notas_fiscais.serie', 'ASC');
        return $this->db->get()->result();
    }

    /** Resumo mensal por produto -> registro 61R. */
    public function resumo_por_produto($inicio, $fim)
    {
        $this->db->select("notas_fiscais_itens.produtos_id,
            SUM(notas_fiscais_itens.quantidade) AS quantidade,
            SUM(notas_fiscais_itens.valor_total) AS valor_bruto", false);
        $this->db->join('notas_fiscais_itens', 'notas_fiscais_itens.notas_fiscais_id = notas_fiscais.id');
        $this->aplicar_filtro_notas($inicio, $fim);
        $this->db->where('notas_fiscais_itens.produtos_id IS NOT NULL', null, false);
        $this->db->group_by('notas_fiscais_itens.produtos_id');
        $this->db->order_by('notas_fiscais_itens.produtos_id', 'ASC');
        return $this->db->get()->result();
    }

    /** Cadastro dos produtos citados no periodo -> registro 75. */
    public function produtos_do_periodo($inicio, $fim)
    {
        $this->db->select("produtos.id, produtos.titulo,
            MAX(notas_fiscais_itens.ncm) AS ncm,
            MAX(notas_fiscais_itens.unidade) AS unidade", false);
        $this->db->join('notas_fiscais_itens', 'notas_fiscais_itens.notas_fiscais_id = notas_fiscais.id');
        $this->db->join('produtos', 'produtos.id = notas_fiscais_itens.produtos_id');
        $this->aplicar_filtro_notas($inicio, $fim);
        $this->db->group_by('produtos.id, produtos.titulo');
        $this->db->order_by('produtos.id', 'ASC');
        return $this->db->get()->result();
    }

    /** Notas autorizadas sem valor ou sem numero travam a geracao do arquivo. */
    public function notas_inconsistentes($inicio, $fim)
    {
        $this->db->select('notas_fiscais.id, notas_fiscais.pedidos_id, notas_fiscais.numero, notas_fiscais.serie, notas_fiscais.valor_total');
        $this->aplicar_filtro_notas($inicio, $fim);
        $this->db->group_start()
            ->where('notas_fiscais.valor_total IS NULL', null, false)
            ->or_where('notas_fiscais.valor_total <=', 0)
            ->or_where('notas_fiscais.numero IS NULL', null, false)
            ->or_where('notas_fiscais.numero', '')
            ->group_end();
        return $this->db->get()->result();
    }

    /** Notas do periodo que ficaram sem desfecho: nao entram no arquivo. */
    public function notas_pendentes($inicio, $fim)
    {
        return $this->db->select('notas_fiscais.id, notas_fiscais.pedidos_id, notas_fiscais.status, notas_fiscais.erro, notas_fiscais.created_at')
            ->from('notas_fiscais')
            ->where('notas_fiscais.tipo_documento', 'nfce')
            ->where_not_in('notas_fiscais.status', array('autorizada', 'cancelada'))
            ->where('notas_fiscais.created_at >=', $inicio.' 00:00:00')
            ->where('notas_fiscais.created_at <=', $fim.' 23:59:59')
            ->order_by('notas_fiscais.id', 'ASC')
            ->get()->result();
    }

    // ------------------------------------------------------------------
    // Notas de entrada (XML de fornecedor) -> registro 50
    // ------------------------------------------------------------------

    /** Sem a migration 021 a tela continua funcionando, so sem entradas. */
    public function entradas_disponiveis()
    {
        return $this->db->table_exists('sintegra_entradas');
    }

    /** Notas de entrada recebidas no periodo, para conferencia na tela. */
    public function entradas_do_periodo($inicio, $fim)
    {
        if (!$this->entradas_disponiveis()) return array();
        $entradas = $this->db->select('id, chave_nfe, modelo, numero_nota, serie, data_emissao, data_entrada,
                cnpj_emitente, ie_emitente, razao_emitente, uf_emitente, finalidade,
                valor_total, valor_bc_icms, valor_icms, valor_ipi, importado_em')
            ->from('sintegra_entradas')
            ->where('data_entrada >=', $inicio)
            ->where('data_entrada <=', $fim)
            ->order_by('data_entrada', 'ASC')
            ->order_by('id', 'ASC')
            ->get()->result();
        if (!$entradas) return array();

        $por_id = array();
        foreach ($entradas as $entrada) {
            $entrada->cfops = array();
            $por_id[$entrada->id] = $entrada;
        }
        $linhas = $this->db->where_in('sintegra_entradas_id', array_keys($por_id))
            ->order_by('sintegra_entradas_id', 'ASC')
            ->order_by('cfop', 'ASC')
            ->order_by('aliquota', 'ASC')
            ->get('sintegra_entradas_cfop')->result();
        foreach ($linhas as $linha) {
            $por_id[$linha->sintegra_entradas_id]->cfops[] = $linha;
        }
        return array_values($por_id);
    }

    public function entrada_por_chave($chave)
    {
        return $this->db->select('id, data_entrada')->get_where('sintegra_entradas', array('chave_nfe' => $chave))->row();
    }

    public function entrada_xml($id)
    {
        return $this->db->select('id, chave_nfe, xml_conteudo, data_entrada')->get_where('sintegra_entradas', array('id' => (int) $id))->row();
    }

    /** Grava a nota e o resumo por CFOP juntos: sem resumo a nota nao gera registro 50. */
    public function inserir_entrada(array $nota, array $cfops)
    {
        $this->db->trans_start();
        $this->db->insert('sintegra_entradas', $nota);
        $id = $this->db->insert_id();
        foreach ($cfops as $cfop) {
            $cfop['sintegra_entradas_id'] = $id;
            $this->db->insert('sintegra_entradas_cfop', $cfop);
        }
        $this->db->trans_complete();
        return $this->db->trans_status() ? $id : false;
    }

    public function excluir_entrada($id)
    {
        // As linhas de CFOP saem pela FK com ON DELETE CASCADE.
        $this->db->delete('sintegra_entradas', array('id' => (int) $id));
        return $this->db->affected_rows() > 0;
    }

    private function aplicar_filtro_notas($inicio, $fim)
    {
        $this->db->from('notas_fiscais');
        $this->db->where('notas_fiscais.tipo_documento', 'nfce');
        $this->db->where('notas_fiscais.status', 'autorizada');
        $this->db->where('notas_fiscais.autorizada_em >=', $inicio.' 00:00:00');
        $this->db->where('notas_fiscais.autorizada_em <=', $fim.' 23:59:59');
    }
}
