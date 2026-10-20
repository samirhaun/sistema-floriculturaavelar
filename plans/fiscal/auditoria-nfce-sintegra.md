# Auditoria fiscal — NFC-e (cupom fiscal) e geração do SINTEGRA

Data: 2026-08-05 · Escopo: `public_html/producao/admin` (Montes Claros) e `public_html/filialsalinas`

---

## 1. O que já existe hoje

| Peça | Arquivo | Situação |
|---|---|---|
| Cliente HTTP Focus NFe | `application/libraries/Focus_nfe.php` | Funcional (emite, consulta, baixa XML/DANFE) |
| Orquestração da NFC-e | `application/libraries/Nfce_service.php` | Funcional, com ressalvas (seção 3) |
| Persistência | `migrations/008_notas_fiscais_montes_claros.sql` (`notas_fiscais`, `_itens`, `_pagamentos`) | Completa |
| Classificação fiscal do produto | migrations 009/010/011 + `Loja_produtos::revisar_classificacao_fiscal` | Completa (NCM/CSOSN/PIS/COFINS por produto ou grupo) |
| Tela de notas | `Loja_notas_fiscais::lista` + `views/notas_fiscais/lista.php` | Funcional |
| Emissão automática na venda | `Site_pedidos.php:667-687` (+ impressão em `:885-887`) | Funcional |
| Botões emitir/reimprimir | `views/pedidos/detalhes_pedido.php:29-35` | Funcional |
| Chave de liga/desliga | `application/config/fiscal.php:12` → `enabled = FALSE` | **Desligado** |

Teste de homologação já executado com sucesso (`plans/testes-focus/resposta-nfcehomologacao202608041217502f08d0.json`):
`status: autorizado`, `status_sefaz: 100`, chave `31260849765943000102650010000000011661859201` (modelo 65, série 1, número 1).
Ou seja: **a integração em si está provada**. O que falta são regras de negócio e conformidade.

Filial Salinas: só tem `Focus_nfe.php`; `config/fiscal.php` aponta `documento_padrao = 'nfe'` e **não existe** `Nfce_service` nem migrations fiscais. A filial está fora do escopo atual.

---

## 2. Bloqueadores para ligar em produção

### 2.1 Conflito de numeração com o emissor atual (CRÍTICO)
O SINTEGRA de 06/2026 mostra que a empresa **já emite NFC-e modelo 65, série 1**, nos números **000883 a 000920** (registros 61 dos dias 02, 03 e 10/06), além de NF-e modelo 55 série 1 números 000622–000642 (registros 50 com indicador `P`).

A Focus controla a numeração (`config/fiscal.php:20`, `numeracao_nfce = 'focus'`) e começou a homologação em **série 1 / número 1**. Se produção subir assim, a SEFAZ devolve **duplicidade de documento**.

Ação: definir na Focus uma **série exclusiva** para este sistema (ex.: série 2) ou fixar o número inicial acima do último emitido pelo sistema legado. Decisão precisa vir junto com a contabilidade, porque muda o SINTEGRA (registro 61 é agrupado por modelo+série).

### 2.2 Pré-requisitos operacionais da Focus
- Certificado A1 enviado ao painel Focus (ambiente produção).
- **CSC / Token de NFC-e** cadastrado na Focus (obrigatório para modelo 65 — sem ele nenhuma NFC-e autoriza em produção).
- `FOCUS_NFE_TOKEN` e `FOCUS_NFE_CNPJ_EMITENTE` no ambiente do servidor (hoje só existem como `getenv`, `Nfce_service.php:161-166`).

---

## 3. Defeitos e lacunas encontrados na emissão

Ordenados por severidade.

### 3.1 Desconto percentual é tratado como reais — ALTA
`Nfce_service.php:90` faz `round((float) $pedido->valor_desconto, 2)` e usa como valor em R$.
Mas `pedidos.valor_desconto` guarda **percentual** quando `pedidos.tipo_desconto == 'porcentagem'` (`Site_pedidos.php:396-401`, confirmado em `Pedidos_model.php:1142-1146`).

Efeito: em todo pedido com desconto em %, o total fiscal calculado diverge do total pago e a emissão morre em `Nfce_service.php:139-141` (“Total pago diverge do total fiscal do pedido”). Nos casos em que o número coincidir, sai nota com desconto errado.

Correção: converter para reais antes de montar o payload — base do desconto é **somente o valor dos produtos** (`Site_pedidos.php:382-383`).

### 3.2 NFC-e não pode ser interestadual — ALTA
`Nfce_service.php:84-85` monta CFOP `6102` e `local_destino = '2'` quando `estado_entrega != MG`. Modelo 65 é restrito a operação **interna** com consumidor final; a SEFAZ rejeita a operação interestadual.

