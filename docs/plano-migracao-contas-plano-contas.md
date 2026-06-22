# Plano de migracao de contas e plano de contas

Data do levantamento: 2026-06-03  
Periodo analisado: 2026-01-01 a 2026-06-30  
Escopo: `contas_pagar` e `contas_receber` das filiais Montes Claros e Salinas.

Este documento e somente um plano. Nao executar migracao sem revisao e aprovacao.

## Situacao atual

### Montes Claros

| Tabela | Total no periodo | Ja categorizadas em plano ativo | A revisar |
|---|---:|---:|---:|
| `contas_pagar` | 804 | 89 | 715 |
| `contas_receber` | 4906 | 4906 | 0 |

### Salinas

| Tabela | Total no periodo | Ja categorizadas em plano ativo | A revisar |
|---|---:|---:|---:|
| `contas_pagar` | 302 | 234 | 68 |
| `contas_receber` | 593 | 593 | 0 |

Conclusao: `contas_receber` ja esta ok nas duas filiais. A migracao deve focar em `contas_pagar`.

## Regras gerais da migracao

- Atualizar somente `contas_pagar` com `data_vencimento` entre `2026-01-01` e `2026-06-30`.
- Nao apagar planos antigos. Eles continuam inativos/soft deleted para manter historico e auditoria.
- Migrar lancamentos que ainda apontam para planos inativos, planos sem codigo, codigo `9` ou codigo `9.*`.
- Criar novos planos somente quando nao existir destino ativo adequado.
- Depois da migracao, validar que `contas_pagar` e `contas_receber` do periodo estejam 100% ligados a planos ativos e com codigo valido.

## Migracao proposta - Montes Claros

