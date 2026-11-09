<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/funcoes.php';

$tituloPagina = 'Cadastrar produto';
$erros = [];
$produto = ['nome' => '', 'categoria' => '', 'preco' => '', 'estoque' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validarCsrf();

    $resultado = validarProduto($_POST);
    $erros = $resultado['erros'];
    $produto = $resultado['produto'];

    if ($erros === []) {
        $sql = 'INSERT INTO produtos (nome, categoria, preco, estoque)
                VALUES (:nome, :categoria, :preco, :estoque)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($produto);

        $_SESSION['mensagem'] = 'Produto cadastrado com sucesso.';
        header('Location: index.php');
        exit;
    }
}

require __DIR__ . '/cabecalho.php';
?>

<section class="formulario-pagina">
    <a class="voltar" href="index.php">← Voltar para produtos</a>
    <h1>Cadastrar produto</h1>
    <p class="texto-suave">Preencha os campos para adicionar um produto ao banco de dados.</p>

    <?php if ($erros !== []): ?>
        <div class="alerta erro">
            <strong>Confira os dados informados:</strong>
            <ul>
                <?php foreach ($erros as $erro): ?>
                    <li><?= e($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="cartao formulario" method="post" action="cadastrar.php">
        <input type="hidden" name="csrf_token" value="<?= e(tokenCsrf()) ?>">

        <label for="nome">Nome do produto</label>
        <input id="nome" name="nome" type="text" maxlength="100"
               value="<?= e($produto['nome']) ?>" required>

        <label for="categoria">Categoria</label>
        <input id="categoria" name="categoria" type="text" maxlength="50"
               value="<?= e($produto['categoria']) ?>" required>

        <div class="grade-campos">
            <div>
                <label for="preco">Preço (R$)</label>
                <input id="preco" name="preco" type="number" min="0" step="0.01"
                       value="<?= e($produto['preco']) ?>" required>
            </div>
            <div>
                <label for="estoque">Quantidade em estoque</label>
                <input id="estoque" name="estoque" type="number" min="0" step="1"
                       value="<?= e($produto['estoque']) ?>" required>
            </div>
        </div>

        <div class="botoes-formulario">
            <a class="botao secundario" href="index.php">Cancelar</a>
            <button class="botao primario" type="submit">Salvar produto</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/rodape.php'; ?>
