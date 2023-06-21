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
  <title>Adicionar Sapato</title>
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

  <h1 class="titulo-pagina">Adicionar Sapato</h1>

  <main class="container">
    <form id="adicionar-sapato-form" method="POST">
      <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
      </div>
      <div class="form-group">
        <label for="marca">Marca:</label>
        <select id="marca" name="marca" required>
          <option value="">Selecione uma marca</option>
        </select>
      </div>

      <div class="form-group">
        <label for="cor">Cor:</label>
        <select id="cor" name="cor" required>
          <option value="">Selecione uma cor</option>
        </select>
      </div>
      <button class="botao botao-primary" type="submit">Adicionar</button>
      <button class="botao botao-secondary" type="reset">Cancelar</button>
    </form>
  </main>

  <script>
    const form = document.getElementById('adicionar-sapato-form');

    const marcaSelect = document.getElementById('marca');
    const corSelect = document.getElementById('cor');

    function buscarMarcas() {
      fetch('http://localhost/prova/api/getmarcas.php')
        .then(response => response.json())
        .then(marcas => {

          marcaSelect.innerHTML = '<option value="">Selecione uma marca</option>';

          marcas.forEach(marca => {
            const option = document.createElement('option');
            option.value = marca.id;
            option.textContent = marca.nome;
            marcaSelect.appendChild(option);
          });
        })
        .catch(error => {
          console.error('Erro ao buscar as marcas:', error);
        });
    }

    function buscarCores() {
      fetch('http://localhost/prova/api/getcores.php')
        .then(response => response.json())
        .then(cores => {

          corSelect.innerHTML = '<option value="">Selecione uma cor</option>';

          cores.forEach(cor => {
            const option = document.createElement('option');
            option.value = cor.id;
            option.textContent = cor.nome;
            corSelect.appendChild(option);
          });
        })
        .catch(error => {
          console.error('Erro ao buscar as cores:', error);
        });
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const formData = new FormData(form);


      fetch('http://localhost/prova/api/addsapato.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(result => {
          console.log(result);
          window.location.href = "http://localhost/prova/apiSapatos.php";

        })
        .catch(error => {
          console.error(error);
        });
    });


    buscarMarcas();
    buscarCores();
  </script>
</body>

</html>