| Plano antigo | Qtd | Total | Destino proposto | Status do destino | Observacao |
|---|---:|---:|---|---|---|
| `15 / 9.16 / FORNECEDOR FLORES` | 102 | 143868.94 | Dividir por fornecedor | Misto | Ver regras detalhadas abaixo. |
| `30 / 9.3 / FINANCEIRO SALARIO` | 46 | 59983.19 | `3.2.5 / Folha de pagamento - Outros` | Novo | Criar abaixo de `3.2 / Despesas com Folha de Pagamento`. |
| `92 / 9.999 / OUTROS` | 58 | 38299.12 | `3.6.3 / Outras despesas` | Novo | Criar abaixo de `3.6 / Despesas Extras/Esporadicas`. |
| `117 / 9.99 / CARTAO DE CREDITO` | 4 | 33218.44 | `3.4.5 / Cartao de Credito` | Novo | Criar abaixo de `3.4 / Despesas Bancarias`. |
| `19 / 9.12 / FORNECEDOR OUTROS` | 49 | 26876.94 | `2.1.8 / Fornecedores - Outros` | Novo | Criar abaixo de `2.1 / Custos com Fornecedores e Insumos`. |
| `121 / sem codigo / MOTO TAXI EXTERNO` | 65 | 12174.00 | `2.5.8 / Mototaxi externo` | Novo | Criar abaixo de `2.5 / Custos Operacional/Logistico`. |
| `161 / sem codigo / EMPRESTIMO MARIO` | 1 | 12000.00 | `3.5.1 / Emprestimos` | Existente | Ja existe em Despesas Financeiras. |
| `135 / sem codigo / CAJU CARTAO ALIMENTACAO` | 7 | 7582.60 | `3.2.6 / Cartao Alimentacao` | Novo | Criar abaixo de `3.2 / Despesas com Folha de Pagamento`. |
| `124 / sem codigo / RECEITA FEDERAL` | 5 | 5593.82 | `3.3.5 / Receita Federal` | Novo | Criar abaixo de `3.3 / Despesas com Impostos`. |
| `101 / 9.92 / TARIFA TAXAS DA MAQUININHA` | 253 | 2223.90 | `2.3.2 / Taxa de Maquininha` | Existente | Ja existe em Custos Financeiros. |
| `102 / 9.93 / TARIFA TAXA DE ANTECIPACAO` | 94 | 1799.27 | `2.3.7 / Taxa de Antecipacao` | Novo | Criar abaixo de `2.3 / Custos Financeiros`. |
| `119 / sem codigo / DEPOSITO` | 5 | 1655.42 | `3.1.7 / Manutencao e Reparo` | Existente | Confirmar se todas sao materiais/reforma/manutencao. |
| `114 / 9.98 / AZ TEC CONTABIL` | 4 | 1126.72 | `3.1.11 / Assessoria Contabil` | Existente | Ja existe em Despesas Administrativas. |
| `146 / sem codigo / FREELANCER DIA DAS MAES` | 4 | 1110.00 | `2.4.3 / Freelancer` | Novo | Criar abaixo de `2.4 / Custos Mao de Obra - Producao/Comissao`. |
| `156 / sem codigo / EXTORNO DE PIX` | 4 | 684.00 | `3.4.6 / Estorno de Pix` | Novo | Criar abaixo de `3.4 / Despesas Bancarias`. |
| `127 / sem codigo / CLIMATIZE` | 2 | 680.00 | `3.1.7 / Manutencao e Reparo` | Existente | Confirmar se trata de manutencao/servico. |
| `162 / sem codigo / MULTA MOTO` | 3 | 625.75 | `3.6.4 / Multas` | Novo | Criar abaixo de `3.6 / Despesas Extras/Esporadicas`. |
| `14 / 9.17 / FORNECEDOR` | 1 | 591.80 | `2.1.8 / Fornecedores - Outros` | Novo | Mesmo destino de fornecedor generico. |
| `18 / 9.13 / FORNECEDOR PELUCIA` | 1 | 484.49 | `2.1.9 / Fornecedor Pelucia` | Novo | Criar abaixo de `2.1 / Custos com Fornecedores e Insumos`. |
| `163 / sem codigo / RATEIO FISIOTERAPIA` | 2 | 239.70 | `3.2.7 / Saude e beneficios` | Novo | Sugestao melhor que jogar em Outras despesas. Confirmar. |
| `118 / sem codigo / CERTIFICADO DIGITAL` | 1 | 155.00 | `3.1.20 / Certificado Digital` | Novo | Criar abaixo de `3.1 / Despesas Administrativas/Infraestrutura`. |
| `157 / sem codigo / RASTREADOR DA MOTO` | 3 | 139.15 | `2.5.9 / Rastreador de moto` | Novo | Criar abaixo de `2.5 / Custos Operacional/Logistico`. |
| `125 / sem codigo / GASOLINA` | 1 | 20.00 | `2.5.3 / Combustivel` | Existente | Ja existe em Custos Operacional/Logistico. |

### Regras detalhadas para `15 / 9.16 / FORNECEDOR FLORES`

| Condicao no lancamento | Destino proposto | Status |
|---|---|---|
| Descricao contem `ANDRE` | `2.1.1 / ANDRE CUSTODIO MARTINS (FLORES)` | Existente |
| Descricao contem `ARI` e nao contem `ROMARIO` | `2.1.2 / ARI CASSIMIRO DA SILVA (FLORES)` | Existente |
| Descricao contem `VALDIR` | `2.1.3 / VALDIR PEREIRA DA SILVA (FLORES)` | Existente |
| Descricao contem `GUSTAVO` | `2.1.4 / GUSTAVO CESTAS` | Existente |
| Descricao contem `JULIO` ou `URSO` | `2.1.5 / JULIO URSOS` | Existente |
| Descricao contem `PHELIP` | `2.1.6 / PHELIPPE TUROLLI SILVA (FLORES)` | Existente |
| Demais lancamentos | `2.1.7 / Fornecedores de flores - Outros` | Novo |

## Migracao proposta - Salinas

