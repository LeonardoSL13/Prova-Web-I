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

// Buscar todos os aluguéis
$stmt = $pdo->query('SELECT a.*, c.nome AS cliente, p.nome AS produto FROM alugueis a
                               JOIN clientes c ON a.id_cliente = c.id
                               JOIN produtos p ON a.id_produto = p.id
                               ORDER BY a.id ASC
                               ');
$alugueis = $stmt->fetchAll();

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

  <h1 class="titulo-pagina">Alugueis</h1>

  <main class="container">
    <table class="tabela-alugueis">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Produto</th>
            <th>Data de Aluguel</th>
            <th>Data de Devolução</th>
            <th>Valor Total</th>
            <th>Devolvido</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($alugueis as $aluguel): ?>
            <tr>
              <td>
                <?php echo $aluguel['id']; ?>
              </td>
              <td>
                <?php echo $aluguel['cliente']; ?>
              </td>
              <td>
                <?php echo $aluguel['produto']; ?>
              </td>
              <td>
                <?php echo $aluguel['data_aluguel']; ?>
              </td>
              <td>
                <?php echo $aluguel['data_devolucao']; ?>
              </td>
              <td>
                <?php echo $aluguel['valor_total']; ?>
              </td>
              <td>
                <?php $mensagem = $aluguel['devolvido'] ? "Devolvido" : "Pendente";
                echo $mensagem ?>
              </td>
              <td>
                <?php if (!$aluguel['devolvido']) { ?>
                  <a href="devolver_aluguel.php?id=<?php echo $aluguel['id']; ?>" class="botao-editar">Devolver</a>
                <?php } ?>
              </td>
            <?php endforeach; ?>
          </tr>
        </tbody>
      </table>
      <a href="adicionar_alugueis.php" class="botao-adicionar">Adicionar</a>
  </main>
</body>

</html>