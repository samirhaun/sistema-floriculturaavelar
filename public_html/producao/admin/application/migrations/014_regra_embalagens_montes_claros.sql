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
