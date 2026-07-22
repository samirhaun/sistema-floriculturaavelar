-- Antecipacao SIPAG: percentual com precisao, base liquida e agrupamento por bandeira.
ALTER TABLE maquininhas_cartao
  MODIFY taxa_antecipacao DECIMAL(8,4) NULL DEFAULT 0.0000;

ALTER TABLE maquininhas_cartao_taxas
  MODIFY taxa_antecipacao DECIMAL(8,4) NULL DEFAULT 0.0000;

ALTER TABLE contas_receber
  ADD COLUMN bandeira_cartao VARCHAR(40) NULL AFTER maquininha_taxa_id,
  ADD INDEX idx_contas_receber_lote_cartao (data_pago, maquininha_cartao_id, bandeira_cartao);

ALTER TABLE contas_pagar
  ADD COLUMN maquininha_cartao_id INT NULL AFTER contas_receber_id,
  ADD COLUMN bandeira_cartao VARCHAR(40) NULL AFTER maquininha_cartao_id,
  ADD COLUMN base_calculo DECIMAL(10,2) NULL AFTER bandeira_cartao,
  ADD COLUMN percentual_taxa DECIMAL(8,4) NULL AFTER base_calculo,
  ADD COLUMN chave_agrupamento VARCHAR(150) NULL AFTER origem,
  ADD UNIQUE INDEX uq_contas_pagar_chave_agrupamento (chave_agrupamento),
  ADD INDEX idx_contas_pagar_lote_cartao (data_vencimento, maquininha_cartao_id, bandeira_cartao);

-- Taxa inferida dos tres lotes fornecidos (Mastercard, Visa e Elo).
-- 1,489% sobre o liquido apos a taxa normal reproduz os valores do SIPAG.
UPDATE maquininhas_cartao
SET taxa_antecipacao = 1.4890,
    updated_at = NOW()
WHERE nome = 'SIPAG' AND deleted_at IS NULL;

UPDATE maquininhas_cartao_taxas mct
JOIN maquininhas_cartao mc ON mc.id = mct.maquininha_cartao_id
SET mct.taxa_antecipacao = 1.4890,
    mct.updated_at = NOW()
WHERE mc.nome = 'SIPAG' AND mc.deleted_at IS NULL AND mct.deleted_at IS NULL;

-- O proprio grupo selecionado identifica a bandeira; nao ha um segundo campo na venda.
UPDATE maquininhas_cartao_taxas mct
JOIN maquininhas_cartao mc ON mc.id = mct.maquininha_cartao_id
SET mct.grupo_bandeira = 'Visa',
    mct.bandeiras = 'Visa',
    mct.updated_at = NOW()
WHERE mc.nome = 'SIPAG'
  AND mct.grupo_bandeira = 'Geral'
  AND mct.deleted_at IS NULL;

INSERT INTO maquininhas_cartao_taxas
    (maquininha_cartao_id, grupo_bandeira, bandeiras, taxa_debito,
     taxa_credito_1x, taxa_credito_2x, taxa_credito_3x, taxa_credito_4x,
     taxa_antecipacao, ativo, created_at, updated_at, deleted_at)
SELECT visa.maquininha_cartao_id, 'Mastercard', 'Mastercard', visa.taxa_debito,
       visa.taxa_credito_1x, visa.taxa_credito_2x, visa.taxa_credito_3x,
       visa.taxa_credito_4x, visa.taxa_antecipacao, 1, NOW(), NOW(), NULL
FROM maquininhas_cartao_taxas visa
JOIN maquininhas_cartao mc ON mc.id = visa.maquininha_cartao_id
WHERE mc.nome = 'SIPAG'
  AND visa.grupo_bandeira = 'Visa'
  AND visa.deleted_at IS NULL
  AND NOT EXISTS (
      SELECT 1
      FROM maquininhas_cartao_taxas master
      WHERE master.maquininha_cartao_id = visa.maquininha_cartao_id
        AND master.grupo_bandeira = 'Mastercard'
        AND master.deleted_at IS NULL
  );
