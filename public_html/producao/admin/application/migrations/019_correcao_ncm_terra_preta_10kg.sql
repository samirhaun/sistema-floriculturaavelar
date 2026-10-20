-- Mesma classificacao corrigida para outra embalagem da Terra Preta.
UPDATE produtos
SET nfce_ncm = '31010000', nfce_csosn = '102', nfce_pis_cst = '04',
    nfce_cofins_cst = '04', nfce_cfop = '5102'
WHERE id = 161
  AND nfce_ncm = '31019900';