| Plano antigo | Qtd | Total | Destino proposto | Status do destino | Observacao |
|---|---:|---:|---|---|---|
| `37 / sem codigo / SALARIO FUNCIONARIO` | 1 | 1537.20 | `3.2.1 / Salario funcionario` | Novo | Criar abaixo de `3.2 / Despesas com Folha de Pagamento`. |
| `43 / sem codigo / OUTROS` | 26 | 1112.00 | `3.6.2 / Outras despesas` | Novo | Criar abaixo de `3.6 / Despesas Extras/Esporadicas`. Depois revisar itens individuais se precisar separar em material, alimentacao, doacao etc. |
| `38 / sem codigo / CARTAO ALIMENTACAO` | 2 | 500.00 | `3.2.2 / Cartao Alimentacao` | Novo | Criar abaixo de `3.2 / Despesas com Folha de Pagamento`. |
| `40 / sem codigo / UAI LOGISTICA CHOCOLATE` | 2 | 499.70 | `2.5.4 / UAI Logistica` | Novo | Confirmar se e frete/logistica. Se for compra de mercadoria, usar `2.1.3 / Fornecedor Chocolate`. |
| `35 / sem codigo / FREELANCER DIA DAS MAES` | 1 | 100.00 | `2.4.1 / Freelancer` | Novo | Criar abaixo de `2.4 / Custos Mao de Obra - Producao/Comissao`. |
| `34 / sem codigo / TAXA DE ANTECIPACAO` | 36 | 92.06 | `2.3.2 / Taxa de Antecipacao` | Novo | Separar da taxa normal de maquininha. |

### Renumeracao proposta do plano ativo atual de Salinas

| Plano atual | Destino proposto | Acao |
|---|---|---|
| `1 / RECEITA` | `1 / Receitas` | Renomear para padrao de Montes Claros. |
| `1.1 / Receitas de Pedidos` | `1.1 / Receitas de Pedidos` | Manter. |
| `2 / CUSTOS` | `2 / Custos` | Manter como grupo. |
| `2.16 / FORNECEDOR FLORES` | `2.1.1 / Fornecedor Flores` | Mover para fornecedores/insumos. |
| `2.15 / FORNECEDOR CESTAS` | `2.1.2 / Fornecedor Cestas` | Mover para fornecedores/insumos. |
| `2.14 / FORNECEDOR CHOCOLATE` | `2.1.3 / Fornecedor Chocolate` | Mover para fornecedores/insumos. |
| `2.13 / FORNECEDOR PELUCIA` | `2.1.4 / Fornecedor Pelucia` | Mover para fornecedores/insumos. |
| `2.12 / FORNECEDOR OUTROS` | `2.1.5 / Fornecedores - Outros` | Consolidar. |
| `2.17 / FORNECEDOR` | `2.1.5 / Fornecedores - Outros` | Consolidar com fornecedor generico. |
| `2.1 / COMPRAS SUPERMECADO` | `2.1.6 / Compras supermercado/insumos gerais` | Mover para fornecedores/insumos. |
| `2.10 / IMPOSTO PRODUTOS` | `2.2.1 / Impostos sobre produtos` | Mover para custos com impostos. |
| `2.9 / IMPOSTO FUNCIONARIOS` | `2.2.2 / Impostos funcionarios` | Mover para custos com impostos. |
| `2.7 / TARIFAS MAQUININHA CARTAO` | `2.3.1 / Taxa de Maquininha` | Mover para custos financeiros. |
| `2.0 / OPERACIONAIS FRETES` | `2.5.1 / Fretes` | Mover para operacional/logistico. |
| `2.1 / OPERACIONAIS ENTREGAS` | `2.5.2 / Entregas` | Corrigir codigo duplicado e mover. |
| `2.4 / OPERACIONAIS GASOLINA` | `2.5.3 / Combustivel` | Mover para operacional/logistico. |
| `2.3 / FINANCEIRO SALARIO` | `3.2.1 / Salario funcionario` | Salario deve ficar em despesas com folha. |
| `2.2 / FINANCEIRO BONIFICACAO` | `3.2.3 / Bonificacao` | Confirmar se e beneficio/folha antes de migrar. |
| `2.5 / OPERACIONAIS` | revisar/descontinuar | Manter inativo se for apenas grupo antigo generico. |
| `2.6 / FINANCEIRO ADMINISTRATIVO` | revisar/descontinuar | Migrar lancamentos caso a caso para despesas administrativas ou bancarias. |
| `2.8 / FINANCEIRO` | revisar/descontinuar | Migrar lancamentos caso a caso para custos financeiros, bancarias ou folha. |
| `2.11 / IMPOSTO` | revisar/descontinuar | Usar somente se nao couber em imposto sobre produtos/funcionarios. |
| `2.18 / CUSTO` | revisar/descontinuar | Plano generico deve sair do contexto operacional. |

