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

SET @db = DATABASE();

SELECT COUNT(*) INTO @exists FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='contas_receber' AND COLUMN_NAME='maquininha_cartao_id';
SET @sql = IF(@exists=0, 'ALTER TABLE contas_receber ADD COLUMN maquininha_cartao_id INT(11) NULL AFTER forma_pgto', 'SELECT "contas_receber.maquininha_cartao_id exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT COUNT(*) INTO @exists FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='contas_receber' AND COLUMN_NAME='taxa_contas_pagar_id';
SET @sql = IF(@exists=0, 'ALTER TABLE contas_receber ADD COLUMN taxa_contas_pagar_id INT(11) NULL AFTER maquininha_cartao_id', 'SELECT "contas_receber.taxa_contas_pagar_id exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT COUNT(*) INTO @exists FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='contas_receber' AND INDEX_NAME='idx_contas_receber_maquininha';
SET @sql = IF(@exists=0, 'ALTER TABLE contas_receber ADD INDEX idx_contas_receber_maquininha (maquininha_cartao_id)', 'SELECT "idx_contas_receber_maquininha exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT COUNT(*) INTO @exists FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='contas_receber' AND INDEX_NAME='idx_contas_receber_taxa_conta';
SET @sql = IF(@exists=0, 'ALTER TABLE contas_receber ADD INDEX idx_contas_receber_taxa_conta (taxa_contas_pagar_id)', 'SELECT "idx_contas_receber_taxa_conta exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT COUNT(*) INTO @exists FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='contas_pagar' AND COLUMN_NAME='contas_receber_id';
SET @sql = IF(@exists=0, 'ALTER TABLE contas_pagar ADD COLUMN contas_receber_id INT(11) NULL AFTER forma_pgto', 'SELECT "contas_pagar.contas_receber_id exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT COUNT(*) INTO @exists FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='contas_pagar' AND COLUMN_NAME='origem';
SET @sql = IF(@exists=0, 'ALTER TABLE contas_pagar ADD COLUMN origem VARCHAR(50) NULL AFTER contas_receber_id', 'SELECT "contas_pagar.origem exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT COUNT(*) INTO @exists FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='contas_pagar' AND INDEX_NAME='idx_contas_pagar_contas_receber';
SET @sql = IF(@exists=0, 'ALTER TABLE contas_pagar ADD INDEX idx_contas_pagar_contas_receber (contas_receber_id)', 'SELECT "idx_contas_pagar_contas_receber exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT COUNT(*) INTO @exists FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='contas_pagar' AND INDEX_NAME='idx_contas_pagar_origem';
SET @sql = IF(@exists=0, 'ALTER TABLE contas_pagar ADD INDEX idx_contas_pagar_origem (origem)', 'SELECT "idx_contas_pagar_origem exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT 'schema_maquininhas_ok' AS resultado;
