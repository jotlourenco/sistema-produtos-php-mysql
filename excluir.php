<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método não permitido.');
}

validarCsrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('Identificador de produto inválido.');
}

$stmt = $pdo->prepare('DELETE FROM produtos WHERE id = :id');
$stmt->execute([':id' => $id]);

$_SESSION['mensagem'] = $stmt->rowCount() > 0
    ? 'Produto excluído com sucesso.'
    : 'O produto não foi encontrado.';

header('Location: index.php');
exit;
