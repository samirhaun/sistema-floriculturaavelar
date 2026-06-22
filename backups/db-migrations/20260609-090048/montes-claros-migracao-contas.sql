SET NAMES utf8mb4;
START TRANSACTION;
SET @inicio = '2026-01-01';
SET @fim = '2026-06-30';

SET @mc_2_1 = (SELECT id FROM plano_contas WHERE cod='2.1' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_3 = (SELECT id FROM plano_contas WHERE cod='2.3' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_4 = (SELECT id FROM plano_contas WHERE cod='2.4' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_5 = (SELECT id FROM plano_contas WHERE cod='2.5' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_1 = (SELECT id FROM plano_contas WHERE cod='3.1' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_2 = (SELECT id FROM plano_contas WHERE cod='3.2' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_3 = (SELECT id FROM plano_contas WHERE cod='3.3' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_4 = (SELECT id FROM plano_contas WHERE cod='3.4' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_6 = (SELECT id FROM plano_contas WHERE cod='3.6' AND ativo=1 AND deleted_at IS NULL LIMIT 1);

INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Fornecedores de flores - Outros','2.1.7',@mc_2_1,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.1.7' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Fornecedores - Outros','2.1.8',@mc_2_1,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.1.8' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Fornecedor Pelucia','2.1.9',@mc_2_1,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.1.9' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Taxa de Antecipacao','2.3.7',@mc_2_3,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.3.7' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Freelancer','2.4.3',@mc_2_4,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.4.3' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Mototaxi externo','2.5.8',@mc_2_5,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.5.8' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Rastreador de moto','2.5.9',@mc_2_5,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='2.5.9' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Certificado Digital','3.1.20',@mc_3_1,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.1.20' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Folha de pagamento - Outros','3.2.5',@mc_3_2,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.2.5' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Cartao Alimentacao','3.2.6',@mc_3_2,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.2.6' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Saude e beneficios','3.2.7',@mc_3_2,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.2.7' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Receita Federal','3.3.5',@mc_3_3,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.3.5' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Cartao de Credito','3.4.5',@mc_3_4,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.4.5' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Estorno de Pix','3.4.6',@mc_3_4,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.4.6' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Outras despesas','3.6.3',@mc_3_6,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.6.3' AND ativo=1 AND deleted_at IS NULL);
INSERT INTO plano_contas (descricao,cod,plano_conta_id,lancamento,ativo,created_at,updated_at)
SELECT 'Multas','3.6.4',@mc_3_6,1,1,NOW(),NOW() WHERE NOT EXISTS (SELECT 1 FROM plano_contas WHERE cod='3.6.4' AND ativo=1 AND deleted_at IS NULL);

SET @mc_2_1_1 = (SELECT id FROM plano_contas WHERE cod='2.1.1' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_1_2 = (SELECT id FROM plano_contas WHERE cod='2.1.2' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_1_3 = (SELECT id FROM plano_contas WHERE cod='2.1.3' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_1_4 = (SELECT id FROM plano_contas WHERE cod='2.1.4' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_1_5 = (SELECT id FROM plano_contas WHERE cod='2.1.5' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_1_6 = (SELECT id FROM plano_contas WHERE cod='2.1.6' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_1_7 = (SELECT id FROM plano_contas WHERE cod='2.1.7' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_1_8 = (SELECT id FROM plano_contas WHERE cod='2.1.8' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_1_9 = (SELECT id FROM plano_contas WHERE cod='2.1.9' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_3_2 = (SELECT id FROM plano_contas WHERE cod='2.3.2' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_3_7 = (SELECT id FROM plano_contas WHERE cod='2.3.7' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_4_3 = (SELECT id FROM plano_contas WHERE cod='2.4.3' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_5_3 = (SELECT id FROM plano_contas WHERE cod='2.5.3' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_5_8 = (SELECT id FROM plano_contas WHERE cod='2.5.8' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_2_5_9 = (SELECT id FROM plano_contas WHERE cod='2.5.9' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_1_7 = (SELECT id FROM plano_contas WHERE cod='3.1.7' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_1_11 = (SELECT id FROM plano_contas WHERE cod='3.1.11' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_1_20 = (SELECT id FROM plano_contas WHERE cod='3.1.20' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_2_5 = (SELECT id FROM plano_contas WHERE cod='3.2.5' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_2_6 = (SELECT id FROM plano_contas WHERE cod='3.2.6' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_2_7 = (SELECT id FROM plano_contas WHERE cod='3.2.7' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_3_5 = (SELECT id FROM plano_contas WHERE cod='3.3.5' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_4_5 = (SELECT id FROM plano_contas WHERE cod='3.4.5' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_4_6 = (SELECT id FROM plano_contas WHERE cod='3.4.6' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_5_1 = (SELECT id FROM plano_contas WHERE cod='3.5.1' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_6_3 = (SELECT id FROM plano_contas WHERE cod='3.6.3' AND ativo=1 AND deleted_at IS NULL LIMIT 1);
SET @mc_3_6_4 = (SELECT id FROM plano_contas WHERE cod='3.6.4' AND ativo=1 AND deleted_at IS NULL LIMIT 1);

UPDATE contas_pagar SET plano_contas_id=@mc_2_1_1 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=15 AND UPPER(COALESCE(descricao,'')) LIKE '%ANDRE%' AND @mc_2_1_1 IS NOT NULL;
SELECT 'MC 15 ANDRE' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_1_2 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=15 AND UPPER(COALESCE(descricao,'')) LIKE '%ARI%' AND UPPER(COALESCE(descricao,'')) NOT LIKE '%ROMARIO%' AND @mc_2_1_2 IS NOT NULL;
SELECT 'MC 15 ARI' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_1_3 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=15 AND UPPER(COALESCE(descricao,'')) LIKE '%VALDIR%' AND @mc_2_1_3 IS NOT NULL;
SELECT 'MC 15 VALDIR' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_1_4 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=15 AND UPPER(COALESCE(descricao,'')) LIKE '%GUSTAVO%' AND @mc_2_1_4 IS NOT NULL;
SELECT 'MC 15 GUSTAVO' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_1_5 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=15 AND (UPPER(COALESCE(descricao,'')) LIKE '%JULIO%' OR UPPER(COALESCE(descricao,'')) LIKE '%URSO%') AND @mc_2_1_5 IS NOT NULL;
SELECT 'MC 15 JULIO/URSO' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_1_6 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=15 AND UPPER(COALESCE(descricao,'')) LIKE '%PHELIP%' AND @mc_2_1_6 IS NOT NULL;
SELECT 'MC 15 PHELIP' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_1_7 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=15 AND @mc_2_1_7 IS NOT NULL;
SELECT 'MC 15 demais flores' item, ROW_COUNT() alteradas;

UPDATE contas_pagar SET plano_contas_id=@mc_3_2_5 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=30 AND @mc_3_2_5 IS NOT NULL;
SELECT 'MC salario outros' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_6_3 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=92 AND @mc_3_6_3 IS NOT NULL;
SELECT 'MC outros' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_4_5 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=117 AND @mc_3_4_5 IS NOT NULL;
SELECT 'MC cartao credito' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_1_8 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id IN (19,14) AND @mc_2_1_8 IS NOT NULL;
SELECT 'MC fornecedor outros/generico' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_5_8 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=121 AND @mc_2_5_8 IS NOT NULL;
SELECT 'MC moto taxi externo' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_5_1 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=161 AND @mc_3_5_1 IS NOT NULL;
SELECT 'MC emprestimo mario' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_2_6 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=135 AND @mc_3_2_6 IS NOT NULL;
SELECT 'MC caju cartao alimentacao' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_3_5 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=124 AND @mc_3_3_5 IS NOT NULL;
SELECT 'MC receita federal' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_3_2 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=101 AND @mc_2_3_2 IS NOT NULL;
SELECT 'MC taxa maquininha' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_3_7 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=102 AND @mc_2_3_7 IS NOT NULL;
SELECT 'MC taxa antecipacao' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_1_7 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id IN (119,127) AND @mc_3_1_7 IS NOT NULL;
SELECT 'MC deposito/climatize' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_1_11 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=114 AND @mc_3_1_11 IS NOT NULL;
SELECT 'MC az contabilidade' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_4_3 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=146 AND @mc_2_4_3 IS NOT NULL;
SELECT 'MC freelancer' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_4_6 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=156 AND @mc_3_4_6 IS NOT NULL;
SELECT 'MC estorno pix' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_6_4 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=162 AND @mc_3_6_4 IS NOT NULL;
SELECT 'MC multa moto' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_1_9 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=18 AND @mc_2_1_9 IS NOT NULL;
SELECT 'MC fornecedor pelucia' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_2_7 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=163 AND @mc_3_2_7 IS NOT NULL;
SELECT 'MC rateio fisioterapia' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_3_1_20 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=118 AND @mc_3_1_20 IS NOT NULL;
SELECT 'MC certificado digital' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_5_9 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=157 AND @mc_2_5_9 IS NOT NULL;
SELECT 'MC rastreador moto' item, ROW_COUNT() alteradas;
UPDATE contas_pagar SET plano_contas_id=@mc_2_5_3 WHERE data_vencimento BETWEEN @inicio AND @fim AND plano_contas_id=125 AND @mc_2_5_3 IS NOT NULL;
SELECT 'MC gasolina' item, ROW_COUNT() alteradas;

COMMIT;
