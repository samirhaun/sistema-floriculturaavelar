-- Alguns cadastros antigos possuem espacos duplicados no titulo. O vinculo
-- por ID evita que a classificacao fiscal dependa dessa grafia inconsistente.

UPDATE produtos p
INNER JOIN grupos_fiscais_nfce g ON g.nome = 'Flores e arranjos'
SET p.grupos_fiscais_nfce_id = g.id,
    p.grupo_fiscal_sugerido_id = NULL,
    p.grupo_fiscal_sugestao_confianca = NULL,
    p.grupo_fiscal_sugestao_origem = NULL
WHERE p.id IN (325, 443, 515, 810, 979, 1048)
  AND p.grupos_fiscais_nfce_id IS NULL
  AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '');

UPDATE produtos p
INNER JOIN grupos_fiscais_nfce g ON g.nome = 'Vasos decorativos'
SET p.grupos_fiscais_nfce_id = g.id,
    p.grupo_fiscal_sugerido_id = NULL,
    p.grupo_fiscal_sugestao_confianca = NULL,
    p.grupo_fiscal_sugestao_origem = NULL
WHERE p.id IN (1008, 1049, 1050)
  AND p.grupos_fiscais_nfce_id IS NULL
  AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '');
