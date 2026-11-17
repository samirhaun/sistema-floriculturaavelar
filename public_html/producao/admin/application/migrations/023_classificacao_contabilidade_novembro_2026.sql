-- Classificacao fiscal enviada pela contabilidade em 17/11/2026
-- (planilha "Cadasttro flores.xlsx"), para os produtos do levantamento
-- PRODUTOS_SEM_CONFIG_FISCAL_2026-11-16 de Montes Claros.
--
-- A contabilidade indicou CST PIS/COFINS 49 para estes itens. A regra fica no
-- proprio produto (sobrepoe o grupo fiscal). CFOP 5102 para CSOSN 102 e 5405
-- para o chocolate com ICMS-ST (CSOSN 500), como nas demais regras.
--
-- Ficaram de fora, aguardando definicao:
--   659 IGOR DECORADOR e 996 RATEIO DOMESTICOS (sem classificacao; nao parecem mercadoria)
--   642 MINI CESTA, 788 MINI CESTA CHOCOLATE e 1085 CESTA PEQUENA COM URSO AZUL
--   (a contabilidade pede os itens da cesta separados no cupom).

-- Flores e arranjos: NCM 06031900, CSOSN 102, CST PIS/COFINS 49.
UPDATE produtos
SET nfce_ncm = '06031900', nfce_csosn = '102', nfce_pis_cst = '49',
    nfce_cofins_cst = '49', nfce_cfop = '5102'
WHERE id IN (
    68,    -- CALLA
    517,   -- BOX ALTO FLORES DO CAMPO
    101,   -- EUCAFLOR BABY PROMO
    1007,  -- 1 UN DE LIRIO NA MANTA COM TANGO
    1075,  -- ORQUIDEA AZUL NA PROMOCAO
    511,   -- BOX REDONDO ALTO COM ROSAS
    1064,  -- BUQUE 20 ROSAS
    1086,  -- BUQUE TRADICIONAL 10 ROSAS
    1079,  -- AMARYLLYS
    121,   -- LISIANTHUS CORTE
    1074,  -- BUQUE DE GERBERA 12 UNIDADES
    1073,  -- BUQUE DE GERBERA 6 UNIDADES
    912,   -- BUQUE TRADICIONAL DE 6 ROSAS AZUL
    649,   -- CONE MIX DE FLORES
    1080,  -- MINI CALANDIVA
    1047,  -- 2 GIRASSOL ORNAMENTADO COM 2 GERBERAS TANGO E GIPSON
    1081,  -- BOX PEQUENO DE ROSAS COM FERRERO
    1076,  -- CRAVINIA
    1083,  -- MIMO GERBERA E GIPSON
    1082,  -- MINI ORQUIDEA PROMOCAO
    1078,  -- MINI ORQUIDEA PROMOCAO
    1077,  -- BEGONIA PROMOCAO
    1084   -- GIPSON GALHO TINGIDO
);

-- Chocolate com ICMS-ST: NCM 18063210, CSOSN 500, CFOP 5405, CST PIS/COFINS 49.
UPDATE produtos
SET nfce_ncm = '18063210', nfce_csosn = '500', nfce_pis_cst = '49',
    nfce_cofins_cst = '49', nfce_cfop = '5405'
WHERE id = 657;  -- BARRA DE CHOCOLATE

-- Conferencia: deve listar os 24 produtos acima com a regra aplicada.
SELECT id, titulo, nfce_ncm, nfce_csosn, nfce_pis_cst, nfce_cofins_cst, nfce_cfop
FROM produtos
WHERE id IN (68, 517, 101, 1007, 1075, 511, 1064, 1086, 1079, 121, 1074, 1073,
             912, 649, 1080, 1047, 1081, 1076, 1083, 1082, 1078, 1077, 1084, 657)
ORDER BY id;
