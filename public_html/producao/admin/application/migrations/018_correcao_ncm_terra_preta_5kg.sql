-- A SEFAZ rejeitou o NCM 31019900 da Terra Preta 5 KG. A contabilidade
-- informou o NCM valido 31010000 para a mesma mercadoria na embalagem de 20 KG;
-- aplica-se a mesma classificacao ao pacote de 5 KG.
UPDATE produtos
SET nfce_ncm = '31010000', nfce_csosn = '102', nfce_pis_cst = '04',
    nfce_cofins_cst = '04', nfce_cfop = '5102'
WHERE id = 164
  AND nfce_ncm = '31019900';
