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

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obter os dados do formulário
  $id = $_POST['id'];
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $telefone = $_POST['telefone'];
  $cpf = $_POST['cpf'];
  $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
  if (!isset($_POST['senha']) || empty($_POST['senha'])) {
    $mudarSenha = false;
  } else {
    $mudarSenha = true;
  }
  $is_admin = isset($_POST['is_admin']) ? true : false;


  // Atualizar o funcionário no banco de dados
  if ($mudarSenha) {
    $stmt = $pdo->prepare('UPDATE funcionarios SET nome = ?, email = ?, senha = ?, is_admin = ?, telefone = ?, cpf = ? WHERE id = ?');
    $resultado = $stmt->execute([$nome, $email, $senha, $is_admin, $telefone, $cpf, $id]);
  } else {
    $stmt = $pdo->prepare('UPDATE funcionarios SET nome = ?, email = ?, is_admin = ?, telefone = ?, cpf = ? WHERE id = ?');
    $resultado = $stmt->execute([$nome, $email, $is_admin, $telefone, $cpf, $id]);
  }


  // Redirecionar para a página de visualização de funcionários
  header('Location: funcionarios.php');
  exit();
}

// Obter o id do funcionário a ser editado
$id = $_GET['id'] ?? null;

// Verificar se o id é válido
if (!$id) {
  // Redirecionar para a página de visualização de funcionários
  header('Location: funcionarios.php');
  exit();
}

// Buscar o funcionário pelo id
$stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE id = ?');
$stmt->execute([$id]);
$funcionario = $stmt->fetch();

// Verificar se o funcionário foi encontrado
if (!$funcionario) {
  // Redirecionar para a página de visualização de funcionários
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
  <title>Editar Funcionário</title>
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

  <h1 class="titulo-pagina">Editar Funcionário</h1>

  <main class="container">
    <form method="POST">
      <div class="form-group">
        <input type="hidden" name="id" value="<?= $funcionario['id'] ?>">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo $funcionario['nome']; ?>" required>
      </div>
      <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" value="<?php echo $funcionario['email']; ?>" required>
      </div>

      <div class="form-group">
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" value="<?php echo $funcionario['telefone']; ?>" required>
      </div>

      <div class="form-group">
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" value="<?php echo $funcionario['cpf']; ?>" required>
      </div>
      <div class="form-group">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" placeholder="Deixe em branco para não alterar">
      </div>
      <div class="is-admin">
        <label for="is_admin">Administrador:</label>
        <input type="checkbox" id="is_admin" name="is_admin" <?php

        if ($_SESSION['funcionario_id'] == $funcionario['id']) {
          echo 'checked disabled >';
          echo "<input type='hidden' name='is_admin' value='1' >";
        } elseif ($funcionario['is_admin']) {
          echo 'checked >';
        } else {
          echo '>';
        }
        ?> </div>

        <div class="form-group">
          <button type="submit" class="botao botao-primary">Salvar</button>
          <a href="funcionarios.php" class="botao botao-secondary">Cancelar</a>
        </div>
    </form>
  </main>

</body>

</html>

<?php
// Fechar a conexão com o banco de dados
$pdo = null;
?>