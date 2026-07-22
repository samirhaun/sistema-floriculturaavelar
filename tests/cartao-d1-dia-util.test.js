'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

function proximoDiaUtil(data) {
  const resultado = new Date(`${data}T00:00:00Z`);
  resultado.setUTCDate(resultado.getUTCDate() + 1);

  while (resultado.getUTCDay() === 0 || resultado.getUTCDay() === 6) {
    resultado.setUTCDate(resultado.getUTCDate() + 1);
  }

  return resultado.toISOString().slice(0, 10);
}

// Venda em sexta: receita e taxa devem liquidar na segunda.
const dataVenda = '2026-07-17';
const dataLiquidacao = proximoDiaUtil(dataVenda);
assert.equal(dataLiquidacao, '2026-07-20');
assert.equal(dataLiquidacao, proximoDiaUtil(dataVenda));

function dataLiquidacaoCartao(dataPago) {
  return proximoDiaUtil(dataPago);
}

assert.equal(dataLiquidacaoCartao(dataVenda), '2026-07-20');

const controladores = [
  'public_html/producao/admin/application/controllers/Site_pedidos.php',
  'public_html/filialsalinas/application/controllers/Site_pedidos.php',
];

for (const controlador of controladores) {
  const codigo = fs.readFileSync(path.resolve(__dirname, '..', controlador), 'utf8');

  // data_pago e a data informada; data_liquidacao recebe o D+1 útil.
  assert.match(
    codigo,
    /\$dados_receita\['data_liquidacao'\] = \(\$eh_cartao_d1/
  );
  assert.match(codigo, /\$this->proximo_dia_util\(\$dados_receita\['data_pago'\]\)/);
  assert.match(codigo, /contas_receber_id/);
  assert.match(codigo, /\$dados_receita\['data_liquidacao'\] = \$conta_original->data_liquidacao/);

  // A taxa acompanha a liquidação da receita; não recebe um segundo D+1.
  assert.match(codigo, /\$dados_receita\['data_pago_taxa'\] = \$dados_receita\['data_liquidacao'\];/);
}

const modelosTaxa = [
  'public_html/producao/admin/application/models/loja/Taxas_cartao_model.php',
  'public_html/filialsalinas/application/models/loja/Taxas_cartao_model.php',
];

for (const modelo of modelosTaxa) {
  const codigo = fs.readFileSync(path.resolve(__dirname, '..', modelo), 'utf8');

  // A antecipacao agrupada usa a mesma liquidacao da receita, nao D+2.
  assert.match(codigo, /\$data_lote = \$data_pagamento \?: null;/);
  assert.doesNotMatch(codigo, /\$data_lote = \$data_pagamento \? \$this->proximo_dia_util/);
  assert.match(codigo, /forma_pgto, data_vencimento, data_pago, data_liquidacao, maquininha_cartao_id/);
  assert.match(codigo, /data_origem_lote/);
  assert.match(codigo, /data_base_antecipacao/);

  // O recálculo precisa trabalhar com a data de pagamento real da receita.
  assert.match(codigo, /cr\.data_vencimento, cr\.data_pago, cr\.data_liquidacao, COALESCE\(tm\.valor, 0\)/);
}

const modelosFluxo = [
  'public_html/producao/admin/application/models/loja/Pedidos_model.php',
  'public_html/filialsalinas/application/models/loja/Pedidos_model.php',
];

for (const modelo of modelosFluxo) {
  const codigo = fs.readFileSync(path.resolve(__dirname, '..', modelo), 'utf8');

  // Nos relatórios por pagamento, cartão entra pela liquidação; os demais, pela data paga.
  assert.match(codigo, /COALESCE\(contas_receber\.data_liquidacao, contas_receber\.data_pago\) BETWEEN/);
  assert.doesNotMatch(codigo, /contas_receber\.data_pago BETWEEN/);
}

const formularios = [
  'public_html/producao/admin/application/views/pedidos/formulario.php',
  'public_html/filialsalinas/application/views/pedidos/formulario.php',
];

for (const formulario of formularios) {
  const codigo = fs.readFileSync(path.resolve(__dirname, '..', formulario), 'utf8');
  assert.match(codigo, /Data Pago:/);
  assert.match(codigo, /liquidacao prevista \(D\+1 util\)/);
  assert.match(codigo, /\$pagamento\.prop\('readonly', false\)/);
}

console.log('OK: cartão em sexta liquida receita e taxa na segunda-feira.');