Correção: se o destino for fora de MG, a venda não pode sair como NFC-e — tem que ser NF-e modelo 55 (ou bloquear a emissão automática com mensagem clara, em vez de mandar e tomar rejeição).

### 3.3 Entrega em domicílio sem destinatário e sem endereço de entrega — ALTA
`Nfce_service.php:143,148` marca `presenca_comprador = '4'` (entrega a domicílio) quando o pedido tem `rua_entrega`, mas:
- o CPF/nome do destinatário só entra se o cliente tiver CPF válido (`:152-157`);
- o **grupo de endereço de entrega nunca é enviado**.

A SEFAZ exige identificação do destinatário e o endereço de entrega quando `indPres = 4`. Como praticamente toda venda da floricultura é entrega, isso rejeitaria a maioria das notas.

Correção: enviar o grupo `entrega` (logradouro, número, bairro, município, UF, CEP) a partir dos campos `*_entrega` do pedido e exigir CPF/CNPJ do destinatário nesse fluxo.

### 3.4 Frete e desconto empilhados no primeiro item — MÉDIA
`Nfce_service.php:110-114` joga o frete e o desconto **inteiros do pedido** no item 1.
- Se o desconto for maior que o valor do item 1, o `vDesc` do item excede o `vProd` → rejeição.
- `modalidade_frete = '9'` (`:149`) significa “sem ocorrência de transporte”, o que é incoerente com `valor_frete > 0`.

Correção: ratear frete e desconto proporcionalmente entre os itens e ajustar a modalidade do frete (0 = por conta do emitente, quando a loja entrega).

### 3.5 Venda em dinheiro e venda a prazo não geram documento fiscal — ALTA (conformidade)
`config/fiscal.php:24` (`emitir_apenas_pagamento_nao_dinheiro = TRUE`) + `Notas_fiscais_model.php:6,30-45`:
- só entra na fila o pedido que tem **alguma** parcela diferente de dinheiro;
- e só emite se **todas** as parcelas estiverem com `status = 1` (pagas).

Consequência: venda 100% em dinheiro nunca gera cupom, e venda a prazo/crediário só geraria (se gerasse) na quitação — depois da saída da mercadoria. O documento fiscal é devido na saída, independente da forma de pagamento. Isso também **quebra o SINTEGRA**: o registro 61 sairia com menos faturamento do que o movimento real.

Se a regra atual é uma decisão consciente de transição, precisa estar registrada — mas ela impede que o SINTEGRA gerado pelo sistema seja o arquivo oficial.

### 3.6 Sem reconciliação de nota “em voo” — MÉDIA
`Focus_nfe.php:111` usa timeout de 45s. Se estourar, `Nfce_service.php:63-66` grava `status = 'erro'` — mas a nota **pode ter sido autorizada** na SEFAZ. Não existe rotina que chame `consultar_nfce($referencia)` para as notas em `enviando`/`erro`.

Efeito: nota autorizada e invisível no sistema → falta no SINTEGRA e some do controle. A idempotência por `ref` protege contra emitir duas vezes (bom), mas ninguém fecha o ciclo.

Correção: job/cron de reconciliação varrendo `notas_fiscais` com status `enviando`, `erro`, `pendente`.

### 3.7 XML e DANFE não são arquivados localmente — MÉDIA
`Loja_notas_fiscais.php:52-80` baixa o arquivo da Focus a cada impressão; o banco guarda só a URL (`xml_url`, `danfe_url`). A guarda do XML é obrigação do emitente por 5 anos. Se a Focus ficar indisponível (ou o contrato acabar), não há reimpressão nem XML para a contabilidade — e o gerador de SINTEGRA fica sem fonte.

Correção: salvar o XML autorizado em disco/banco no momento da autorização.

### 3.8 Sem cancelamento de NFC-e — MÉDIA
A coluna `cancelada_em` existe na migration 008 mas nada a usa. NFC-e tem janela de cancelamento (30 min após autorização). Hoje, se o pedido é cancelado (`status_pedido` 4/5, `Loja_pedidos.php:129-134`) ou editado depois de autorizado, a nota continua válida e divergente — e entra errada no SINTEGRA.

### 3.9 Sem contingência offline — MÉDIA
Loja de balcão sem internet fica sem poder vender com documento. A Focus suporta contingência offline para NFC-e; o `Focus_nfe.php` não trata.

### 3.10 Permissão genérica demais — BAIXA
`Loja_notas_fiscais.php:14-19` autoriza emissão fiscal com `check_habilidade('visualizar_pedidos')`. Quem consegue ver pedido consegue emitir documento fiscal.

### 3.11 Variável possivelmente indefinida — BAIXA
`$nfce_imprimir_url` é inicializada em `Site_pedidos.php:668` (ramo de sucesso) e lida em `:885`, fora do `if/else`. No ramo de erro gera warning em PHP 8.

