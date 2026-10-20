-- CFOP por produto: hoje toda NFC-e sai com CFOP fixo 5102 (venda comum),
-- mas produtos de revenda com ICMS-ST (chocolates, vinhos/espumantes)
-- precisam de CFOP 5405. Sem essa coluna a diferenca era ignorada.
-- Executar apos a migration 012, primeiro em homologacao.
ALTER TABLE produtos
  ADD COLUMN nfce_cfop VARCHAR(4) NULL AFTER nfce_cofins_cst;
