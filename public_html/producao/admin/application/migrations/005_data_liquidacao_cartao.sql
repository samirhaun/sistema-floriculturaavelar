-- Data interna de liquidacao bancaria de cada conta de cartao (D+1 util).
ALTER TABLE contas_receber
  ADD COLUMN data_liquidacao DATE NULL AFTER data_pago,
  ADD INDEX idx_contas_receber_data_liquidacao (data_liquidacao);
