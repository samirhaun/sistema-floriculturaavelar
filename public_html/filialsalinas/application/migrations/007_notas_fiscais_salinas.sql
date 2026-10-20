-- Base de rastreabilidade para NF-e emitida a partir de pedidos de Salinas.
-- Executar primeiro em homologacao e somente depois do backup do banco de producao.

CREATE TABLE notas_fiscais (
  id INT NOT NULL AUTO_INCREMENT,
  pedidos_id INT NOT NULL,
  tipo_documento VARCHAR(10) NOT NULL DEFAULT 'nfe',
  ambiente VARCHAR(20) NOT NULL,
  provedor VARCHAR(30) NOT NULL DEFAULT 'focus_nfe',
  referencia VARCHAR(100) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'pendente',
  tentativa SMALLINT NOT NULL DEFAULT 0,
  numero VARCHAR(20) DEFAULT NULL,
  serie VARCHAR(10) DEFAULT NULL,
  chave_acesso VARCHAR(50) DEFAULT NULL,
  protocolo VARCHAR(50) DEFAULT NULL,
  xml_url TEXT,
  danfe_url TEXT,
  payload_json MEDIUMTEXT,
  resposta_json MEDIUMTEXT,
  erro TEXT,
  autorizada_em DATETIME DEFAULT NULL,
  cancelada_em DATETIME DEFAULT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_notas_fiscais_referencia (referencia),
  UNIQUE KEY uq_notas_fiscais_pedido_documento (pedidos_id, tipo_documento),
  KEY idx_notas_fiscais_status (status),
  CONSTRAINT fk_notas_fiscais_pedidos
    FOREIGN KEY (pedidos_id) REFERENCES pedidos (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE notas_fiscais_pagamentos (
  id INT NOT NULL AUTO_INCREMENT,
  notas_fiscais_id INT NOT NULL,
  forma_pgto INT NOT NULL,
  valor DECIMAL(10,2) NOT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_notas_fiscais_pagamentos_nota (notas_fiscais_id),
  CONSTRAINT fk_notas_fiscais_pagamentos_nota
    FOREIGN KEY (notas_fiscais_id) REFERENCES notas_fiscais (id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE notas_fiscais_itens (
  id INT NOT NULL AUTO_INCREMENT,
  notas_fiscais_id INT NOT NULL,
  pedido_produto_id INT DEFAULT NULL,
  descricao VARCHAR(255) NOT NULL,
  quantidade DECIMAL(10,4) NOT NULL,
  valor_unitario DECIMAL(10,2) NOT NULL,
  valor_total DECIMAL(10,2) NOT NULL,
  ncm VARCHAR(8) DEFAULT NULL,
  cfop VARCHAR(4) DEFAULT NULL,
  unidade VARCHAR(6) DEFAULT NULL,
  tributacao_json MEDIUMTEXT,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_notas_fiscais_itens_nota (notas_fiscais_id),
  CONSTRAINT fk_notas_fiscais_itens_nota
    FOREIGN KEY (notas_fiscais_id) REFERENCES notas_fiscais (id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
