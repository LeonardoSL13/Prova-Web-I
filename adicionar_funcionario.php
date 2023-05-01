<?php
session_start();

// Verificar se o funcionário está logado
if (!isset($_SESSION['funcionario_id'])) {
  // Redirecionar para a página de login
  header('Location: login.php');
  exit();
}

if (!$_SESSION['is_admin']) {
  header('Location: index.php');
  exit();
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $telefone = $_POST['telefone'];
  $cpf = $_POST['cpf'];
  $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
  $is_admin = isset($_POST['is_admin']) ? true : false;

  // Conectar ao banco de dados
  $pdo = new PDO('mysql:host=localhost;dbname=black_tie;charset=utf8', 'root', '');

  // Preparar a query para inserir um novo funcionário
  $stmt = $pdo->prepare('INSERT INTO funcionarios (nome, email, senha, telefone, cpf, is_admin) VALUES (?, ?, ?, ?, ?, ?)');

  // Executar a query com os dados do formulário
  $stmt->execute([
    $nome,
    $email,
    $senha,
    $telefone,
    $cpf,
    $is_admin
  ]);

  // Redirecionar para a página de funcionários
  header('Location: funcionarios.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="style.css">
  <title>Adicionar Funcionário</title>
</head>

<body>
  <nav class="menu">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="clientes.php">Clientes</a></li>
      <li><a href="produtos.php">Produtos</a></li>
      <li><a href="alugueis.php">Alugueis</a></li>
      <li><a href="fornecedores.php">Fornecedores</a></li>
      <?php if ($_SESSION['is_admin']) { ?>
        <li><a href="funcionarios.php">Funcionários</a></li>
      <?php } ?>
      <li class="logout-button"><a href="index.php?logout">Sair</a></li>
    </ul>
  </nav>

  <h1 class="titulo-pagina">Adicionar Funcionário</h1>

  <main class="container">
    <form action="adicionar_funcionario.php" method="POST">
      <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
      </div>
      <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" required>
      </div>
      <div class="form-group">
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required>
      </div>
      <div class="form-group">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
      </div>
      <div class="is-admin">
        <label for="is_admin">Administrador:</label>
        <input type="checkbox" id="is_admin" name="is_admin">
      </div>
      <div class="form-group">
        <button class="botao botao-primary" type="submit">Adicionar</button>
        <a href="funcionarios.php" class="botao botao-secondary">Cancelar</a>
      </div>
    </form>
  </main>
</body>

</html>