## Novos planos propostos

### Montes Claros

| Codigo | Descricao | Pai | Tipo |
|---|---|---|---|
| `2.1.7` | Fornecedores de flores - Outros | `2.1` | Lancamento |
| `2.1.8` | Fornecedores - Outros | `2.1` | Lancamento |
| `2.1.9` | Fornecedor Pelucia | `2.1` | Lancamento |
| `2.3.7` | Taxa de Antecipacao | `2.3` | Lancamento |
| `2.4.3` | Freelancer | `2.4` | Lancamento |
| `2.5.8` | Mototaxi externo | `2.5` | Lancamento |
| `2.5.9` | Rastreador de moto | `2.5` | Lancamento |
| `3.1.20` | Certificado Digital | `3.1` | Lancamento |
| `3.2.5` | Folha de pagamento - Outros | `3.2` | Lancamento |
| `3.2.6` | Cartao Alimentacao | `3.2` | Lancamento |
| `3.2.7` | Saude e beneficios | `3.2` | Lancamento |
| `3.3.5` | Receita Federal | `3.3` | Lancamento |
| `3.4.5` | Cartao de Credito | `3.4` | Lancamento |
| `3.4.6` | Estorno de Pix | `3.4` | Lancamento |
| `3.6.3` | Outras despesas | `3.6` | Lancamento |
| `3.6.4` | Multas | `3.6` | Lancamento |

### Salinas

| Codigo | Descricao | Pai | Tipo |
|---|---|---|---|
| `1` | Receitas | sem pai | Grupo |
| `2.1` | Custos com Fornecedores e Insumos | `2` | Grupo |
| `2.1.1` | Fornecedor Flores | `2.1` | Lancamento |
| `2.1.2` | Fornecedor Cestas | `2.1` | Lancamento |
| `2.1.3` | Fornecedor Chocolate | `2.1` | Lancamento |
| `2.1.4` | Fornecedor Pelucia | `2.1` | Lancamento |
| `2.1.5` | Fornecedores - Outros | `2.1` | Lancamento |
| `2.1.6` | Compras supermercado/insumos gerais | `2.1` | Lancamento |
| `2.2` | Custos com Impostos | `2` | Grupo |
| `2.2.1` | Impostos sobre produtos | `2.2` | Lancamento |
| `2.2.2` | Impostos funcionarios | `2.2` | Lancamento |
| `2.3` | Custos Financeiros | `2` | Grupo |
| `2.3.1` | Taxa de Maquininha | `2.3` | Lancamento |
| `2.3.2` | Taxa de Antecipacao | `2.3` | Lancamento |
| `2.4` | Custos Mao de Obra - Producao/Comissao | `2` | Grupo |
| `2.4.1` | Freelancer | `2.4` | Lancamento |
| `2.5` | Custos Operacional/Logistico | `2` | Grupo |
| `2.5.1` | Fretes | `2.5` | Lancamento |
| `2.5.2` | Entregas | `2.5` | Lancamento |
| `2.5.3` | Combustivel | `2.5` | Lancamento |
| `2.5.4` | UAI Logistica | `2.5` | Lancamento |
| `3.1` | Despesas Administrativas/Infraestrutura | `3` | Grupo |
| `3.1.1` | Material de limpeza | `3.1` | Lancamento |
| `3.1.2` | Material de escritorio/embalagem | `3.1` | Lancamento |
| `3.1.3` | Alimentacao/almoco | `3.1` | Lancamento |
| `3.1.4` | Outras despesas administrativas | `3.1` | Lancamento |
| `3.2` | Despesas com Folha de Pagamento | `3` | Grupo |
| `3.2.1` | Salario funcionario | `3.2` | Lancamento |
| `3.2.2` | Cartao Alimentacao | `3.2` | Lancamento |
| `3.2.3` | Bonificacao | `3.2` | Lancamento |
| `3.3` | Despesas com Impostos | `3` | Grupo |
| `3.3.1` | IPTU | `3.3` | Lancamento |
| `3.4` | Despesas Bancarias | `3` | Grupo |
| `3.4.1` | Taxas bancarias | `3.4` | Lancamento |
| `3.6` | Despesas Extras/Esporadicas | `3` | Grupo |
| `3.6.1` | Doacoes/patrocinios | `3.6` | Lancamento |
| `3.6.2` | Outras despesas | `3.6` | Lancamento |

