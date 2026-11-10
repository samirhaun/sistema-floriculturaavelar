-- Permite que o gerente emita a NFC-e de uma venda com entrega como se fosse
-- retirada (consumidor nao identificado), quando o cliente nao informa CPF/CNPJ.
-- A habilidade deve ser liberada so para gerentes na tela de permissoes.

INSERT INTO habilidades (nome_unico, descricao)
SELECT 'emitir_nfce_entrega_como_retirada', 'Emitir NFC-e de entrega como retirada (cliente sem CPF)'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM habilidades WHERE nome_unico = 'emitir_nfce_entrega_como_retirada');

ALTER TABLE notas_fiscais
  ADD COLUMN emitida_como_retirada_por INT NULL DEFAULT NULL;
