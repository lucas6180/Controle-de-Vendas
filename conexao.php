<?php
$host = 'localhost';
$nameDataBase = 'vendasim';
$user = 'root';
$password = '';

$mysqli = new mysqli($host, $user, $password, $nameDataBase);

if ($mysqli->connect_errno) {
    echo "Falha na conexão: " . $mysqli->connect_error;
    exit();
}

$conn = $mysqli;
?>