Observacao: em Salinas, a arvore deve seguir o mesmo desenho de Montes Claros, separando custo operacional/fornecedor/financeiro de despesas administrativas, folha e extras.

## Resumo da arvore proposta

### Montes Claros

```text
1 Receitas de Pedidos

2 Custos
  2.1 Custos com Fornecedores e Insumos
    2.1.1 ANDRE CUSTODIO MARTINS (FLORES)
    2.1.2 ARI CASSIMIRO DA SILVA (FLORES)
    2.1.3 VALDIR PEREIRA DA SILVA (FLORES)
    2.1.4 GUSTAVO CESTAS
    2.1.5 JULIO URSOS
    2.1.6 PHELIPPE TUROLLI SILVA (FLORES)
    2.1.7 Fornecedores de flores - Outros [novo]
    2.1.8 Fornecedores - Outros [novo]
    2.1.9 Fornecedor Pelucia [novo]
  2.2 Custos com Impostos
    2.2.1 Simples Nacional
  2.3 Custos Financeiros
    2.3.1 Tarifas de Boletos
    2.3.2 Taxa de Maquininha
    2.3.3 Taxa de Link de Pagamento
    2.3.4 Taxa de Pix Recebido
    2.3.5 PJBANK
    2.3.6 MERCADO PAGO
    2.3.7 Taxa de Antecipacao [novo]
  2.4 Custos Mao de Obra - Producao/Comissao
    2.4.1 Comissao por Venda
    2.4.2 Repasse por Cliente ou Servico
    2.4.3 Freelancer [novo]
  2.5 Custos Operacional/Logistico
    2.5.1 Frete (Recebimento de Mercadorias)
    2.5.2 Frete (Envio de Mercadorias)
    2.5.3 Combustivel
    2.5.4 MOTO TAXI VINICIUS
    2.5.5 MOTO TAXI EDILSON
    2.5.6 REISA EMBALAGEM
    2.5.7 UAI LOGISTICA
    2.5.8 Mototaxi externo [novo]
    2.5.9 Rastreador de moto [novo]

3 Despesas
  3.1 Despesas Administrativas/Infraestrutura
    3.1.1 Aluguel
    3.1.2 Agua
    3.1.3 Luz
    3.1.4 Internet
    3.1.5 Sistemas
    3.1.6 Telefonia
    3.1.7 Manutencao e Reparo
    3.1.8 Material de Limpeza
    3.1.9 Material de Escritorio
    3.1.10 Agua Mineral
    3.1.11 Assessoria Contabil
    3.1.12 Assessoria Financeira
    3.1.13 Assessoria Juridica
    3.1.14 KENEDY SISTEMAS
    3.1.15 PADARIA DELICIA DE PAO
    3.1.16 OPTIMUS GESTAO
    3.1.17 ENMED SAUDE E SEGURANCA DO TRABALHO
    3.1.18 SINDICATO DOS EMPREGADOS
    3.1.19 GAS RAPIDO
    3.1.20 Certificado Digital [novo]
  3.2 Despesas com Folha de Pagamento
    3.2.1 Vendedor
    3.2.2 Financeiro
    3.2.3 Gerencia
    3.2.4 CONVENIO MINAS BRASIL
    3.2.5 Folha de pagamento - Outros [novo]
    3.2.6 Cartao Alimentacao [novo]
    3.2.7 Saude e beneficios [novo]
  3.3 Despesas com Impostos
    3.3.1 FGTS
    3.3.2 INSS
    3.3.3 IPVA
    3.3.4 IPTU
    3.3.5 Receita Federal [novo]
  3.4 Despesas Bancarias
    3.4.1 Manutencao de Conta Bancaria
    3.4.2 Juros
    3.4.3 Taxa Cofre
    3.4.4 Tarifa de Pix Enviado
    3.4.5 Cartao de Credito [novo]
    3.4.6 Estorno de Pix [novo]
  3.5 Despesas Financeiras
    3.5.1 Emprestimos
    3.5.2 Financiamentos
  3.6 Despesas Extras/Esporadicas
    3.6.1 Bolo de Aniversario
    3.6.2 Presente
    3.6.3 Outras despesas [novo]
    3.6.4 Multas [novo]
  3.7 Despesas Com Investimentos
    3.7.1 Compra de Equipamento
    3.7.2 Compra de Curso
    3.7.3 Compra de Treinamento
    3.7.4 Compra de Bens
```

