# Recategorização de produtos ("Outros")

Em 2026-07-25, 681 dos 918 produtos ativos (74%) estavam na categoria "Outros" — só existiam 12 categorias cadastradas, a maioria quase sem uso.

## O que foi feito

Categorização automática por palavra-chave no título do produto (normalizado: maiúsculas, sem acento), aplicada direto no banco `floricultura`:

| Categoria | Produtos | Palavras-chave |
|---|---|---|
| Cestas e Caixas *(nova, id 24)* | 122 | CESTA, CESTAS, BOX, CAIXA |
| Plantas *(nova, id 23)* | 90 | BEGONIA, SAMAMBAIA, ANTURIO, FICUS, CACTO, SUCULENTA, JIBOIA, MONSTERA, ALOCACIA, MARANTA, AZALEIA, BROMELIA, SINGONIO, FITONIA, ORQUIDEA, COSTELA, MOSTELA, AVENCAO, MUDA, ROSEIRA(S) |
| Mimos e Chocolates *(existente, id 19)* | +59 (25→84) | CHOCOLATE, FERRERO, PELUCIA, URSO, CANECA, VINHO, CARTAO, CARTA, ESPUMANTE, RAFAELOO, BALAO, BONECA, BIS |
| Buquês diversos *(nova, id 22)* | 54 | BUQUE/BUQUÊ (bouquets que não são de rosas -- esses já ficam em "Buque de rosas") |
| Arranjos *(nova, id 21)* | 38 | ARRANJO, ARRANJOS |
| Vasos *(existente, id 20)* | +38 (135→177) | VASO, VASINHO |
| Flores avulsas *(nova, id 25)* | 33 | PACOTE, UNIDADE, MACO, GALHO, CORTE |

Regras aplicadas em ordem de prioridade (primeira que bater, ganha) -- um produto com "ARRANJO... COM VASO" cai em Arranjos, não em Vasos, por exemplo.

Também consolidadas 3 categorias quase vazias dentro de "Vasos": Vasos de girassol (0), Vasos de rosas (1), Vasos de lírios (3) -- essas 3 categorias continuam existindo (não foram apagadas) mas ficaram com 0 produtos.

**Resultado:** "Outros" caiu de 681 para 247 produtos (64% recategorizado automaticamente).

## O que falta

Os 247 restantes não bateram em nenhuma regra -- nomes muito específicos/únicos (ex: "ARRANJOS Jane Loiola-30/04/2024", nomes de espécie de planta não cobertos pela lista de palavras-chave, erros de digitação como "AARANJO"). Decisão do usuário: deixar em "Outros" por enquanto, sem tela de revisão manual dedicada (pode ser retomado depois, mesmo padrão da tela de custos).

## Como reproduzir/estender

Script de matching + aplicação em `/tmp` (não versionado); lógica: normaliza o título (maiúsculas, sem acento), separa em palavras, testa contra a lista de keywords por categoria em ordem de prioridade. Pra novas leituras, ajustar as REGRAS e rodar de novo só nos produtos ainda em "Outros".
