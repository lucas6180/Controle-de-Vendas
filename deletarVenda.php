<?php

include('conexao.php');

$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? intval($data['id']) : 0;

if ($id > 0) {
    $sql = "DELETE FROM vendas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        
        $conn->query("INSERT INTO id_reutilizaveis (id) VALUES ($id)");
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao deletar a venda.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
}

$conn->close();
?>
