<?php
$host = 'localhost';
$nameDataBase = 'vendasim';
$user = 'root';
$password = '';

// Cria uma nova conexão MySQLi
$mysqli = new mysqli($host, $user, $password, $nameDataBase);

// Verifica se houve erro na conexão
if ($mysqli->connect_errno) {
    echo "Falha na conexão: " . $mysqli->connect_error;
    exit();
}

// A variável correta deve ser $mysqli
$conn = $mysqli;
?>
