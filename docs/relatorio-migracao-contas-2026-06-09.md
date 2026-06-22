# Relatorio da migracao de contas

Data de execucao: 2026-06-09

## Snapshot antes da migracao

Snapshot criado antes de qualquer alteracao:

- `backups/db-snapshots/20260609-085454`

Tabelas salvas:

- `plano_contas`
- `contas_pagar`
- `contas_receber`

Linhas no snapshot:

| Filial | `plano_contas` | `contas_pagar` | `contas_receber` |
|---|---:|---:|---:|
| Montes Claros | 151 | 6115 | 28614 |
| Salinas | 33 | 1613 | 3952 |

## Scripts executados

Scripts e resultados salvos em:

- `backups/db-migrations/20260609-090048`

Arquivos:

- `montes-claros-migracao-contas.sql`
- `montes-claros-resultado.txt`
- `salinas-migracao-contas.sql`
- `salinas-resultado.txt`

## Escopo aplicado

- Periodo migrado: `2026-01-01` a `2026-06-30`
- Tabela atualizada: `contas_pagar`
- Estrutura de plano de contas atualizada/criada conforme o plano em `docs/plano-migracao-contas-plano-contas.md`
- `contas_receber` nao foi recategorizada; foi apenas validada depois da migracao

## Totais migrados

| Filial | Lancamentos migrados em `contas_pagar` |
|---|---:|
| Montes Claros | 733 |
| Salinas | 71 |

## Validacao apos migracao

### Montes Claros

| Tabela | Total no periodo | OK | A revisar |
|---|---:|---:|---:|
| `contas_pagar` | 823 | 823 | 0 |
| `contas_receber` | 5088 | 5088 | 0 |

Planos de conta apos migracao:

| Status | Qtd |
|---|---:|
| Ativos | 92 |
| Inativos | 75 |

### Salinas

| Tabela | Total no periodo | OK | A revisar |
|---|---:|---:|---:|
| `contas_pagar` | 309 | 309 | 0 |
| `contas_receber` | 604 | 604 | 0 |

Planos de conta apos migracao:

| Status | Qtd |
|---|---:|
| Ativos | 39 |
| Inativos | 16 |

## Observacoes

- Nao restaram lancamentos do periodo apontando para plano inativo, plano sem codigo, codigo `9` ou codigo `9.*`.
- Nao foram encontrados codigos ativos duplicados em `plano_contas` nas duas filiais.
- Para rollback, usar o snapshot `backups/db-snapshots/20260609-085454`.
