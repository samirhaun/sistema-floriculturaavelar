-- Dados que o SINTEGRA precisa e que a emissao ainda nao gravava.
--
-- Os totais ficam na propria nota (o registro 61 e' um resumo diario por
-- documento) e o codigo do produto vai para o item (o registro 61R e' um
-- resumo mensal por produto). Sem o produtos_id o resumo dependeria de
-- pedido_produto, que e' apagado e reinserido a cada edicao do pedido.
--
-- Executar apos a migration 011, primeiro em homologacao.

ALTER TABLE notas_fiscais
  ADD COLUMN valor_produtos DECIMAL(10,2) NULL AFTER protocolo,
  ADD COLUMN valor_frete DECIMAL(10,2) NULL AFTER valor_produtos,
  ADD COLUMN valor_desconto DECIMAL(10,2) NULL AFTER valor_frete,
  ADD COLUMN valor_total DECIMAL(10,2) NULL AFTER valor_desconto;

ALTER TABLE notas_fiscais_itens
  ADD COLUMN produtos_id INT NULL AFTER pedido_produto_id,
  ADD COLUMN valor_frete DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER valor_total,
  ADD COLUMN valor_desconto DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER valor_frete,
  ADD KEY idx_notas_fiscais_itens_produto (produtos_id);

-- O registro 61 agrupa por dia de autorizacao; o indice evita varredura da
-- tabela inteira na geracao mensal.
ALTER TABLE notas_fiscais
  ADD KEY idx_notas_fiscais_autorizada_em (autorizada_em);
