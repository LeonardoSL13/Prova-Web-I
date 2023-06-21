<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loja_de_sapatos";

// Função para retornar a resposta da API em formato JSON
function sendResponse($data)
{
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Preparar a query para inserir um novo sapato
    $stmt = $conn->prepare('INSERT INTO sapato (nome, marca_id, cor_id) VALUES (?, ?, ?)');

    // Executar a query com os dados do formulário
    $stmt->execute([
      $_POST['nome'],
      $_POST['marca'],
      $_POST['cor'],

    ]);

    sendResponse(['success' => 'Sapato adicionado com sucesso.']);
  } else {
    sendResponse(['error' => 'Método de requisição inválido.']);
  }

} catch (PDOException $e) {
  sendResponse(['error' => 'Erro na conexão com o banco de dados: ' . $e->getMessage()]);
}
?>