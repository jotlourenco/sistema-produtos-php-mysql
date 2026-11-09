<?php
declare(strict_types=1);

/*
 * Configuração da conexão.
 * No XAMPP padrão, o usuário costuma ser root e a senha fica vazia.
 * Em outros ambientes, altere os dados abaixo.
 */

$host = 'localhost';
$dbname = 'loja_escola';
$username = 'root';
$password = '';

$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $exception) {
    // Detalhes técnicos ficam no log do servidor, não na tela do visitante.
    error_log($exception->getMessage());
    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados. Verifique a configuração.');
}
