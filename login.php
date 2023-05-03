<?php

session_start();
if (isset($_GET['error'])) {
  $error = $_GET['error'];
} else {
  $error = '';
}

// Classe para autenticação
class Auth
{
  // Método para fazer login
  public function login($username, $password)
  {
    // Conectar ao banco de dados
    $pdo = new PDO('mysql:host=localhost;dbname=black_tie;charset=utf8', 'root', '');

    // Buscar o funcionário com o nome de usuário fornecido
    $stmt = $pdo->prepare('SELECT * FROM funcionarios WHERE email = ? AND excluido = 0');
    $stmt->execute([$username]);
    $funcionario = $stmt->fetch();

    // Verificar se a senha está correta
    if ($funcionario && password_verify($password, $funcionario['senha'])) {
      // Iniciar uma sessão e armazenar o ID do funcionário

      $_SESSION['funcionario_id'] = $funcionario['id'];
      $_SESSION['funcionario_nome'] = $funcionario['nome'];
      $_SESSION['is_admin'] = $funcionario['is_admin'];
      // Redirecionar para a página inicial do sistema
      header('Location: index.php');
      exit();
    } else {
      // Senha inválida, exibir mensagem de erro
      header('Location: login.php?error=Nome de usuário ou senha inválidos.');
      exit();
    }
  }
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Tentar fazer login com as credenciais fornecidas
  $auth = new Auth();
  $auth->login($_POST['username'], $_POST['password']);
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="style.css">
  <title>Black Tie Login</title>
</head>

<body class="">
  <div class="container-login">
    <div class="login-header">
      <h2>Faça login</h2>
    </div>
    <form class="login-form" method="POST" action="login.php">
      <input type="text" name="username" placeholder="Nome de usuário" required>
      <input type="password" name="password" placeholder="Senha" required>
      <button type="submit">Entrar</button>
      <div class="error">
        <?php echo $error; ?>
      </div>
    </form>
  </div>
</body>

</html>