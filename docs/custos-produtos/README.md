# Custo dos produtos (valor_custo)

O campo de custo por produto (`produtos.valor_custo`) e o relatório de lucro por produto ("Vendas por produto", dentro de Relatórios > Vendas no `producao/admin`) **já existiam** no sistema antes desse trabalho -- só estavam quase todos vazios (8 de 1029 produtos preenchidos). Não foi preciso construir nada novo, só popular o campo.

## O que foi feito em 2026-07-25

- [custos-preliminares-usuario.tsv](custos-preliminares-usuario.tsv): tabela preliminar de custos que o usuário levantou (299 produtos, 43 com custo já calculado, o resto ainda "-").
- Cruzamento automático por nome do produto (normalizado: maiúsculas, sem acento) contra a tabela `produtos` do banco `floricultura`, com desempate por preço de venda quando havia mais de um produto com o mesmo nome.
- Resultado: 44 produtos atualizados (`UPDATE produtos SET valor_custo = ...`) -- os 42 que bateram limpo, mais os 2 cadastros duplicados de "BEGONIA ROSA" a R$70 (decisão do usuário; havia um terceiro "BEGONIA ROSA" a R$60 que ficou de fora).
- Nenhuma linha da tabela ficou sem produto correspondente no banco.

## Como continuar

- O relatório "Vendas por produto" já mostra custo e lucro (R$ e %) pra qualquer produto com `valor_custo` preenchido -- os outros ~980 produtos continuam aparecendo com "-" até serem preenchidos.
- Preencher custo manualmente: tela de edição do produto (`Loja > Produtos`), campo "Custo un.".
- Preencher em lote via nova leva de dados: repetir o processo -- gerar um TSV/CSV com produto + custo, cruzar por nome contra a tabela `produtos` (normalizado) e aplicar via `UPDATE`, revisando antes os casos ambíguos (nome duplicado) e sem match.
- Vale ficar de olho em produtos onde `valor_custo` acabou igual ao `valor` de venda (margem zero) -- pode ser erro de digitação/pesquisa e não reflete uma margem real.
