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

// Buscar todos os produtos
$stmt = $pdo->query('SELECT p.*,c.nome as categoria, f.nome as fornecedor FROM produtos p INNER JOIN categorias c on p.id_categoria = c.id  INNER JOIN fornecedores f on p.id_fornecedor = f.id WHERE p.excluido = 0 order by p.id asc');
$produtos = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="style.css">
  <title>Produtos</title>
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

  <h1 class="titulo-pagina">Produtos</h1>

  <main class="container">
    <table class="tabela-produtos">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Descrição</th>
          <th>Categoria</th>
          <th>Tamanho</th>
          <th>Cor</th>
          <th>Material</th>
          <th>Preço</th>
          <th>Estoque</th>
          <th>Fornecedor</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produtos as $produto): ?>
          <tr>
            <td>
              <?php echo $produto['id']; ?>
            </td>
            <td>
              <?php echo $produto['nome']; ?>
            </td>
            <td>
              <?php echo $produto['descricao']; ?>
            </td>
            <td>
              <?php echo $produto['categoria']; ?>
            </td>
            <td>
              <?php echo $produto['tamanho']; ?>
            </td>
            <td>
              <?php echo $produto['cor']; ?>
            </td>
            <td>
              <?php echo $produto['material']; ?>
            </td>
            <td>
              <?php echo $produto['preco']; ?>
            </td>
            <td>
              <?php echo $produto['quantidade_estoque']; ?>
            </td>
            <td>
              <?php echo $produto['fornecedor']; ?>
            </td>
            <td><a href="editar_produto.php?id=<?php echo $produto['id']; ?>" class="botao-editar">Editar</a> | <a
                href="excluir_produto.php?id=<?php echo $produto['id']; ?>" class="botao-excluir">Excluir</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <a href="adicionar_produto.php" class="botao-adicionar">Adicionar</a>
  </main>
</body>

</html>