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

// //Buscar todos os fornecedores
$stmt = $pdo->query('SELECT * FROM fornecedores WHERE excluido = 0');
$fornecedores = $stmt->fetchAll();

// Verificar se foi submetido o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Recuperar os dados do formulário
  $nome = $_POST['nome'];
  $descricao = $_POST['descricao'];
  $categoria = $_POST['categoria'];
  $tamanho = $_POST['tamanho'];
  $cor = $_POST['cor'];
  $material = $_POST['material'];
  $preco = $_POST['preco'];
  $quantidade_estoque = $_POST['quantidade_estoque'];
  $id_fornecedor = $_POST['fornecedor'];


  // Inserir o novo produto no banco de dados
  $stmt = $pdo->prepare('INSERT INTO produtos (nome, descricao, id_categoria, tamanho, cor, material, preco, quantidade_estoque, id_fornecedor) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)');
  $stmt->execute([$nome, $descricao, $categoria, $tamanho, $cor, $material, $preco, $quantidade_estoque, $id_fornecedor]);

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
  <title>Adicionar Produto</title>
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

  <h1 class="titulo-pagina">Adicionar Produto</h1>
  <div class="container">
    <form method="POST">
      <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="" required>
      </div>
      <div class="form-group">
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" id="descricao" value="" required>
      </div>
      <div class="form-group">
        <label for="nome">Categoria:</label>
        <select name="categoria" id="categoria" required>
          <option value="1">Terno</option>
          <option value="2">Vestido</option>
        </select>
      </div>
      <div class="form-group">
        <label for="tamanho">Tamanho:</label>
        <select name="tamanho" id="tamanho" required>
          <option value="PP">PP</option>
          <option value="P">P</option>
          <option value="M">M</option>
          <option value="G">G</option>
          <option value="GG">GG</option>
        </select>
      </div>
      <div class="form-group">
        <label for="cor">Cor:</label>
        <input type="text" name="cor" id="cor" value="" required>
      </div>
      <div class="form-group">
        <label for="material">Material:</label>
        <input type="text" name="material" id="material" value="" required>
      </div>
      <div class="form-group">
        <label for="preco">Preço:</label>
        <input type="number" name="preco" id="preco" min="0.01" step="0.01" required>
      </div>
      <div class="form-group">
        <label for="quantidade_estoque">Estoque:</label>
        <input type="number" name="quantidade_estoque" id="quantidade_estoque" min="1" required>
      </div>
      <div class="form-group">
        <label for="fornecedor">Fornecedor:</label>
        <select name="fornecedor" id="fornecedor" required>
          <?php foreach ($fornecedores as $fornecedor) { ?>
            <option value="<?php echo $fornecedor['id'] ?>"><?php echo $fornecedor['nome'] ?></option>
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