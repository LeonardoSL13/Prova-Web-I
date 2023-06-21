<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loja_de_sapatos";

function sendResponse($data)
{
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $sapatoId = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM Sapato WHERE id = :sapatoId");
    $stmt->bindParam(':sapatoId', $sapatoId);
    $stmt->execute();

    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
      sendResponse(['message' => 'Sapato excluído com sucesso']);
    } else {
      sendResponse(['error' => 'Sapato não encontrado']);
    }
  } else {
    sendResponse(['error' => 'Método de requisição inválido']);
  }
} catch (PDOException $e) {
  sendResponse(['error' => 'Erro na conexão com o banco de dados: ' . $e->getMessage()]);
}
?>