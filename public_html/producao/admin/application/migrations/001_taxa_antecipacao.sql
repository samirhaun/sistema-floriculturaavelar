-- Taxa de antecipação (fixa) para maquininhas de cartão
ALTER TABLE maquininhas_cartao ADD COLUMN taxa_antecipacao DECIMAL(5,2) DEFAULT 0 AFTER taxa_credito_4x;
ALTER TABLE maquininhas_cartao_taxas ADD COLUMN taxa_antecipacao DECIMAL(5,2) DEFAULT 0 AFTER taxa_credito_4x;