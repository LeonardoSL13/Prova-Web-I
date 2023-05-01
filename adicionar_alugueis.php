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

// Buscar todos os produtos disponíveis para aluguel
$stmt = $pdo->query('SELECT * FROM produtos WHERE quantidade_estoque > 0 AND excluido = 0');
$produtos = $stmt->fetchAll();

// Buscar todos os clientes
$stmt = $pdo->query('SELECT * FROM clientes WHERE excluido = 0');
$clientes = $stmt->fetchAll();

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Capturar os dados enviados pelo formulário
  $id_cliente = $_POST['cliente'];
  $id_produto = $_POST['produto'];
  $data_aluguel = $_POST['data_aluguel'];
  $data_devolucao = $_POST['data_devolucao'];

  // Buscar o cliente e o produto selecionado
  $stmt = $pdo->prepare('SELECT * FROM clientes WHERE id = ?');
  $stmt->execute([$id_cliente]);
  $cliente = $stmt->fetch();

  $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
  $stmt->execute([$id_produto]);
  $produto = $stmt->fetch();

  // Verificar se a quantidade de produto selecionada está disponível em estoque
  if (1 > $produto['quantidade_estoque']) {
    $erro = 'Não há essa quantidade disponível em estoque.';
  } else {
    // Calcular o valor total do aluguel
    $valor_total = $produto['preco'];

    // Inserir o aluguel no banco de dados
    $stmt = $pdo->prepare('INSERT INTO alugueis (id_cliente, id_produto, data_aluguel, data_devolucao, valor_total) VALUES (?, ?,  ?, ?, ?)');
    $stmt->execute([$id_cliente, $id_produto, $data_aluguel, $data_devolucao, $valor_total]);

    // Atualizar a quantidade de produto em estoque
    $nova_qtd_estoque = $produto['quantidade_estoque'] - 1;
    $stmt = $pdo->prepare('UPDATE produtos SET quantidade_estoque = ? WHERE id = ?');
    $stmt->execute([$nova_qtd_estoque, $id_produto]);

    // Redirecionar para a página de alugueis
    header('Location: alugueis.php');
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="style.css">
  <title>Adicionar Aluguel</title>
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

  <h1 class="titulo-pagina">Adicionar Aluguel</h1>

  <main class="container">
    <form method="POST" action="adicionar_alugueis.php">
      <div class="form-group">
        <label for="cliente">Cliente:</label>
        <select name="cliente" id="cliente">
          <?php foreach ($clientes as $cliente): ?>
            <option value="<?php echo $cliente['id']; ?>"><?php echo $cliente['nome']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="produto">Produto:</label>
        <select name="produto" id="produto">
          <?php foreach ($produtos as $produto): ?>
            <option value="<?php echo $produto['id']; ?>"><?php echo $produto['nome']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="data_aluguel">Data de Aluguel:</label>
        <input type="date" name="data_aluguel" id="data_aluguel" required>
      </div>
      <div class="form-group">
        <label for="data_devolucao">Data de Devolução:</label>
        <input type="date" name="data_devolucao" id="data_devolucao" required>
      </div>
      <button type="submit" class="botao-adicionar">Adicionar</button>
    </form>
  </main>
</body>

</html>