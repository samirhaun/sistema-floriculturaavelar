# Transcrição da tabela "Taxa de Entrega" (Avelar / Montes Claros)

Transcrição da foto enviada, coluna por coluna, na ordem em que aparece impresso. Arquivo: [montes-claros-transcricao.csv](montes-claros-transcricao.csv).

Esta é a lista da loja principal, **Avelar / Montes Claros** (banco `floricultura`) — confirmado pelo usuário (a marca "Flor Jardins" no crachá da foto não indica a filial Salinas como eu tinha presumido por engano). A lista equivalente da filial **Salinas** (banco `jardins_salin`) ainda precisa ser levantada separadamente.

**Status dos 3 pontos que ficaram incertos na primeira leitura:**

1. **"Residencial Terra Jardim" (coluna 3, linha 23)** — sem valor legível na foto. Decisão do usuário: deixar de fora do seed por enquanto; adicionar depois quando tiver o valor certo.
2. **"Nossa Senhora Aparecida" / "Nossa Senhora das Graças" (coluna 3, linhas 5-6)** — confirmado pelo usuário: são duas linhas separadas, R$12 cada. Já incluídas no seed.
3. **"Morada da Serra" duplicado** — nome repetido (mesmo valor, 15) no final da coluna 2 e início da coluna 3. Tratado como repetição de impressão, importado uma vez só (sem objeção do usuário).

Outras observações sem necessidade de resposta, só documentando por que a ordem foge do alfabético em alguns pontos (não é erro de transcrição, é assim que está impresso):
- "Santa Maria" aparece no meio da coluna 1 (entre "Cidade Nova Cidade" e "Cintra"), fora da ordem alfabética.
- "João Alves" aparece entre "Dr. Delfino Magalhães" e "Duque de Caxias" na coluna 2, também fora de ordem.
- "Canelas 2" aparece sozinho (sem "Canelas 1" em lugar nenhum da foto) — mantive como está, só por precaução caso não seja intencional.

## Próximos passos
- Depois da sua confirmação, o seed (`INSERT INTO bairros_frete ...`) vai pro banco `floricultura` (Avelar/Montes Claros), junto com a migration de schema.
- Levantar a lista equivalente da Salinas (banco `jardins_salin`) — antes de fotografar/digitar outra tabela, vale checar (agora que temos acesso direto ao banco) se já não existe algo aproveitável lá também.
