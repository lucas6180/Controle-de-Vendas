<?php
include('conexao.php');
header('Content-Type: application/json');

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Erro de conexão: ' . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$response = []; 

if (isset($data['nome']) && isset($data['valor']) && isset($data['valorRecebido']) && isset($data['valoresManuais'])) {
    
    
    $nome = is_array($data['nome']) ? implode(", ", $data['nome']) : $data['nome'];
    
    if (!is_numeric($data['valor']) || !is_numeric($data['valorRecebido']) || !is_numeric($data['valoresManuais'])) {
        echo json_encode(['status' => 'error', 'message' => 'Os valores devem ser numéricos.']);
        exit;
    }

    $valorDaCompra = (float)$data['valor'];
    $valorRecebido = (float)$data['valorRecebido'];
    $valoresManuais = (float)$data['valoresManuais'];

    $troco = $valorRecebido - $valorDaCompra;

    $stmt = $conn->prepare("INSERT INTO vendas (nome, valorDaCompra, valorRecebido, valoresManuais, troco) VALUES (?, ?, ?, ?, ?)");

    if ($stmt === false) {
        error_log('Prepare failed: ' . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'Erro ao preparar a consulta: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("sdddd", $nome, $valorDaCompra, $valorRecebido, $valoresManuais, $troco);

    
    if ($stmt->execute()) {
        $response = [
            'status' => 'success',
            'id' => $stmt->insert_id,
            'nome' => $nome,
            'valorDaCompra' => $valorDaCompra,
            'valorRecebido' => $valorRecebido,
            'valoresManuais' => $valoresManuais,
            'troco' => $troco,
        ];
    } else {
        error_log('SQL Error: ' . $stmt->error);
        $response = [
            'status' => 'error',
            'message' => 'Erro ao registrar venda: ' . $stmt->error
        ];
    }

    $stmt->close();
} else {
    $response = ['status' => 'error', 'message' => 'Dados inválidos.'];
}

echo json_encode($response); 
$conn->close();
