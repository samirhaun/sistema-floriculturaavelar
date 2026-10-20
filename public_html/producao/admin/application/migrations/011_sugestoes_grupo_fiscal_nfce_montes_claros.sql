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
