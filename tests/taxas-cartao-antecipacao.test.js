'use strict';

const assert = require('node:assert/strict');

function arredondarCentavos(valor) {
  return Math.round((valor + Number.EPSILON) * 100) / 100;
}

function simularLote(vendas, percentualAntecipacao) {
  const porBandeira = new Map();

  for (const venda of vendas) {
    const taxaMaquininha = arredondarCentavos(venda.bruto * venda.percentualMaquininha / 100);
    const liquidoMaquininha = arredondarCentavos(venda.bruto - taxaMaquininha);
    const grupo = porBandeira.get(venda.bandeira) || {
      bandeira: venda.bandeira,
      bruto: 0,
      taxaMaquininha: 0,
      baseAntecipacao: 0,
    };

    grupo.bruto = arredondarCentavos(grupo.bruto + venda.bruto);
    grupo.taxaMaquininha = arredondarCentavos(grupo.taxaMaquininha + taxaMaquininha);
    grupo.baseAntecipacao = arredondarCentavos(grupo.baseAntecipacao + liquidoMaquininha);
    porBandeira.set(venda.bandeira, grupo);
  }

  return [...porBandeira.values()].map(grupo => {
    grupo.taxaAntecipacao = arredondarCentavos(grupo.baseAntecipacao * percentualAntecipacao / 100);
    grupo.liquidoBanco = arredondarCentavos(grupo.baseAntecipacao - grupo.taxaAntecipacao);
    return grupo;
  });
}

const vendas08Julho = [
  { bandeira: 'Mastercard', bruto: 33.00, percentualMaquininha: 1.45 },
  { bandeira: 'Visa', bruto: 145.00, percentualMaquininha: 1.45 },
  { bandeira: 'Visa', bruto: 130.00, percentualMaquininha: 1.45 },
  { bandeira: 'Elo', bruto: 258.00, percentualMaquininha: 1.95 },
  { bandeira: 'Elo', bruto: 280.00, percentualMaquininha: 1.95 },
];

const resultado = simularLote(vendas08Julho, 1.489);
const esperado = {
  Mastercard: { bruto: 33.00, taxaMaquininha: 0.48, baseAntecipacao: 32.52, taxaAntecipacao: 0.48, liquidoBanco: 32.04 },
  Visa: { bruto: 275.00, taxaMaquininha: 3.99, baseAntecipacao: 271.01, taxaAntecipacao: 4.04, liquidoBanco: 266.97 },
  Elo: { bruto: 538.00, taxaMaquininha: 10.49, baseAntecipacao: 527.51, taxaAntecipacao: 7.85, liquidoBanco: 519.66 },
};

for (const lote of resultado) {
  assert.deepEqual(lote, { bandeira: lote.bandeira, ...esperado[lote.bandeira] });
}

const total = resultado.reduce((soma, lote) => ({
  bruto: arredondarCentavos(soma.bruto + lote.bruto),
  taxaMaquininha: arredondarCentavos(soma.taxaMaquininha + lote.taxaMaquininha),
  taxaAntecipacao: arredondarCentavos(soma.taxaAntecipacao + lote.taxaAntecipacao),
  liquidoBanco: arredondarCentavos(soma.liquidoBanco + lote.liquidoBanco),
}), { bruto: 0, taxaMaquininha: 0, taxaAntecipacao: 0, liquidoBanco: 0 });

assert.deepEqual(total, {
  bruto: 846.00,
  taxaMaquininha: 14.96,
  taxaAntecipacao: 12.37,
  liquidoBanco: 818.67,
});

console.table(resultado);
console.log('TOTAL', total);
console.log('OK: a simulacao sem banco reproduziu todos os valores informados pelo SIPAG.');
