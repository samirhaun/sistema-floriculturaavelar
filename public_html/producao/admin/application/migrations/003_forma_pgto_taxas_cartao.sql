-- Taxas de cartao sao abatimentos de recebimentos bancarios e precisam
-- participar do fluxo de caixa por forma de pagamento.
-- Taxas vinculadas a uma venda herdam sua forma; lotes de antecipacao e
-- registros legados sem vinculo usam a categoria generica Credito 1x (3).
UPDATE contas_pagar cp
INNER JOIN contas_receber cr ON cr.id = cp.contas_receber_id
SET cp.forma_pgto = cr.forma_pgto
WHERE cp.origem IN ('taxa_maquininha', 'taxa_antecipacao')
  AND cp.forma_pgto IS NULL
  AND cr.forma_pgto IS NOT NULL;

UPDATE contas_pagar
SET forma_pgto = 3
WHERE origem IN ('taxa_maquininha', 'taxa_antecipacao')
  AND forma_pgto IS NULL;
