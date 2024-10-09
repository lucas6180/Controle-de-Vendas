<?php
// Inclua sua conexão com o banco de dados aqui
include('conexao.php');
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$response = []; // Inicializa a variável de resposta

if (isset($data['nome']) && isset($data['valor']) && isset($data['valorRecebido'])) {
    $nome = $data['nome'];
    $valorDaCompra = (float)$data['valor'];
    $valorRecebido = (float)$data['valorRecebido'];
    $troco = $valorRecebido - $valorDaCompra;

    $stmt = $conn->prepare("INSERT INTO vendas (nome, valorDaCompra, valorRecebido, troco) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sddd", $nome, $valorDaCompra, $valorRecebido, $troco);

    if ($stmt->execute()) {
        $response = [
            'status' => 'success',
            'id' => $stmt->insert_id,
            'nome' => $nome,
            'valorDaCompra' => $valorDaCompra,
            'valorRecebido' => $valorRecebido,
            'troco' => $troco,
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao registrar venda: ' . $stmt->error
        ];
    }

    $stmt->close();
} else {
    $response = ['status' => 'error', 'message' => 'Dados inválidos.'];
}

echo json_encode($response); // Certifique-se de codificar a resposta como JSON
$conn->close();
?>
