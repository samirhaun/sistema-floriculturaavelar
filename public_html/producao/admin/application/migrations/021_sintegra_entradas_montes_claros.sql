-- Notas de compra (NF-e de fornecedor) importadas por XML para o SINTEGRA.
--
-- Nao movimentam estoque nem contas a pagar: servem somente para gerar os
-- registros 50 de entrada (emitente = terceiros). Cada nota guarda um resumo
-- por CFOP de entrada + aliquota, porque o leiaute exige um registro 50 para
-- cada combinacao dessas dentro do mesmo documento.
--
-- Executar apos a migration 020, primeiro em homologacao.

CREATE TABLE sintegra_entradas (
  id INT NOT NULL AUTO_INCREMENT,
  chave_nfe VARCHAR(44) NOT NULL,
  modelo CHAR(2) NOT NULL DEFAULT '55',
  numero_nota VARCHAR(20) NOT NULL,
  serie VARCHAR(10) DEFAULT NULL,
  data_emissao DATETIME NOT NULL,
  -- O registro 50 de entrada usa a data de recebimento, nao a de emissao.
  data_entrada DATE NOT NULL,
  cnpj_emitente VARCHAR(14) NOT NULL,
  ie_emitente VARCHAR(20) DEFAULT NULL,
  razao_emitente VARCHAR(255) DEFAULT NULL,
  uf_emitente CHAR(2) DEFAULT NULL,
  cnpj_destino VARCHAR(14) DEFAULT NULL,
  finalidade VARCHAR(20) NOT NULL DEFAULT 'revenda',
  valor_total DECIMAL(15,2) NOT NULL DEFAULT 0,
  valor_bc_icms DECIMAL(15,2) NOT NULL DEFAULT 0,
  valor_icms DECIMAL(15,2) NOT NULL DEFAULT 0,
  valor_ipi DECIMAL(15,2) NOT NULL DEFAULT 0,
  xml_conteudo LONGTEXT NOT NULL,
  importado_em DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_sintegra_entradas_chave (chave_nfe),
  KEY idx_sintegra_entradas_data_entrada (data_entrada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE sintegra_entradas_cfop (
  id INT NOT NULL AUTO_INCREMENT,
  sintegra_entradas_id INT NOT NULL,
  -- CFOP(s) de saida informados pelo fornecedor, mantidos para conferencia.
  cfop_nota VARCHAR(30) NOT NULL,
  -- CFOP de entrada (1xxx/2xxx/3xxx) que vai para o registro 50.
  cfop CHAR(4) NOT NULL,
  aliquota DECIMAL(5,2) NOT NULL DEFAULT 0,
  valor_total DECIMAL(15,2) NOT NULL DEFAULT 0,
  valor_bc_icms DECIMAL(15,2) NOT NULL DEFAULT 0,
  valor_icms DECIMAL(15,2) NOT NULL DEFAULT 0,
  valor_isentas DECIMAL(15,2) NOT NULL DEFAULT 0,
  valor_outras DECIMAL(15,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_sintegra_entradas_cfop_entrada (sintegra_entradas_id),
  CONSTRAINT fk_sintegra_entradas_cfop_entrada
    FOREIGN KEY (sintegra_entradas_id) REFERENCES sintegra_entradas (id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
