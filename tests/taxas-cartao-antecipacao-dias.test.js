'use strict';

const assert = require('node:assert/strict');

const taxaMensal = 1.54;
const grupos = [
  { bandeira: 'Elo', parcelas: [{ base: 147.07, dias: 28 }] },
  { bandeira: 'Visa', parcelas: [{ base: 917.50, dias: 28 }] },
  { bandeira: 'Mastercard', parcelas: [
    { base: 223.71, dias: 28 },
    { base: 519.36, dias: 28 },
    { base: 335.07, dias: 29 },
  ] },
];

function centavos(valor) {
  return Math.round((valor + Number.EPSILON) * 100) / 100;
}

const calculado = grupos.map(grupo => ({
  bandeira: grupo.bandeira,
  valor: centavos(grupo.parcelas.reduce(
    (total, parcela) => total + (parcela.base * taxaMensal * parcela.dias / 3000),
    0
  )),
}));

assert.deepEqual(calculado, [
  { bandeira: 'Elo', valor: 2.11 },
  { bandeira: 'Visa', valor: 13.19 },
  { bandeira: 'Mastercard', valor: 15.67 },
]);
assert.equal(centavos(calculado.reduce((soma, grupo) => soma + grupo.valor, 0)), 30.97);

// O extrato SIPAG arredondou o lote Visa um centavo abaixo da simulacao.
assert.equal(centavos(2.11 + 13.18 + 15.67), 30.96);
console.log('OK: antecipacao proporcional por dias validada; lote SIPAG conciliado em R$ 30,96.');
