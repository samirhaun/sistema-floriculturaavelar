-- Classificacoes recebidas da contabilidade para itens remanescentes.
-- CST PIS/COFINS 04 acompanha as demais operacoes do Simples Nacional.
UPDATE produtos
SET nfce_ncm = '06042000', nfce_csosn = '102', nfce_pis_cst = '04',
    nfce_cofins_cst = '04', nfce_cfop = '5102'
WHERE id IN (805, 33, 103, 946, 382, 1045, 151)
  AND (nfce_ncm IS NULL OR nfce_ncm = '');

UPDATE produtos
SET nfce_ncm = '68099000', nfce_csosn = '102', nfce_pis_cst = '04',
    nfce_cofins_cst = '04', nfce_cfop = '5102'
WHERE id = 974
  AND (nfce_ncm IS NULL OR nfce_ncm = '');

UPDATE produtos
SET nfce_ncm = '06031100', nfce_csosn = '102', nfce_pis_cst = '04',
    nfce_cofins_cst = '04', nfce_cfop = '5102'
WHERE id = 866
  AND (nfce_ncm IS NULL OR nfce_ncm = '');

UPDATE produtos
SET nfce_ncm = '18063210', nfce_csosn = '500', nfce_pis_cst = '04',
    nfce_cofins_cst = '04', nfce_cfop = '5405'
WHERE id = 519
  AND (nfce_ncm IS NULL OR nfce_ncm = '');

UPDATE produtos
SET nfce_ncm = '31010000', nfce_csosn = '102', nfce_pis_cst = '04',
    nfce_cofins_cst = '04', nfce_cfop = '5102'
WHERE id = 163
  AND (nfce_ncm IS NULL OR nfce_ncm = '');
