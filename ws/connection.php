<?php

$host = "localhost";
$dbname = "monfab";
$user = "root";
$password = "";


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    return json_encode([
        'success' => false,
        'message' => 'Error en la conexión a la base de datos: ' . $e->getMessage(),
    ]);
}
