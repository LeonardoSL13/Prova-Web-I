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

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET['id'])) {
      sendResponse(['error' => 'ID não informado.']);
    }
    // Preparar pegar dados do sapato
    $stmt = $conn->prepare('SELECT * FROM sapato WHERE id = ?');

    // Executar a query com os dados do formulário
    $stmt->execute([
      $_GET['id'],
    ]);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
      sendResponse($result);
    } else {
      sendResponse(['error' => 'Sapato nao encontrado.']);
    }
  }
} catch (PDOException $e) {
  sendResponse(['error' => 'Erro na conexão com o banco de dados: ' . $e->getMessage()]);
}
?>