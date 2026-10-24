<?php
require_once "conexion.php";
$id = (int)($_GET["id"] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id=?");
    $stmt->execute([$id]);
}
header("Location: index.php");
exit;
?>