-- A SIPAG informa 1,54% ao mes. A taxa efetiva deve ser proporcional aos
-- dias entre a liquidacao antecipada e o vencimento original (D+30),
-- ajustado para o proximo dia util. O model realiza a proporcionalizacao.
UPDATE maquininhas_cartao
SET taxa_antecipacao = 1.5400
WHERE nome = 'SIPAG';

UPDATE maquininhas_cartao_taxas mct
INNER JOIN maquininhas_cartao mc ON mc.id = mct.maquininha_cartao_id
SET mct.taxa_antecipacao = 1.5400
WHERE mc.nome = 'SIPAG'
  AND mct.deleted_at IS NULL;
