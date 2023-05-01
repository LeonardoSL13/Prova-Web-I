<?php
session_start();

// Verificar se o funcionário está logado
if (!isset($_SESSION['funcionario_id'])) {
  // Redirecionar para a página de login
  header('Location: login.php');
  exit();
}

// Fazer logout
if (isset($_GET['logout'])) {
  // Limpar a sessão
  session_unset();
  session_destroy();

  // Redirecionar para a página de login
  header('Location: login.php');
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
  <title>Black Tie</title>
</head>

<body>
  <header>
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
    <h1 class="titulo-pagina">Black Tie</h1>
  </header>

  <main class='container'>
    <h2>Bem-vindo(a)
      <?php echo $_SESSION['funcionario_nome']; ?>
    </h2>

    <p>Esta é a página inicial do sistema. Escolha uma das opções no menu acima para começar.</p>
  </main>
</body>

</html>