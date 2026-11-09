-- Projeto didático: Cadastro de Produtos
-- Execute este arquivo no phpMyAdmin ou no cliente MySQL.

CREATE DATABASE IF NOT EXISTS loja_escola
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE loja_escola;

CREATE TABLE IF NOT EXISTS produtos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT UNSIGNED NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dados iniciais para testar o sistema.
INSERT INTO produtos (nome, categoria, preco, estoque) VALUES
('Teclado USB', 'Periféricos', 59.90, 12),
('Mouse óptico', 'Periféricos', 35.50, 20),
('Monitor 24 polegadas', 'Monitores', 799.00, 5);
