<?php
$host = "127.0.0.1";
$db   = "control_vencimientos";
$user = "root";
$pass = "123456789";
$charset = "utf8mb4";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=$charset",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Error de conexión con MySQL: " . htmlspecialchars($e->getMessage()));
}
?>