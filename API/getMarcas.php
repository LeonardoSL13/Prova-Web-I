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

  $query = "SELECT * FROM marca;";

  $stmt = $conn->query($query);

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  sendResponse($result);

} catch (PDOException $e) {
  sendResponse(['error' => 'Erro na conexão com o banco de dados: ' . $e->getMessage()]);
}
?>