---

## 4. SINTEGRA

### 4.1 Leitura do arquivo modelo
`plans/testes-focus/SINTEGRA_FLORES E DECORACOES LTDA_06_2026.txt` — 141 linhas de 126 caracteres, terminador CRLF, encoding Latin-1.

| Registro | Qtd | Conteúdo | O sistema tem essa informação? |
|---|---|---|---|
| 10 / 11 | 1 / 1 | Cadastro do estabelecimento (CNPJ 49765943000102, IE 0045592750099, Montes Claros/MG, período 20260601–20260630) | Não — precisa virar configuração |
| 50 | 33 | NF-e mod. 55: **próprias** (`P`, nº 622–642) e **de terceiros/entradas** (`T`) | **Não** — não há emissão de mod. 55 nem módulo de entrada de nota de fornecedor |
| 51 | 1 | NF de entrada com IPI | **Não** |
| 54 | 61 | Itens dos documentos do registro 50 | **Não** |
| 61 | 3 | NFC-e mod. 65, série 1, totais por dia + faixa de numeração (883–920) | **Sim**, a partir de `notas_fiscais` (quando a emissão estiver ligada) |
| 61R | 12 | Resumo mensal por item (código, quantidade, valor bruto) | **Sim**, a partir de `notas_fiscais_itens` |
| 75 | 28 | Cadastro dos produtos citados (código, NCM, descrição, unidade, alíquotas) | **Sim**, a partir de `produtos` + classificação fiscal |
| 90 | 1 | Totalizações por tipo de registro | Sim (derivado) |

Conferência feita: soma dos 12 registros 61R = **R$ 67.035,20** = soma do campo “valor total” dos 3 registros 61 diários. O código do produto usado nos registros 54/61R/75 é o **ID do produto no ERP** (ex.: `9` = COROA DE FLORES, mesmo `cProd` dos XMLs em `plans/Xmls das notas fiscais*.zip`).

**Divergência encontrada no arquivo modelo:** no registro 61, o campo 14 (“Outras”, posições 110–122) está gravado com valor 10× maior que o campo 10 (ex.: 341376,00 contra 34137,60 no dia 10/06) e o campo 15 (alíquota) sai como `000␠`. Pelo layout do Convênio ICMS 57/95, com ICMS zerado (Simples Nacional) o campo 14 deveria repetir o total. Ou seja: **não dá para copiar o modelo byte a byte** — o gerador deve seguir o layout oficial, e essa diferença precisa ser confirmada com a contabilidade antes do primeiro envio.

### 4.2 O ponto central
O SINTEGRA é do **estabelecimento**, não do sistema. Um arquivo gerado só com o que este sistema conhece cobriria os registros **10, 11, 61, 61R, 75 e 90** — e ficaria sem os **50/51/54** (NF-e próprias modelo 55 e todas as entradas de fornecedor), que hoje representam 95 das 141 linhas do arquivo de junho. Entregar isso ao fisco como arquivo oficial seria uma declaração incompleta.

Caminhos possíveis (não excludentes):
1. **Só saídas por NFC-e** — gerador cobre 10/11/61/61R/75/90. Serve para conferência interna e para a contabilidade complementar. Rápido de fazer.
2. **Importador de XML** — carregar XMLs (próprias mod. 55 já existem em `plans/Xmls das notas fiscais*.zip`; entradas viriam do SEFAZ/DFe ou da contabilidade) numa tabela de documentos fiscais e gerar 50/51/54 a partir dela. É o que torna o arquivo completo.
3. **Arquivo completo de verdade** — depende também de resolver a seção 3.5 (toda venda gerar documento).

### 4.3 Desenho proposto (independe da opção escolhida)
- `application/libraries/Sintegra_service.php` — monta os registros e serializa em linhas de 126 posições, Latin-1 + CRLF, com formatadores `num()`, `txt()`, `data()`.
- `application/models/loja/Sintegra_model.php` — consultas: notas por dia (61), itens do mês (61R), produtos citados (75), documentos importados (50/51/54).
- `application/config/fiscal.php` — bloco `estabelecimento` (CNPJ, IE, razão social, endereço, contato, inscrição municipal) para os registros 10/11.
- `Loja_notas_fiscais::sintegra` + rota `loja/sintegra` — tela com mês/ano e download do `.txt`.
- Teste de aceite: gerar o arquivo de um mês já fechado e **comparar registro a registro** com o arquivo da contabilidade.

---

## 5. O que foi implementado (05/08/2026)

Decisões tomadas com o Samir: SINTEGRA no escopo **só NFC-e**; regra de emissão **mantida como está** (3.5 segue em aberto de propósito); correções de emissão e gerador feitos na mesma leva.

