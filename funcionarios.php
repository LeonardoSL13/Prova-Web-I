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

// Conectar ao banco de dados
$pdo = new PDO('mysql:host=localhost;dbname=black_tie;charset=utf8', 'root', '');

// Buscar todos os funcionários
$stmt = $pdo->query('SELECT * FROM funcionarios WHERE excluido = 0');
$funcionarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="style.css">
  <title>Funcionários</title>
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

  <h1 class="titulo-pagina">Funcionários</h1>

  <main class="container">
    <table class="tabela-funcionarios">
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
          <th>telefone</th>
          <th>CPF</th>
          <th>É administrador?</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($funcionarios as $funcionario): ?>
          <tr>
            <td>
              <?php echo $funcionario['nome']; ?>
            </td>
            <td>
              <?php echo $funcionario['email']; ?>
            </td>
            <td>
              <?php echo $funcionario['telefone']; ?>
            </td>
            <td>
              <?php echo $funcionario['cpf']; ?>
            </td>
            <td>
              <?php echo $funcionario['is_admin'] ? 'Sim' : 'Não'; ?>
            </td>
            <td><a href="editar_funcionario.php?id=<?php echo $funcionario['id']; ?>" class="botao-editar">Editar</a>
              <?php if ($_SESSION['funcionario_id'] != $funcionario['id']) { ?>
                | <a href="excluir_funcionario.php?id=<?php echo $funcionario['id']; ?>" class="botao-excluir">Excluir</a>
              </td>
            <?php } ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php if ($_SESSION['is_admin']) { ?>
      <a href="adicionar_funcionario.php" class="botao-adicionar">Adicionar</a>
    <?php } ?>
  </main>

</body>

</html>