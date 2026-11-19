-- Extrato bancario importado da API de Conta Corrente do Sicoob, usado na
-- conciliacao automatica (Relatorios > Conciliacao bancaria).
CREATE TABLE IF NOT EXISTS extrato_bancario (
  id INT NOT NULL AUTO_INCREMENT,
  banco VARCHAR(20) NOT NULL DEFAULT 'sicoob',
  conta VARCHAR(30) NOT NULL,
  data DATE NOT NULL,
  tipo CHAR(1) NOT NULL COMMENT 'C credito, D debito',
  valor DECIMAL(12,2) NOT NULL,
  descricao VARCHAR(255) NULL,
  documento VARCHAR(80) NULL,
  cpf_cnpj VARCHAR(20) NULL,
  complemento VARCHAR(255) NULL,
  categoria VARCHAR(20) NOT NULL DEFAULT 'outros' COMMENT 'cartao, pix, outros',
  importado_em DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_extrato_data (conta, data)
);
