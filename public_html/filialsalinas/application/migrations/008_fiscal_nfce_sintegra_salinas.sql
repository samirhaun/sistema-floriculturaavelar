-- NFC-e / SINTEGRA / grupos fiscais para a filial Salinas (banco jardins_salin).
--
-- Consolida, na ordem, as migrations 008-015 e 020-022 de Montes Claros.
-- Ficaram de fora as 016-019, que corrigem produtos de Montes Claros por ID
-- (os IDs de Salinas sao outros). A classificacao restante e' feita pela tela
-- "Revisar classificacao fiscal".
--
-- Rodar UMA vez, depois do backup do banco, e ANTES de publicar o codigo:
-- o cadastro de produtos e o detalhe do pedido ja leem estas tabelas/colunas.
-- A emissao continua bloqueada por application/config/fiscal.php (enabled = FALSE).

-- 1) notas_fiscais: a 007 de Salinas criou as tabelas para NF-e. Se a 007
--    nunca foi rodada, cria agora; em seguida o padrao passa a ser NFC-e.
CREATE TABLE IF NOT EXISTS notas_fiscais (
  id INT NOT NULL AUTO_INCREMENT,
  pedidos_id INT NOT NULL,
  tipo_documento VARCHAR(10) NOT NULL DEFAULT 'nfce',
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

CREATE TABLE IF NOT EXISTS notas_fiscais_pagamentos (
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

CREATE TABLE IF NOT EXISTS notas_fiscais_itens (
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

ALTER TABLE notas_fiscais
  MODIFY tipo_documento VARCHAR(10) NOT NULL DEFAULT 'nfce';


-- ===== origem: 009_produtos_fiscal_nfce_montes_claros.sql =====
-- Cadastro fiscal individual dos produtos para NFC-e de Montes Claros.
-- Executar apos a migration 008, primeiro em homologacao.
ALTER TABLE produtos
  ADD COLUMN nfce_ncm VARCHAR(8) NULL AFTER descricao,
  ADD COLUMN nfce_csosn VARCHAR(4) NULL AFTER nfce_ncm,
  ADD COLUMN nfce_pis_cst VARCHAR(2) NULL AFTER nfce_csosn,
  ADD COLUMN nfce_cofins_cst VARCHAR(2) NULL AFTER nfce_pis_cst;


-- ===== origem: 010_grupos_fiscais_nfce_montes_claros.sql =====
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


-- ===== origem: 011_sugestoes_grupo_fiscal_nfce_montes_claros.sql =====
-- Sugestoes nao alteram a tributacao efetiva. A aprovacao acontece pela tela
-- de revisao em lote.
ALTER TABLE produtos
  ADD COLUMN grupo_fiscal_sugerido_id INT NULL AFTER grupos_fiscais_nfce_id,
  ADD COLUMN grupo_fiscal_sugestao_confianca VARCHAR(10) NULL AFTER grupo_fiscal_sugerido_id,
  ADD COLUMN grupo_fiscal_sugestao_origem VARCHAR(50) NULL AFTER grupo_fiscal_sugestao_confianca,
  ADD KEY idx_produtos_grupo_fiscal_sugerido (grupo_fiscal_sugerido_id),
  ADD CONSTRAINT fk_produtos_grupo_fiscal_sugerido FOREIGN KEY (grupo_fiscal_sugerido_id)
    REFERENCES grupos_fiscais_nfce (id);

UPDATE produtos
SET grupo_fiscal_sugerido_id = 1,
    grupo_fiscal_sugestao_confianca = 'media',
    grupo_fiscal_sugestao_origem = 'nome_do_produto'
WHERE grupos_fiscais_nfce_id IS NULL
  AND (nfce_ncm IS NULL OR nfce_ncm = '')
  AND UPPER(titulo) REGEXP 'ROSA|FLOR|BUQUE|ARRANJO|ASTER|CRISANT|MARGAR|GIRASSOL|LIRIO|LÍRIO|ASTROM|GERBER|TANGO|GIPSON|GIPSUM|EUCAFLOR|CALLA|COPO DE LEITE|LISIANTHUS';

UPDATE produtos
SET grupo_fiscal_sugerido_id = 3,
    grupo_fiscal_sugestao_confianca = 'media',
    grupo_fiscal_sugestao_origem = 'nome_do_produto'
WHERE grupos_fiscais_nfce_id IS NULL
  AND grupo_fiscal_sugerido_id IS NULL
  AND (nfce_ncm IS NULL OR nfce_ncm = '')
  AND UPPER(titulo) REGEXP 'VASO|CACHEPO|CACHEPOT|BACIA';

UPDATE produtos
SET grupo_fiscal_sugerido_id = 4,
    grupo_fiscal_sugestao_confianca = 'alta',
    grupo_fiscal_sugestao_origem = 'nome_do_produto'
WHERE grupos_fiscais_nfce_id IS NULL
  AND (nfce_ncm IS NULL OR nfce_ncm = '')
  AND UPPER(titulo) LIKE '%FRETE%';


-- ===== origem: 012_nfce_sintegra_montes_claros.sql =====
-- Dados que o SINTEGRA precisa e que a emissao ainda nao gravava.
--
-- Os totais ficam na propria nota (o registro 61 e' um resumo diario por
-- documento) e o codigo do produto vai para o item (o registro 61R e' um
-- resumo mensal por produto). Sem o produtos_id o resumo dependeria de
-- pedido_produto, que e' apagado e reinserido a cada edicao do pedido.
--
-- Executar apos a migration 011, primeiro em homologacao.

ALTER TABLE notas_fiscais
  ADD COLUMN valor_produtos DECIMAL(10,2) NULL AFTER protocolo,
  ADD COLUMN valor_frete DECIMAL(10,2) NULL AFTER valor_produtos,
  ADD COLUMN valor_desconto DECIMAL(10,2) NULL AFTER valor_frete,
  ADD COLUMN valor_total DECIMAL(10,2) NULL AFTER valor_desconto;

ALTER TABLE notas_fiscais_itens
  ADD COLUMN produtos_id INT NULL AFTER pedido_produto_id,
  ADD COLUMN valor_frete DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER valor_total,
  ADD COLUMN valor_desconto DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER valor_frete,
  ADD KEY idx_notas_fiscais_itens_produto (produtos_id);

-- O registro 61 agrupa por dia de autorizacao; o indice evita varredura da
-- tabela inteira na geracao mensal.
ALTER TABLE notas_fiscais
  ADD KEY idx_notas_fiscais_autorizada_em (autorizada_em);


-- ===== origem: 013_produtos_cfop_montes_claros.sql =====
-- CFOP por produto: hoje toda NFC-e sai com CFOP fixo 5102 (venda comum),
-- mas produtos de revenda com ICMS-ST (chocolates, vinhos/espumantes)
-- precisam de CFOP 5405. Sem essa coluna a diferenca era ignorada.
-- Executar apos a migration 012, primeiro em homologacao.
ALTER TABLE produtos
  ADD COLUMN nfce_cfop VARCHAR(4) NULL AFTER nfce_cofins_cst;


-- ===== origem: 014_regra_embalagens_montes_claros.sql =====
-- Orientacao da contabilidade (agosto/2026): embalagens, caixas avulsas e
-- palitos de churrasco usam NCM 48191000, CFOP 5102 e CSOSN 102.
-- PIS e COFINS seguem a regra do Simples Nacional ja usada nos demais grupos.
INSERT INTO grupos_fiscais_nfce
  (nome, ncm, csosn, pis_cst, cofins_cst, ativo, created_at, updated_at)
VALUES
  ('Embalagens, caixas e palitos', '48191000', '102', '04', '04', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  ncm = VALUES(ncm), csosn = VALUES(csosn), pis_cst = VALUES(pis_cst),
  cofins_cst = VALUES(cofins_cst), ativo = 1, updated_at = NOW();

-- A categoria EMBALAGENS contem um cadastro de girassol ja classificado de
-- forma individual; por isso a regra por grupo so entra quando nao houver
-- classificacao individual existente.
UPDATE produtos p
INNER JOIN categorias c ON c.id = p.categorias_id
INNER JOIN grupos_fiscais_nfce g ON g.nome = 'Embalagens, caixas e palitos'
SET p.grupos_fiscais_nfce_id = g.id,
    p.grupo_fiscal_sugerido_id = NULL,
    p.grupo_fiscal_sugestao_confianca = NULL,
    p.grupo_fiscal_sugestao_origem = NULL
WHERE c.nome = 'EMBALAGENS'
  AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '');

-- Itens avulsos de embalagem que ficaram cadastrados na categoria Outros.
UPDATE produtos p
INNER JOIN grupos_fiscais_nfce g ON g.nome = 'Embalagens, caixas e palitos'
SET p.grupos_fiscais_nfce_id = g.id,
    p.grupo_fiscal_sugerido_id = NULL,
    p.grupo_fiscal_sugestao_confianca = NULL,
    p.grupo_fiscal_sugestao_origem = NULL
WHERE p.titulo IN (
  'PALITO DE CHURRASCO',
  'EMBALAGEM UNIDADE',
  'CAIXA BOMBOX PRETA',
  'CAIXA BOMBOX VERMELHA'
)
  AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '');

-- Regra individual presente na planilha fiscal da contabilidade. A cesta e'
-- vendida como chocolate e, portanto, usa o NCM/CSOSN/CFOP de ICMS-ST.
UPDATE produtos
SET nfce_ncm = '18063210', nfce_csosn = '500', nfce_pis_cst = '04',
    nfce_cofins_cst = '04', nfce_cfop = '5405'
WHERE titulo = 'CESTA  COM CHOCOLATES'
  AND (nfce_ncm IS NULL OR nfce_ncm = '');


-- ===== origem: 015_classificacao_inequivoca_agosto_2026.sql =====
-- Classificacao revisada a partir da planilha e da orientacao da
-- contabilidade. Somente nomes inequivocos sao vinculados aqui; cestas,
-- brindes, plantas e itens de decoracao sem identificacao fiscal permanecem
-- pendentes para validacao humana.

UPDATE produtos p
INNER JOIN grupos_fiscais_nfce g ON g.nome = 'Flores e arranjos'
SET p.grupos_fiscais_nfce_id = g.id,
    p.grupo_fiscal_sugerido_id = NULL,
    p.grupo_fiscal_sugestao_confianca = NULL,
    p.grupo_fiscal_sugestao_origem = NULL
WHERE p.grupos_fiscais_nfce_id IS NULL
  AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '')
  AND TRIM(UPPER(p.titulo)) IN (
    '3 MINI CALANDIVA', 'CALANDIVA', 'CALANDIVAS MEDIAS',
    'BOTÃO DE ROSA SIMPLES', 'BOTÃO DE ROSA ORNAMENTADO',
    'BOTÃO DE ROSAS SABRINA', '2 BOTÕES ORNAMENTADO',
    'ASTER MARIANO BRANCO', 'ASTER COMUM BRANCO',
    'CALLA', 'CALLA NA PROMOÇÃO',
    'EUCAFLOR', 'RUSCUS', 'TULIPA', 'LISIANTHUS CORTE ROSA',
    'PACOTE DE CRISATEMO DECORADOR', 'PACOTE DE CRAVO 1 LILÁS E 1 ROSA PINK',
    'LIRIO PINTADO DE PRETO', 'ROSA PINTADA DE PRETA', 'DIANTHUS',
    'RAMALHETE FLOR DO CAMPO GRANDE', 'FLORES DIVERSAS',
    'LIMONIUM COLORIDA PACOTE', 'LAPELA DE GIPSON',
    '1 UN DE GIRASSOL NA MANTA COM TANGO',
    '1 UN DE LIRIO NA MANTA COM TANGO',
    '2 GIRASSÓIS E 3 ROSAS ORNAMENTADO COM TANGO E GISPON',
    'MIMO BOTÃO COM FERRERO T6', 'MIMO BOTAO DE GIRASSOL COM FERRERO T8',
    'GIRRASSOL COM FERRERO ROCHER T8',
    'BOX ALTO FLORES DO CAMPO', 'BOX BAIXO COM FLORES DO CAMPO',
    'BOX BAIXO COM ROSAS', 'BOX CAIXA GAVETA FLORES DO CAMPO 2 T3 NAMORADOS',
    'BOX CAIXA GAVETA COM ROSAS MAIS CHOCOLATE 1 T4 NAMORADOS',
    'BOX CORAÇÃO COM ROSAS E FERRERO T8', 'BOX CORAÇÃO DE ROSAS',
    'BOX GRANDE GG NA COR BRANCA DE ROSAS VERMELHAS',
    'CAIXA DE FLORAL F1 DECORADOR', 'CAIXA FLORAL F1',
    'ARRANJOS DE GIRASSOL PARA AGUA ( 6 GIRASSOL, TANGO E ASTROMELIAS)',
    'BUQUÊ COM 43 ROSAS', 'BUQUE DE NOIVA ASTROMELIA ROSA E GIPSON SABRINA',
    'BUQUÊ FLORES DO CAMPO', 'BUQUE ROSAS BRANCAS CASAMENTO',
    'GIRASSOL ORNAMENTADO COM ROSAS'
  );

UPDATE produtos p
INNER JOIN grupos_fiscais_nfce g ON g.nome = 'Vasos decorativos'
SET p.grupos_fiscais_nfce_id = g.id,
    p.grupo_fiscal_sugerido_id = NULL,
    p.grupo_fiscal_sugestao_confianca = NULL,
    p.grupo_fiscal_sugestao_origem = NULL
WHERE p.grupos_fiscais_nfce_id IS NULL
  AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '')
  AND TRIM(UPPER(p.titulo)) IN (
    'ORQUIDEA NO VASO', 'MINI ORQUIDEAS NO VASO',
    'VASO DE VIDRO COM ASTROMÉILIA', 'VASO NAMORADEIRA PEQUENA'
  );

