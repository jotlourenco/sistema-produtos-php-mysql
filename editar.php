<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/funcoes.php';

$tituloPagina = 'Editar produto';
$erros = [];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

if (!$id || $id < 1) {
    http_response_code(400);
    exit('Identificador de produto inválido.');
}

$stmt = $pdo->prepare('SELECT id, nome, categoria, preco, estoque FROM produtos WHERE id = :id');
$stmt->execute([':id' => $id]);
$produto = $stmt->fetch();

if (!$produto) {
    http_response_code(404);
    exit('Produto não encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validarCsrf();

    $resultado = validarProduto($_POST);
    $erros = $resultado['erros'];
    $dados = $resultado['produto'];

    if ($erros === []) {
        $sql = 'UPDATE produtos
                SET nome = :nome, categoria = :categoria,
                    preco = :preco, estoque = :estoque
                WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':categoria' => $dados['categoria'],
            ':preco' => $dados['preco'],
            ':estoque' => $dados['estoque'],
            ':id' => $id,
        ]);

        $_SESSION['mensagem'] = 'Produto atualizado com sucesso.';
        header('Location: index.php');
        exit;
    }

    $produto = array_merge($produto, $dados);
}

require __DIR__ . '/cabecalho.php';
?>

<section class="formulario-pagina">
    <a class="voltar" href="index.php">← Voltar para produtos</a>
    <h1>Editar produto</h1>
    <p class="texto-suave">Altere as informações e salve as modificações.</p>

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

    <form class="cartao formulario" method="post" action="editar.php">
        <input type="hidden" name="csrf_token" value="<?= e(tokenCsrf()) ?>">
        <input type="hidden" name="id" value="<?= e($id) ?>">

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
            <button class="botao primario" type="submit">Salvar alterações</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/rodape.php'; ?>
