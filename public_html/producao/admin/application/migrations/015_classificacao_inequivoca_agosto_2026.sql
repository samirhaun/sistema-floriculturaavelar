-- Classificacao revisada a partir da planilha e da orientacao da
-- contabilidade. Somente nomes inequivocos sao vinculados aqui; cestas,
-- brindes, plantas e itens de decoracao sem identificacao fiscal permanecem
-- pendentes para validacao humana.

UPDATE produtos p
INNER JOIN grupos_fiscais_nfce g ON g.nome = 'Flores e arranjos'
SET p.grupos_fiscais_nfce_id = g.id,
    p.grupo_fiscal_sugerido_id = NULL,
    p.grupo_fiscal_sugestao_confianca = NULL,
    p.grupo_fiscal_sugestao_origem = NULL
WHERE p.grupos_fiscais_nfce_id IS NULL
  AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '')
  AND TRIM(UPPER(p.titulo)) IN (
    '3 MINI CALANDIVA', 'CALANDIVA', 'CALANDIVAS MEDIAS',
    'BOTÃO DE ROSA SIMPLES', 'BOTÃO DE ROSA ORNAMENTADO',
    'BOTÃO DE ROSAS SABRINA', '2 BOTÕES ORNAMENTADO',
    'ASTER MARIANO BRANCO', 'ASTER COMUM BRANCO',
    'CALLA', 'CALLA NA PROMOÇÃO',
    'EUCAFLOR', 'RUSCUS', 'TULIPA', 'LISIANTHUS CORTE ROSA',
    'PACOTE DE CRISATEMO DECORADOR', 'PACOTE DE CRAVO 1 LILÁS E 1 ROSA PINK',
    'LIRIO PINTADO DE PRETO', 'ROSA PINTADA DE PRETA', 'DIANTHUS',
    'RAMALHETE FLOR DO CAMPO GRANDE', 'FLORES DIVERSAS',
    'LIMONIUM COLORIDA PACOTE', 'LAPELA DE GIPSON',
    '1 UN DE GIRASSOL NA MANTA COM TANGO',
    '1 UN DE LIRIO NA MANTA COM TANGO',
    '2 GIRASSÓIS E 3 ROSAS ORNAMENTADO COM TANGO E GISPON',
    'MIMO BOTÃO COM FERRERO T6', 'MIMO BOTAO DE GIRASSOL COM FERRERO T8',
    'GIRRASSOL COM FERRERO ROCHER T8',
    'BOX ALTO FLORES DO CAMPO', 'BOX BAIXO COM FLORES DO CAMPO',
    'BOX BAIXO COM ROSAS', 'BOX CAIXA GAVETA FLORES DO CAMPO 2 T3 NAMORADOS',
    'BOX CAIXA GAVETA COM ROSAS MAIS CHOCOLATE 1 T4 NAMORADOS',
    'BOX CORAÇÃO COM ROSAS E FERRERO T8', 'BOX CORAÇÃO DE ROSAS',
    'BOX GRANDE GG NA COR BRANCA DE ROSAS VERMELHAS',
    'CAIXA DE FLORAL F1 DECORADOR', 'CAIXA FLORAL F1',
    'ARRANJOS DE GIRASSOL PARA AGUA ( 6 GIRASSOL, TANGO E ASTROMELIAS)',
    'BUQUÊ COM 43 ROSAS', 'BUQUE DE NOIVA ASTROMELIA ROSA E GIPSON SABRINA',
    'BUQUÊ FLORES DO CAMPO', 'BUQUE ROSAS BRANCAS CASAMENTO',
    'GIRASSOL ORNAMENTADO COM ROSAS'
  );

UPDATE produtos p
INNER JOIN grupos_fiscais_nfce g ON g.nome = 'Vasos decorativos'
SET p.grupos_fiscais_nfce_id = g.id,
    p.grupo_fiscal_sugerido_id = NULL,
    p.grupo_fiscal_sugestao_confianca = NULL,
    p.grupo_fiscal_sugestao_origem = NULL
WHERE p.grupos_fiscais_nfce_id IS NULL
  AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '')
  AND TRIM(UPPER(p.titulo)) IN (
    'ORQUIDEA NO VASO', 'MINI ORQUIDEAS NO VASO',
    'VASO DE VIDRO COM ASTROMÉILIA', 'VASO NAMORADEIRA PEQUENA'
  );

UPDATE produtos p
INNER JOIN grupos_fiscais_nfce g ON g.nome = 'Embalagens, caixas e palitos'
SET p.grupos_fiscais_nfce_id = g.id,
    p.grupo_fiscal_sugerido_id = NULL,
    p.grupo_fiscal_sugestao_confianca = NULL,
    p.grupo_fiscal_sugestao_origem = NULL
WHERE p.grupos_fiscais_nfce_id IS NULL
  AND (p.nfce_ncm IS NULL OR p.nfce_ncm = '')
  AND TRIM(UPPER(p.titulo)) IN ('BOMBOX VERMELHO', 'CAIXA PAPEL');
