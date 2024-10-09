<?php
include('conexao.php'); // Inclua seu arquivo de conexão com o banco de dados

// Obtém o ID do corpo da requisição
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Log do ID recebido
error_log("ID recebido para deleção: $id");

// Verifica se o ID é válido
if ($id > 0) {
    $sql = "DELETE FROM vendas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Erro na preparação da consulta: " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'Erro na preparação da consulta.']);
        exit;
    }
    
    $stmt->bind_param('i', $id); // Bind do parâmetro

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        error_log("Erro ao executar a consulta: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'Erro ao deletar a venda.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
}

$conn->close();
?>
