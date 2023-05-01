<?php
session_start();

// Verificar se o funcionário está logado
if (!isset($_SESSION['funcionario_id'])) {
  // Redirecionar para a página de login
  header('Location: login.php');
  exit();
}

// Verificar se foi informado um ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
  // Redirecionar para a página de alugueis
  header('Location: alugueis.php');
  exit();
}

// Conectar ao banco de dados
$pdo = new PDO('mysql:host=localhost;dbname=black_tie;charset=utf8', 'root', '');

// Buscar o aluguel pelo ID
$stmt = $pdo->prepare('SELECT a.*, c.nome AS cliente, p.nome AS produto, p.id as produto_id FROM alugueis a
JOIN clientes c ON a.id_cliente = c.id
JOIN produtos p ON a.id_produto = p.id WHERE a.id = ?');
$stmt->execute(array($_GET['id']));
$aluguel = $stmt->fetch();

// Verificar se o aluguel foi encontrado
if (!$aluguel) {
  // Redirecionar para a página de alugueis
  header('Location: alugueis.php');
  exit();
}

// Verificar se foi enviado um pedido de devolução
if (isset($_POST['devolver'])) {
  // Atualizar o aluguel para marcá-lo como devolvido
  $stmt = $pdo->prepare('UPDATE alugueis SET devolvido = 1 WHERE id = ?');
  $stmt->execute(array($aluguel['id']));

  // Atualizar o estoque do produto alugado
  $stmt = $pdo->prepare('UPDATE produtos SET quantidade_estoque = quantidade_estoque + 1 WHERE id = ?');
  $stmt->execute(array($aluguel['produto_id']));


  // Redirecionar para a página de alugueis
  header('Location: alugueis.php');
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
  <title>Devolver Aluguel</title>
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

  <h1 class="titulo-pagina">Devolver Aluguel</h1>

  <main class="container">
    <p>Tem certeza que deseja marcar o aluguel de<b>
        <?php echo $aluguel['produto']; ?>
      </b> para o cliente<b>
        <?php echo $aluguel['cliente']; ?>
      </b> como devolvido?
    </p>
    <form action="" method="POST">
      <button class="botao botao-primary" type="submit" name="devolver">Devolver</button>
      <a href="alugueis.php" class="botao botao-secondary">Cancelar</a>
    </form>
  </main>

</body>

</html>