UPDATE produtos p
INNER JOIN grupos_fiscais_nfce g ON g.nome = 'Embalagens, caixas e palitos'
SET p.grupos_fiscais_nfce_id = g.id,
    p.grupo_fiscal_sugerido_id = NULL,
    p.grupo_fiscal_sugestao_confianca = NULL,
    p.grupo_fiscal_sugestao_origem = NULL
WHERE p.grupos_fiscais_nfce_id IS NULL
  AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '')
  AND TRIM(UPPER(p.titulo)) IN ('BOMBOX VERMELHO', 'CAIXA PAPEL');


-- ===== origem: 020_grupo_fiscal_fallback_montes_claros.sql =====
-- Fallback temporario para o catalogo ativo sem classificacao completa.
-- Usa o mesmo enquadramento informado pela contabilidade para flores/arranjos:
-- NCM 06031900, CSOSN 102, PIS/COFINS 04 e CFOP interno 5102 (definido na emissao).
-- O nome deixa explicito que os itens continuam sujeitos a revisao individual.
INSERT INTO grupos_fiscais_nfce
    (nome, ncm, csosn, pis_cst, cofins_cst, ativo, created_at, updated_at)
VALUES
    ('Fallback fiscal - revisar classificacao', '06031900', '102', '04', '04', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    ativo = VALUES(ativo),
    updated_at = VALUES(updated_at);

-- Nao altera produto que ja tenha regra propria completa ou grupo completo.
-- Vincula apenas o catalogo ativo que ainda ficaria sem regra na NFC-e.
UPDATE produtos p
LEFT JOIN grupos_fiscais_nfce grupo_atual
    ON grupo_atual.id = p.grupos_fiscais_nfce_id
JOIN grupos_fiscais_nfce fallback
    ON fallback.nome = 'Fallback fiscal - revisar classificacao'
SET p.grupos_fiscais_nfce_id = fallback.id
WHERE COALESCE(p.ativo, 1) = 1
  AND NOT (
      COALESCE(p.nfce_ncm, '') <> ''
      AND COALESCE(p.nfce_csosn, '') <> ''
      AND COALESCE(p.nfce_pis_cst, '') <> ''
      AND COALESCE(p.nfce_cofins_cst, '') <> ''
  )
  AND NOT (
      COALESCE(grupo_atual.ncm, '') <> ''
      AND COALESCE(grupo_atual.csosn, '') <> ''
      AND COALESCE(grupo_atual.pis_cst, '') <> ''
      AND COALESCE(grupo_atual.cofins_cst, '') <> ''
  );


-- ===== origem: 021_sintegra_entradas_montes_claros.sql =====
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


-- ===== origem: 022_nfce_entrega_como_retirada.sql =====
-- Permite que o gerente emita a NFC-e de uma venda com entrega como se fosse
-- retirada (consumidor nao identificado), quando o cliente nao informa CPF/CNPJ.
-- A habilidade deve ser liberada so para gerentes na tela de permissoes.

INSERT INTO habilidades (nome_unico, descricao)
SELECT 'emitir_nfce_entrega_como_retirada', 'Emitir NFC-e de entrega como retirada (cliente sem CPF)'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM habilidades WHERE nome_unico = 'emitir_nfce_entrega_como_retirada');

ALTER TABLE notas_fiscais
  ADD COLUMN emitida_como_retirada_por INT NULL DEFAULT NULL;

