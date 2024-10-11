<?php
include('conexao.php');
header('Content-Type: application/json');

$query = "SELECT * FROM vendas ORDER BY dataVenda DESC"; 
$result = $conn->query($query);

$vendas = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $vendas[] = $row;
    }
    echo json_encode(['status' => 'success', 'vendas' => $vendas]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar vendas: ' . $conn->error]);
}

$conn->close();
?>
