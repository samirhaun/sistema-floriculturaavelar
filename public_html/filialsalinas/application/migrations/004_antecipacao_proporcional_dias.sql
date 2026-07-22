-- Taxa nominal mensal; o model calcula a taxa efetiva pelos dias antecipados.
UPDATE maquininhas_cartao
SET taxa_antecipacao = 1.5400
WHERE nome = 'SIPAG';

UPDATE maquininhas_cartao_taxas mct
INNER JOIN maquininhas_cartao mc ON mc.id = mct.maquininha_cartao_id
SET mct.taxa_antecipacao = 1.5400
WHERE mc.nome = 'SIPAG'
  AND mct.deleted_at IS NULL;
