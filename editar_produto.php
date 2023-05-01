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

// Verificar se foi submetido o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Recuperar os dados do formulário
  $id = $_POST['id'];
  $nome = $_POST['nome'];
  $descricao = $_POST['descricao'];
  $categoria = $_POST['categoria'];
  $tamanho = $_POST['tamanho'];
  $cor = $_POST['cor'];
  $material = $_POST['material'];
  $preco = $_POST['preco'];
  $quantidade_estoque = $_POST['quantidade_estoque'];
  $id_fornecedor = $_POST['fornecedor'];

  // Atualizar o produto no banco de dados
  $stmt = $pdo->prepare('UPDATE produtos SET nome = ?, descricao = ?, id_categoria = ?, tamanho = ?, cor = ?, material = ?, preco = ?, quantidade_estoque = ?, id_fornecedor = ? WHERE id = ?');
  $stmt->execute([$nome, $descricao, $categoria, $tamanho, $cor, $material, $preco, $quantidade_estoque, $id_fornecedor, $id]);

  // Redirecionar para a página de produtos
  header('Location: produtos.php');
  exit();
}

// Verificar se foi passado o ID do produto
if (!isset($_GET['id'])) {
  // Redirecionar para a página de produtos
  header('Location: produtos.php');
  exit();
}

// Buscar o produto pelo ID
$stmt = $pdo->prepare('SELECT p.*,c.nome as categoria, f.nome as fornecedor  FROM produtos p INNER JOIN categorias c on p.id_categoria = c.id  INNER JOIN fornecedores f on p.id_fornecedor = f.id  WHERE p.id = ? AND p.excluido = 0');
$stmt->execute([$_GET['id']]);
$produto = $stmt->fetch();

//Buscar todos os fornecedores
$stmt = $pdo->query('SELECT * FROM fornecedores WHERE excluido = 0');
$fornecedores = $stmt->fetchAll();


// Verificar se o produto foi encontrado
if (!$produto) {
  // Redirecionar para a página de produtos
  header('Location: produtos.php');
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
  <title>Produto</title>
</head>

<body>
  <nav class="menu">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="clientes.php">clientes</a></li>
      <li><a href="produtos.php">Produtos</a></li>
      <li><a href="alugueis.php">Alugueis</a></li>
      <li><a href="fornecedores.php">Fornecedores</a></li>
      <?php if ($_SESSION['is_admin']) { ?>
        <li><a href="funcionarios.php">Funcionários</a></li>
      <?php } ?>
      <li class="logout-button"><a href="index.php?logout">Sair</a></li>
    </ul>
  </nav>

  <h1 class="titulo-pagina">Editar Produto</h1>
  <div class="container">
    <form method="POST">
      <input type="hidden" name="id" value="<?= $produto['id'] ?>">
      <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?= $produto['nome'] ?>" required>
      </div>
      <div class="form-group">
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" id="descricao" value="<?= $produto['descricao'] ?>" required>
      </div>
      <div class="form-group">
        <label for="nome">Categoria:</label>
        <select name="categoria" id="categoria" required>
          <option value="1" <?php if ($produto['id_categoria'] == 1) {
            echo 'selected';
          } ?>>Terno</option>
          <option value="2" <?php if ($produto['id_categoria'] == 2) {
            echo 'selected';
          } ?>>Vestido</option>
        </select>
      </div>
      <div class="form-group">
        <label for="tamanho">Tamanho:</label>
        <select name="tamanho" id="tamanho" required>
          <option value="PP" <?php if ($produto['tamanho'] == 'PP') {
            echo 'selected';
          } ?>>PP</option>
          <option value="P" <?php if ($produto['tamanho'] == 'P') {
            echo 'selected';
          } ?>>P</option>
          <option value="M" <?php if ($produto['tamanho'] == 'M') {
            echo 'selected';
          } ?>>M</option>
          <option value="G" <?php if ($produto['tamanho'] == 'G') {
            echo 'selected';
          } ?>>G</option>
          <option value="GG" <?php if ($produto['tamanho'] == 'GG') {
            echo 'selected';
          } ?>>GG</option>
        </select>
      </div>
      <div class="form-group">
        <label for="cor">Cor:</label>
        <input type="text" name="cor" id="cor" value="<?= $produto['cor'] ?>" required>
      </div>
      <div class="form-group">
        <label for="material">Material:</label>
        <input type="text" name="material" id="material" value="<?= $produto['material'] ?>" required>
      </div>
      <div class="form-group">
        <label for="preco">Preço:</label>
        <input type="text" name="preco" id="preco" value="<?= $produto['preco'] ?>" required>
      </div>
      <div class="form-group">
        <label for="quantidade_estoque">Estoque:</label>
        <input type="text" name="quantidade_estoque" id="quantidade_estoque"
          value="<?= $produto['quantidade_estoque'] ?>" required>
      </div>
      <div class="form-group">
        <label for="fornecedor">Fornecedor:</label>
        <select name="fornecedor" id="fornecedor" required>
          <?php foreach ($fornecedores as $fornecedor) { ?>
            <option value="<?= $fornecedor['id'] ?>" <?php if ($produto['id_fornecedor'] == $fornecedor['id']) {
                echo 'selected';
              } ?>><?= $fornecedor['nome'] ?></option>
          <?php } ?>
        </select>
      </div>

      <button class="botao botao-primary" type="submit">Salvar</button>
      <a href="produtos.php" class="botao botao-secondary">Cancelar</a>
    </form>
  </div>
</body>

</html>
<?php
// Fechar a conexão com o banco de dados
$pdo = null;
?>