### Salinas

```text
1 Receitas
  1.1 Receitas de Pedidos

2 Custos
  2.1 Custos com Fornecedores e Insumos
    2.1.1 Fornecedor Flores
    2.1.2 Fornecedor Cestas
    2.1.3 Fornecedor Chocolate
    2.1.4 Fornecedor Pelucia
    2.1.5 Fornecedores - Outros
    2.1.6 Compras supermercado/insumos gerais
  2.2 Custos com Impostos
    2.2.1 Impostos sobre produtos
    2.2.2 Impostos funcionarios
  2.3 Custos Financeiros
    2.3.1 Taxa de Maquininha
    2.3.2 Taxa de Antecipacao [novo]
  2.4 Custos Mao de Obra - Producao/Comissao
    2.4.1 Freelancer [novo]
  2.5 Custos Operacional/Logistico
    2.5.1 Fretes
    2.5.2 Entregas
    2.5.3 Combustivel
    2.5.4 UAI Logistica [novo]

3 Despesas
  3.1 Despesas Administrativas/Infraestrutura
    3.1.1 Material de limpeza [novo]
    3.1.2 Material de escritorio/embalagem [novo]
    3.1.3 Alimentacao/almoco [novo]
    3.1.4 Outras despesas administrativas [novo]
  3.2 Despesas com Folha de Pagamento
    3.2.1 Salario funcionario [novo]
    3.2.2 Cartao Alimentacao [novo]
    3.2.3 Bonificacao [novo]
  3.3 Despesas com Impostos
    3.3.1 IPTU [novo]
  3.4 Despesas Bancarias
    3.4.1 Taxas bancarias [novo]
  3.6 Despesas Extras/Esporadicas
    3.6.1 Doacoes/patrocinios [novo]
    3.6.2 Outras despesas [novo]
```

## Validacoes pos-migracao

Depois de aprovar e executar:

1. `contas_pagar` de Montes Claros deve ficar com `revisar = 0` no periodo.
2. `contas_pagar` de Salinas deve ficar com `revisar = 0` no periodo.
3. `contas_receber` deve continuar com `revisar = 0` nas duas filiais.
4. Nenhum lancamento do periodo deve apontar para plano `ativo = 0`, plano sem codigo, codigo `9` ou codigo `9.*`.
5. Os planos antigos devem continuar no banco como inativos para historico.
