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

// Buscar todos os clientes
$stmt = $pdo->query('SELECT * FROM clientes WHERE excluido = 0');
$clientes = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="style.css">
  <title>Clientes</title>
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

  <h1 class="titulo-pagina">Clientes</h1>

  <main class="container">
    <table class="tabela-clientes">
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Telefone</th>
          <th>Endereço</th>
          <th>CPF</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clientes as $cliente): ?>
          <tr>
            <td>
              <?php echo $cliente['nome']; ?>
            </td>
            <td>
              <?php echo $cliente['email']; ?>
            </td>
            <td>
              <?php echo $cliente['telefone']; ?>
            </td>
            <td>
              <?php echo $cliente['endereco']; ?>
            </td>
            <td>
              <?php echo $cliente['cpf']; ?>
            </td>
            <td><a href="editar_cliente.php?id=<?php echo $cliente['id']; ?>" class="botao-editar">Editar</a> | <a
                href="excluir_cliente.php?id=<?php echo $cliente['id']; ?>" class="botao-excluir">Excluir</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <a href="adicionar_cliente.php" class="botao-adicionar">Adicionar</a>
  </main>

</body>

</html>