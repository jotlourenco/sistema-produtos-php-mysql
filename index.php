<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/funcoes.php';

$tituloPagina = 'Produtos cadastrados';

$sql = 'SELECT id, nome, categoria, preco, estoque FROM produtos ORDER BY id DESC';
$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll();

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

require __DIR__ . '/cabecalho.php';
?>

<section class="cabecalho-pagina">
    <div>
        <p class="sobretitulo">GERENCIAMENTO</p>
        <h1>Produtos cadastrados</h1>
        <p class="texto-suave">Consulte, cadastre, edite ou exclua os produtos da loja.</p>
    </div>
    <a class="botao primario" href="cadastrar.php">+ Novo produto</a>
</section>

<?php if ($mensagem !== ''): ?>
    <div class="alerta sucesso"><?= e($mensagem) ?></div>
<?php endif; ?>

<div class="cartao tabela-responsiva">
    <?php if ($produtos === []): ?>
        <div class="estado-vazio">
            <h2>Nenhum produto cadastrado</h2>
            <p>Comece adicionando o primeiro produto.</p>
            <a class="botao primario" href="cadastrar.php">Cadastrar produto</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= e($produto['id']) ?></td>
                    <td class="nome-produto"><?= e($produto['nome']) ?></td>
                    <td><?= e($produto['categoria']) ?></td>
                    <td>R$ <?= number_format((float) $produto['preco'], 2, ',', '.') ?></td>
                    <td><?= e($produto['estoque']) ?></td>
                    <td>
                        <div class="acoes">
                            <a class="botao pequeno secundario"
                               href="editar.php?id=<?= e($produto['id']) ?>">Editar</a>

                            <form action="excluir.php" method="post"
                                  onsubmit="return confirm('Deseja realmente excluir este produto?');">
                                <input type="hidden" name="csrf_token" value="<?= e(tokenCsrf()) ?>">
                                <input type="hidden" name="id" value="<?= e($produto['id']) ?>">
                                <button class="botao pequeno perigo" type="submit">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/rodape.php'; ?>
