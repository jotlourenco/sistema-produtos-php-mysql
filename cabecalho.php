<?php
// O arquivo que inclui este cabeçalho deve iniciar a sessão antes de qualquer HTML.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($tituloPagina ?? 'Cadastro de Produtos') ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topo">
    <div class="container topo-conteudo">
        <a class="marca" href="index.php">Loja Escola</a>
        <nav>
            <a href="index.php">Produtos</a>
            <a href="cadastrar.php">Cadastrar produto</a>
        </nav>
    </div>
</header>
<main class="container principal">
