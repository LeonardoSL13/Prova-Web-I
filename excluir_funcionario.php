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



// Verificar se foi informado um ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
  // Redirecionar para a página de funcionários
  header('Location: funcionarios.php');
  exit();
}

// Conectar ao banco de dados
$pdo = new PDO('mysql:host=localhost;dbname=black_tie;charset=utf8', 'root', '');

// Buscar o funcionário pelo ID
$stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE id = ? AND excluido = 0');
$stmt->execute(array($_GET['id']));
$funcionario = $stmt->fetch();

// Verificar se o funcionário foi encontrado
if (!$funcionario) {
  // Redirecionar para a página de funcionários
  header('Location: funcionarios.php');
  exit();
}

// Verificar se foi enviado um pedido de exclusão
if (isset($_POST['excluir'])) {
  // Excluir o funcionário
  $stmt = $pdo->prepare('UPDATE funcionarios SET excluido = 1 WHERE id = ?');
  $stmt->execute(array($funcionario['id']));

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
  <title>Excluir Cliente</title>
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

  <h1 class="titulo-pagina">Excluir Funcionário</h1>

  <main class="container">
    <p>Tem certeza que deseja excluir o funcionário
      <?php echo $funcionario['nome']; ?>?
    </p>
    <form action="" method="POST">
      <button class="botao botao-primary" type="submit" name="excluir">Excluir</button>
      <a href="funcionarios.php" class="botao botao-secondary">Cancelar</a>
    </form>
  </main>


</body>

</html>