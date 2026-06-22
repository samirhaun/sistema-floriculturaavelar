SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS maquininhas_cartao (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(120) NOT NULL,
  fornecedor_id INT(11) NOT NULL,
  plano_contas_taxa_id INT(11) NOT NULL,
  taxa_debito DECIMAL(8,4) NOT NULL DEFAULT 0.0000,
  taxa_credito_1x DECIMAL(8,4) NOT NULL DEFAULT 0.0000,
  taxa_credito_2x DECIMAL(8,4) NOT NULL DEFAULT 0.0000,
  taxa_credito_3x DECIMAL(8,4) NOT NULL DEFAULT 0.0000,
  taxa_credito_4x DECIMAL(8,4) NOT NULL DEFAULT 0.0000,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  deleted_at DATETIME NULL,
  PRIMARY KEY (id),
  KEY idx_maquininhas_cartao_fornecedor (fornecedor_id),
  KEY idx_maquininhas_cartao_plano_taxa (plano_contas_taxa_id),
  KEY idx_maquininhas_cartao_ativo (ativo, deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE contas_receber
  ADD COLUMN IF NOT EXISTS maquininha_cartao_id INT(11) NULL AFTER forma_pgto,
  ADD COLUMN IF NOT EXISTS taxa_contas_pagar_id INT(11) NULL AFTER maquininha_cartao_id,
  ADD INDEX IF NOT EXISTS idx_contas_receber_maquininha (maquininha_cartao_id),
  ADD INDEX IF NOT EXISTS idx_contas_receber_taxa_conta (taxa_contas_pagar_id);

ALTER TABLE contas_pagar
  ADD COLUMN IF NOT EXISTS contas_receber_id INT(11) NULL AFTER forma_pgto,
  ADD COLUMN IF NOT EXISTS origem VARCHAR(50) NULL AFTER contas_receber_id,
  ADD INDEX IF NOT EXISTS idx_contas_pagar_contas_receber (contas_receber_id),
  ADD INDEX IF NOT EXISTS idx_contas_pagar_origem (origem);

SELECT 'schema_maquininhas_ok' AS resultado;
