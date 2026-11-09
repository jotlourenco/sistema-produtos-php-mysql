<?php
declare(strict_types=1);

/**
 * Escapa valores antes de mostrá-los no HTML.
 * Ajuda a prevenir XSS quando os dados são exibidos na página.
 */
function e(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Cria e mantém um token CSRF na sessão. */
function tokenCsrf(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/** Confere se o token enviado pelo formulário é válido. */
function validarCsrf(): void
{
    $tokenEnviado = $_POST['csrf_token'] ?? '';
    $tokenSessao = $_SESSION['csrf_token'] ?? '';

    if (!is_string($tokenEnviado) || !is_string($tokenSessao)
        || $tokenSessao === ''
        || !hash_equals($tokenSessao, $tokenEnviado)) {
        http_response_code(403);
        exit('Solicitação inválida. Atualize a página e tente novamente.');
    }
}

/** Converte e valida os dados recebidos do formulário. */
function validarProduto(array $dados): array
{
    $nome = trim((string) ($dados['nome'] ?? ''));
    $categoria = trim((string) ($dados['categoria'] ?? ''));
    $precoTexto = str_replace(',', '.', trim((string) ($dados['preco'] ?? '')));
    $estoqueTexto = trim((string) ($dados['estoque'] ?? ''));

    $erros = [];

    if ($nome === '' || mb_strlen($nome) > 100) {
        $erros[] = 'Informe um nome com até 100 caracteres.';
    }

    if ($categoria === '' || mb_strlen($categoria) > 50) {
        $erros[] = 'Informe uma categoria com até 50 caracteres.';
    }

    if (!is_numeric($precoTexto) || (float) $precoTexto < 0 || (float) $precoTexto > 99999999.99) {
        $erros[] = 'Informe um preço válido e não negativo.';
    }

    $estoque = filter_var($estoqueTexto, FILTER_VALIDATE_INT);
    if ($estoque === false || $estoque < 0) {
        $erros[] = 'Informe uma quantidade inteira igual ou maior que zero.';
    }

    if ($erros !== []) {
        return ['erros' => $erros, 'produto' => [
            'nome' => $nome,
            'categoria' => $categoria,
            'preco' => $precoTexto,
            'estoque' => $estoqueTexto,
        ]];
    }

    return ['erros' => [], 'produto' => [
        'nome' => $nome,
        'categoria' => $categoria,
        'preco' => number_format((float) $precoTexto, 2, '.', ''),
        'estoque' => $estoque,
    ]];
}
