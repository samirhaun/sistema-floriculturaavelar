-- Grupos fiscais reutilizaveis. Produtos podem usar o grupo ou uma excecao
-- individual cadastrada na migration 009.
CREATE TABLE grupos_fiscais_nfce (
  id INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  ncm VARCHAR(8) NOT NULL,
  csosn VARCHAR(4) NOT NULL,
  pis_cst VARCHAR(2) NOT NULL,
  cofins_cst VARCHAR(2) NOT NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_grupos_fiscais_nfce_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE produtos
  ADD COLUMN grupos_fiscais_nfce_id INT NULL AFTER nfce_cofins_cst,
  ADD KEY idx_produtos_grupo_fiscal_nfce (grupos_fiscais_nfce_id),
  ADD CONSTRAINT fk_produtos_grupo_fiscal_nfce FOREIGN KEY (grupos_fiscais_nfce_id)
    REFERENCES grupos_fiscais_nfce (id);

INSERT INTO grupos_fiscais_nfce (id, nome, ncm, csosn, pis_cst, cofins_cst, ativo, created_at, updated_at) VALUES
  (1, 'Flores e arranjos', '06031900', '102', '04', '04', 1, NOW(), NOW()),
  (2, 'Coroas de flores', '67021000', '102', '04', '04', 1, NOW(), NOW()),
  (3, 'Vasos decorativos', '69139000', '102', '04', '04', 1, NOW(), NOW()),
  (4, 'Frete', '06039000', '102', '04', '04', 1, NOW(), NOW());

-- Classificacao automatica apenas onde a categoria ou o nome e' inequívoco.
UPDATE produtos p INNER JOIN categorias c ON c.id = p.categorias_id
SET p.grupos_fiscais_nfce_id = 1
WHERE c.nome IN ('Arranjos', 'Flores avulsas', 'Buques diversos', 'Buque de rosas', 'Buque de girassois', 'Arranjo de girassol');

UPDATE produtos SET grupos_fiscais_nfce_id = 2
WHERE UPPER(titulo) LIKE '%COROA%';

UPDATE produtos p INNER JOIN categorias c ON c.id = p.categorias_id
SET p.grupos_fiscais_nfce_id = 3
WHERE c.nome = 'VASOS';
