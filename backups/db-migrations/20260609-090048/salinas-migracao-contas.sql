SET NAMES utf8mb4;
START TRANSACTION;
SET @inicio = '2026-01-01';
SET @fim = '2026-06-30';

SET @s_custos = (SELECT id FROM plano_contas WHERE cod='2' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_despesas = (SELECT id FROM plano_contas WHERE cod='3' AND ativo=1 AND deleted_at IS NULL LIMIT 1);

UPDATE plano_contas SET descricao='Custos', lancamento=0, plano_conta_id=NULL, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=@s_custos;
UPDATE plano_contas SET descricao='Despesas', lancamento=0, plano_conta_id=NULL, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=@s_despesas;

UPDATE plano_contas SET cod='2.1.1', descricao='Fornecedor Flores', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=15;
UPDATE plano_contas SET cod='2.1.2', descricao='Fornecedor Cestas', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=16;
UPDATE plano_contas SET cod='2.1.3', descricao='Fornecedor Chocolate', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=17;
UPDATE plano_contas SET cod='2.1.4', descricao='Fornecedor Pelucia', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=18;
UPDATE plano_contas SET cod='2.1.5', descricao='Fornecedores - Outros', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=19;
UPDATE plano_contas SET cod='2.1.6', descricao='Compras supermercado/insumos gerais', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=32;
UPDATE plano_contas SET cod='2.2.1', descricao='Impostos sobre produtos', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=21;
UPDATE plano_contas SET cod='2.2.2', descricao='Impostos funcionarios', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=22;
UPDATE plano_contas SET cod='2.3.1', descricao='Taxa de Maquininha', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=24;
UPDATE plano_contas SET cod='2.5.1', descricao='Fretes', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=27;
UPDATE plano_contas SET cod='2.5.2', descricao='Entregas', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=28;
UPDATE plano_contas SET cod='2.5.3', descricao='Combustivel', plano_conta_id=@s_custos, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=29;
UPDATE plano_contas SET cod='3.2.1', descricao='Salario funcionario', plano_conta_id=@s_despesas, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=30;
UPDATE plano_contas SET cod='3.2.3', descricao='Bonificacao', plano_conta_id=@s_despesas, lancamento=1, ativo=1, deleted_at=NULL, updated_at=NOW() WHERE id=31;

UPDATE plano_contas SET ativo=0, deleted_at=COALESCE(deleted_at,NOW()), updated_at=NOW() WHERE id IN (13,14,20,23,25,26);

INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Custos com Fornecedores e Insumos','2.1',@s_custos,0,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.1' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Custos com Impostos','2.2',@s_custos,0,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.2' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Custos Financeiros','2.3',@s_custos,0,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.3' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Custos Mao de Obra - Producao/Comissao','2.4',@s_custos,0,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.4' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Custos Operacional/Logistico','2.5',@s_custos,0,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.5' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Despesas Administrativas/Infraestrutura','3.1',@s_despesas,0,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.1' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Despesas com Folha de Pagamento','3.2',@s_despesas,0,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.2' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Despesas com Impostos','3.3',@s_despesas,0,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.3' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Despesas Bancarias','3.4',@s_despesas,0,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.4' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Despesas Extras/Esporadicas','3.6',@s_despesas,0,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.6' AND ativo=1 AND deleted_at IS NULL);

SET @s_2_1 = (SELECT id FROM plano_contas WHERE cod='2.1' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_2_2 = (SELECT id FROM plano_contas WHERE cod='2.2' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_2_3 = (SELECT id FROM plano_contas WHERE cod='2.3' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_2_4 = (SELECT id FROM plano_contas WHERE cod='2.4' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_2_5 = (SELECT id FROM plano_contas WHERE cod='2.5' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_3_1 = (SELECT id FROM plano_contas WHERE cod='3.1' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_3_2 = (SELECT id FROM plano_contas WHERE cod='3.2' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_3_3 = (SELECT id FROM plano_contas WHERE cod='3.3' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_3_4 = (SELECT id FROM plano_contas WHERE cod='3.4' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_3_6 = (SELECT id FROM plano_contas WHERE cod='3.6' AND ativo=1 AND deleted_at IS NULL LIMIT 1);

UPDATE plano_contas SET plano_conta_id=@s_2_1 WHERE id IN (15,16,17,18,19,32);
UPDATE plano_contas SET plano_conta_id=@s_2_2 WHERE id IN (21,22);
UPDATE plano_contas SET plano_conta_id=@s_2_3 WHERE id=24;
UPDATE plano_contas SET plano_conta_id=@s_2_5 WHERE id IN (27,28,29);
UPDATE plano_contas SET plano_conta_id=@s_3_2 WHERE id IN (30,31);

INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Taxa de Antecipacao','2.3.2',@s_2_3,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.3.2' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Freelancer','2.4.1',@s_2_4,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.4.1' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'UAI Logistica','2.5.4',@s_2_5,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.5.4' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Material de limpeza','3.1.1',@s_3_1,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.1.1' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Material de escritorio/embalagem','3.1.2',@s_3_1,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.1.2' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Alimentacao/almoco','3.1.3',@s_3_1,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.1.3' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Outras despesas administrativas','3.1.4',@s_3_1,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.1.4' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Cartao Alimentacao','3.2.2',@s_3_2,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.2.2' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'IPTU','3.3.1',@s_3_3,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.3.1' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Taxas bancarias','3.4.1',@s_3_4,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.4.1' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Doacoes/patrocinios','3.6.1',@s_3_6,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.6.1' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Outras despesas','3.6.2',@s_3_6,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.6.2' AND ativo=1 AND deleted_at IS NULL);

SET @s_2_3_2 = (SELECT id FROM plano_contas WHERE cod='2.3.2' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_2_4_1 = (SELECT id FROM plano_contas WHERE cod='2.4.1' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_2_5_4 = (SELECT id FROM plano_contas WHERE cod='2.5.4' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_3_2_1 = (SELECT id FROM plano_contas WHERE cod='3.2.1' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_3_2_2 = (SELECT id FROM plano_contas WHERE cod='3.2.2' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @s_3_6_2 = (SELECT id FROM plano_contas WHERE cod='3.6.2' AND ativo=1 AND deleted_at IS NULL LIMIT 1);

UPDATE contas_pagar SET plano_contas_id=@s_3_2_1 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=37 AND @s_3_2_1 IS NOT NULL;
SELECT 'SAL salario funcionario' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@s_3_6_2 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=43 AND @s_3_6_2 IS NOT NULL;
SELECT 'SAL outros' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@s_3_2_2 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=38 AND @s_3_2_2 IS NOT NULL;
SELECT 'SAL cartao alimentacao' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@s_2_5_4 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=40 AND @s_2_5_4 IS NOT NULL;
SELECT 'SAL uai logistica' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@s_2_4_1 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=35 AND @s_2_4_1 IS NOT NULL;
SELECT 'SAL freelancer' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@s_2_3_2 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=34 AND @s_2_3_2 IS NOT NULL;
SELECT 'SAL taxa antecipacao' item, ROW_COUNT() alteradas;

COMMIT;
