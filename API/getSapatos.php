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

  $query = "SELECT
                Sapato.id AS id,
                Sapato.nome AS nome_sapato,
                Marca.nome AS nome_marca,
                Cores.nome AS nome_cor
            FROM Sapato
                INNER JOIN Marca ON Sapato.marca_id = Marca.id
                INNER JOIN Cores ON Sapato.cor_id = Cores.id;;
  ";

  $stmt = $conn->query($query);

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  sendResponse($result);

} catch (PDOException $e) {
  sendResponse(['error' => 'Erro na conexão com o banco de dados: ' . $e->getMessage()]);
}
?>