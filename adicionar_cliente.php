<?php
session_start();

// Verificar se o funcionário está logado
if (!isset($_SESSION['funcionario_id'])) {
  // Redirecionar para a página de login
  header('Location: login.php');
  exit();
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Conectar ao banco de dados
  $pdo = new PDO('mysql:host=localhost;dbname=black_tie;charset=utf8', 'root', '');

  // Preparar a query para inserir um novo cliente
  $stmt = $pdo->prepare('INSERT INTO clientes (nome, email, telefone, endereco, cpf) VALUES (?, ?, ?, ?, ?)');

  // Executar a query com os dados do formulário
  $stmt->execute([
    $_POST['nome'],
    $_POST['email'],
    $_POST['telefone'],
    $_POST['endereco'],
    $_POST['cpf']
  ]);

  // Redirecionar para a página de clientes
  header('Location: clientes.php');
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
  <title>Adicionar Cliente</title>
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

  <h1 class="titulo-pagina">Adicionar Cliente</h1>

  <main class="container">
    <form action="adicionar_cliente.php" method="POST">
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
        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>
      </div>
      <div class="form-group">
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required>
      </div>
      <button class="botao botao-primary" type="submit">Adicionar</button>
      <a href="clientes.php" class="botao botao-secondary">Cancelar</a>
    </form>
  </main>

</body>

</html>