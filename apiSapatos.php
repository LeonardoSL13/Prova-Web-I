<?php
session_start();
if (!isset($_SESSION['funcionario_id'])) {
  header('Location: login.php');
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
  <title>Sapatos</title>
</head>

<body>
  <nav class="menu">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="clientes.php">Clientes</a></li>
      <li><a href="produtos.php">Produtos</a></li>
      <li><a href="alugueis.php">Alugueis</a></li>
      <li><a href="fornecedores.php">Fornecedores</a></li>
      <li><a href="apiSapatos.php">API sapatos</a></li>
      <?php if ($_SESSION['is_admin']) { ?>
        <li><a href="funcionarios.php">Funcionários</a></li>
      <?php } ?>
      <li class="logout-button"><a href="index.php?logout">Sair</a></li>
    </ul>
  </nav>

  <h1 class="titulo-pagina">API Sapatos</h1>

  <main class="container">
    <table class="tabela-sapatos">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Marca</th>
          <th>Cor</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    <a href="adicionar_apiSapatos.php" class="botao-adicionar">Adicionar</a>
  </main>
</body>
<script>
  function excluirSapato(id) {
    if (confirm("Deseja realmente excluir o sapato?")) {
      var url = "http://localhost/prova/api/excluiSapato.php?id=" + id;

      fetch(url, {
        method: 'DELETE'
      })
        .then(response => {
          if (response.ok) {
            window.location.reload();
          } else {
            throw new Error("Houve um erro ao excluir o sapato: " + response.statusText);
          }
        })
        .catch(error => {
          console.error(error);
          alert(error.message);
        });
    }
  }

  var url = <?php echo json_encode("http://localhost/prova/api/getsapatos.php"); ?>;

  fetch(url)
    .then(response => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error("Houve um erro ao carregar: " + response.statusText);
      }
    })
    .then(sapatos => {
      var tabelaSapatos = document.querySelector('.tabela-sapatos tbody');

      for (var i = 0; i < sapatos.length; i++) {
        var sapato = sapatos[i];
        var row = tabelaSapatos.insertRow();
        row.innerHTML = "<td>" + sapato.id + "</td>" +
          "<td>" + sapato.nome_sapato + "</td>" +
          "<td>" + sapato.nome_marca + "</td>" +
          "<td>" + sapato.nome_cor + "</td>" +
          "<td><a href='editar_apiSapatos.php?id=" + sapato.id +
          "' class='botao-editar'>Editar</a> | <a onclick='excluirSapato(" + sapato.id +
          ")' class='pointer botao-excluir'>Excluir</a></td>";
      }
    })
    .catch(error => {
      console.error(error);
      alert(error.message);
    });
</script>

</html>