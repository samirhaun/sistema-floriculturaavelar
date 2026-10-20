-- Cadastro fiscal individual dos produtos para NFC-e de Montes Claros.
-- Executar apos a migration 008, primeiro em homologacao.
ALTER TABLE produtos
  ADD COLUMN nfce_ncm VARCHAR(8) NULL AFTER descricao,
  ADD COLUMN nfce_csosn VARCHAR(4) NULL AFTER nfce_ncm,
  ADD COLUMN nfce_pis_cst VARCHAR(2) NULL AFTER nfce_csosn,
  ADD COLUMN nfce_cofins_cst VARCHAR(2) NULL AFTER nfce_pis_cst;
