CREATE TABLE IF NOT EXISTS maquininhas_cartao_taxas (
  id INT NOT NULL AUTO_INCREMENT,
  maquininha_cartao_id INT NOT NULL,
  grupo_bandeira VARCHAR(120) NOT NULL,
  bandeiras VARCHAR(255) NULL,
  taxa_debito DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  taxa_credito_1x DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  taxa_credito_2x DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  taxa_credito_3x DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  taxa_credito_4x DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  deleted_at DATETIME NULL,
  PRIMARY KEY (id),
  KEY idx_maquininhas_cartao_taxas_maquininha (maquininha_cartao_id),
  KEY idx_maquininhas_cartao_taxas_ativo (ativo, deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE contas_receber ADD COLUMN maquininha_taxa_id INT NULL AFTER maquininha_cartao_id;
CREATE INDEX idx_contas_receber_maquininha_taxa ON contas_receber (maquininha_taxa_id);
