# Sistema de Cadastro de Produtos — PHP + MySQL

Projeto didático para estudar a integração entre PHP e MySQL, implementar as quatro operações do CRUD e conhecer medidas básicas de segurança.

## 1. Tecnologias

- PHP 8.0 ou superior (recomendado)
- MySQL ou MariaDB
- PDO
- HTML5 e CSS3
- XAMPP
- Visual Studio Code (opcional)

## 2. Como executar no XAMPP

1. Instale e abra o XAMPP.
2. Inicie os módulos **Apache** e **MySQL**.
3. Copie a pasta `sistema-produtos-php-mysql` para `C:\xampp\htdocs\`.
4. Abra `http://localhost/phpmyadmin`.
5. Entre na opção **Importar** e selecione o arquivo `database.sql`.
6. Confirme a importação. O banco `loja_escola` e a tabela `produtos` serão criados.
7. Confira as credenciais em `config.php`. No XAMPP padrão, normalmente o usuário é `root` e a senha está vazia.
8. Acesse `http://localhost/sistema-produtos-php-mysql/`.

> Se sua instalação do MySQL usa outra senha ou porta, ajuste a configuração em `config.php`.

## 3. Estrutura do projeto

```text
sistema-produtos-php-mysql/
│
├── assets/
│   └── style.css
├── database.sql
├── config.php
├── funcoes.php
├── cabecalho.php
├── rodape.php
├── index.php
├── cadastrar.php
├── editar.php
├── excluir.php
└── README.md
```

## 4. Onde está o CRUD?

| Operação | Comando SQL | Arquivo |
|---|---|---|
| Create | `INSERT` | `cadastrar.php` |
| Read | `SELECT` | `index.php` |
| Update | `UPDATE` | `editar.php` |
| Delete | `DELETE` | `excluir.php` |

## 5. Medidas de segurança demonstradas

### SQL Injection — consultas preparadas

O projeto utiliza `PDO::prepare()` e `execute()` com parâmetros. Não concatene valores enviados pelo usuário diretamente ao SQL.

Exemplo:

```php
$sql = 'SELECT * FROM produtos WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
```

### XSS — escape na saída HTML

A função `e()` usa `htmlspecialchars()` antes de exibir dados no HTML:

```php
<?= e($produto['nome']) ?>
```

### Validação no servidor

A função `validarProduto()` confere campos, tamanhos, preço e estoque no PHP. A validação do navegador é útil para a experiência do usuário, mas não substitui a validação no servidor.

### CSRF — token nos formulários

Os formulários que alteram dados enviam um token aleatório de sessão. O servidor confere o token antes de executar a operação.

### Exclusão via POST

A exclusão é feita por formulário POST, com token CSRF. O sistema não exclui registros apenas porque alguém abriu uma URL.

### Erros

Os detalhes da conexão ficam no log do servidor; a mensagem exibida ao usuário não revela credenciais nem detalhes internos.

## 6. Roteiro de testes para os alunos

1. Cadastre um produto chamado `Caderno`.
2. Cadastre outros dois produtos.
3. Atualize o preço de um produto.
4. Altere a quantidade em estoque.
5. Exclua um produto.
6. Tente enviar o formulário sem preencher os campos obrigatórios.
7. Tente informar preço negativo.
8. Tente informar estoque decimal ou negativo.
9. Cadastre como nome o texto `<script>alert('teste')</script>` e observe como ele aparece na listagem. O navegador não deve executar o script.
10. Abra `excluir.php` diretamente pelo navegador. A operação não deve ser executada.

## 7. Conceitos para discutir em sala

- O que é CRUD?
- Qual é a função do PDO?
- Qual é a diferença entre `query()` e `prepare()`?
- Por que usamos parâmetros nomeados, como `:id`?
- O que é SQL Injection?
- O que é XSS?
- Por que precisamos validar os dados no servidor?
- Para que serve um token CSRF?
- Por que a exclusão é realizada por POST?

## 8. Limites deste projeto

Este é um exemplo educacional, não um sistema pronto para publicação na internet.

Antes de utilizá-lo em produção, seria necessário, entre outras medidas:
- Criar um usuário MySQL exclusivo para a aplicação, com permissões mínimas; não usar `root`.
- Retirar credenciais do código e armazená-las em configuração protegida.
- Usar HTTPS.
- Implementar autenticação, autorização e controle de sessão.
- Configurar cookies de sessão com `HttpOnly`, `Secure` (em HTTPS) e `SameSite`.
- Revisar validações, tratamento de erros, logs, cópias de segurança e controles de acesso.
- Proteger contra abuso de requisições e revisar a configuração do servidor.

O projeto não implementa login nem controle de usuários. Todos que acessarem a aplicação poderão gerenciar os produtos; por isso, utilize-o apenas em ambiente local de aula.