**Correções na emissão** — `libraries/Nfce_service.php`
- 3.1 resolvido: `desconto_em_reais()` converte o percentual usando só o valor dos produtos como base.
- 3.2 resolvido: entrega em UF diferente da do emitente para a emissão com mensagem pedindo NF-e modelo 55. CFOP fixo em 5102 e `local_destino` 1.
- 3.3 resolvido: `endereco_entrega()` envia o grupo `*_entrega` (logradouro, número, complemento, bairro, município, código IBGE, UF, CEP); entrega sem CPF/CNPJ do cliente é recusada com mensagem clara. Aceita CPF (11) e CNPJ (14).
- 3.4 resolvido: `ratear()` distribui frete e desconto proporcionalmente ao valor de cada item, com o resíduo do arredondamento no item de maior valor; `modalidade_frete` passa a 0 quando há frete cobrado.
- Novo: o CNPJ de `FOCUS_NFE_CNPJ_EMITENTE` é conferido contra o do estabelecimento em `config/fiscal.php` — servidor apontado para o emitente errado para antes de emitir.

**Base de dados** — `migrations/012_nfce_sintegra_montes_claros.sql`
- `notas_fiscais`: `valor_produtos`, `valor_frete`, `valor_desconto`, `valor_total` e índice em `autorizada_em`.
- `notas_fiscais_itens`: `produtos_id` (o registro 61R não pode depender de `pedido_produto`, que é apagado e reinserido a cada edição do pedido), `valor_frete` e `valor_desconto` rateados.

**Configuração** — `config/fiscal.php`
- Bloco `estabelecimento` (registros 10 e 11 + validação de operação interna), bloco `sintegra` (códigos 3/3/1) e `municipios_ibge` (hoje só Montes Claros; município fora da lista bloqueia a emissão com mensagem em vez de virar rejeição).

**Gerador do SINTEGRA**
- `libraries/Sintegra_service.php` — monta e serializa os registros 10, 11, 61, 61R, 75 e 90 em 126 posições, ASCII, CRLF. Registro incompleto ou valor que não cabe no campo aborta a geração em vez de sair torto.
- `models/loja/Sintegra_model.php` — resumo diário, resumo mensal por produto, produtos do período e as duas consultas de conferência (notas autorizadas sem número/valor e notas do período sem desfecho).
- `controllers/Loja_notas_fiscais.php` — `sintegra` (conferência) e `sintegra_download`; rotas `loja/sintegra` e `loja/sintegra/download`; item "Arquivo SINTEGRA" no menu.
- `views/notas_fiscais/sintegra.php` — totais do período, faixa de numeração por dia e dois avisos: notas que travam a geração e pedidos com nota não autorizada (que sairiam do arquivo e derrubariam o faturamento declarado).

**Testes** (rodam sem banco, com o CodeIgniter substituído por stubs):
- `tests/sintegra-layout.test.php` — 57 asserções de posição/tamanho campo a campo contra o layout do Convênio 57/95, incluindo a divergência do campo 14 do registro 61 encontrada no arquivo da contabilidade.
- `tests/nfce-payload.test.php` — 26 asserções sobre o payload: rateio, desconto percentual, bloqueio interestadual, endereço de entrega, identificação do destinatário e as validações que já existiam.

Como rodar (não há PHP na máquina; usei container):
```
docker run --rm -v "$PWD:/app" -w /app php:8.1-cli php tests/sintegra-layout.test.php
docker run --rm -v "$PWD:/app" -w /app php:8.1-cli php tests/nfce-payload.test.php
```

Nada disso foi replicado em `public_html/filialsalinas`: a filial não tem `Nfce_service` nem as migrations fiscais, e copiar o bloco `estabelecimento` de Montes Claros para lá criaria um emitente errado.

---

## 6. O que continua em aberto

1. **Decidir 3.5** — enquanto só vendas com pagamento não-dinheiro e quitadas gerarem cupom, o SINTEGRA gerado aqui é menor que o movimento real e serve para conferência, não para entrega.
2. **Série e numeração da NFC-e** (2.1) com a contabilidade, e os pré-requisitos da Focus (2.2: certificado A1 e CSC).
3. **Homologação** cobrindo: só dinheiro, cartão, misto, com frete, desconto em % e em R$, entrega em domicílio, balcão, e um pedido com vários itens para conferir o rateio.
4. **3.6 reconciliação**, **3.7 arquivamento do XML** e **3.8 cancelamento** — os três afetam a integridade do SINTEGRA.
5. **Confirmar com a contabilidade** o campo 14 do registro 61 (o arquivo deles diverge do layout) e o preenchimento de "isentas" × "outras" para CSOSN 102.
6. **Registros 50/51/54** — só entram com o importador de XML, se um dia o arquivo precisar ser completo.
