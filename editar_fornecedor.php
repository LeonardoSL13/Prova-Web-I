<?php
session_start();

// Verificar se o funcionário está logado
if (!isset($_SESSION['funcionario_id'])) {
  // Redirecionar para a página de login
  header('Location: login.php');
  exit();
}

// Conectar ao banco de dados
$pdo = new PDO('mysql:host=localhost;dbname=black_tie;charset=utf8', 'root', '');

// Verificar se foi submetido o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Recuperar os dados do formulário
  $id = $_POST['id'];
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $telefone = $_POST['telefone'];
  $endereco = $_POST['endereco'];

  // Atualizar o fornecedor no banco de dados
  $stmt = $pdo->prepare('UPDATE fornecedores SET nome = ?, email = ?, telefone = ?, endereco = ? WHERE id = ?');
  $stmt->execute([$nome, $email, $telefone, $endereco, $id]);

  // Redirecionar para a página de fornecedores
  header('Location: fornecedores.php');
  exit();
}

// Verificar se foi passado o ID do fornecedor
if (!isset($_GET['id'])) {
  // Redirecionar para a página de fornecedores
  header('Location: fornecedores.php');
  exit();
}

// Buscar o fornecedor pelo ID
$stmt = $pdo->prepare('SELECT * FROM fornecedores WHERE id = ? AND excluido = 0');
$stmt->execute([$_GET['id']]);
$fornecedor = $stmt->fetch();

// Verificar se o fornecedor foi encontrado
if (!$fornecedor) {
  // Redirecionar para a página de fornecedores
  header('Location: fornecedores.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <title>Editar Fornecedor</title>
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

  <h1 class="titulo-pagina">Editar Fornecedor</h1>
  <div class="container">
    <form method="POST">
      <input type="hidden" name="id" value="<?= $fornecedor['id'] ?>">
      <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?= $fornecedor['nome'] ?>" required>
      </div>
      <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" value="<?= $fornecedor['email'] ?>" required>
      </div>
      <div class="form-group">
        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" value="<?= $fornecedor['telefone'] ?>" required>
      </div>
      <div class="form-group">
        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco" value="<?= $fornecedor['endereco'] ?>" required>
      </div>
      <button class="botao botao-primary" type="submit">Salvar</button>
      <a href="fornecedores.php" class="botao botao-secondary">Cancelar</a>
    </form>
  </div>
</body>

</html>