-- Frete automático por bairro (filial Salinas / banco jardins_salin).
-- Ver docs/frete-bairros/ na raiz do repo para o plano completo.

CREATE TABLE `bairros_frete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `nome_normalizado` varchar(150) NOT NULL,
  `valor_frete` decimal(8,2) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_nome_normalizado` (`nome_normalizado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pedidos`
  ADD COLUMN `bairros_frete_id` int(11) DEFAULT NULL AFTER `bairro_entrega`,
  ADD KEY `fk_pedidos_bairros_frete1_idx` (`bairros_frete_id`),
  ADD CONSTRAINT `fk_pedidos_bairros_frete1` FOREIGN KEY (`bairros_frete_id`) REFERENCES `bairros_frete` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- Seed dos bairros/valores da Salinas ainda pendente -- não existe lista
-- fotografada ainda (a foto que temos é de Montes Claros/Avelar) e a
-- tabela `transparencia` deste banco está vazia (checado em produção).
