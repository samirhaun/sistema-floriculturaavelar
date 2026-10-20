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
