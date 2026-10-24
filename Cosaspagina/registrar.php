<?php
require_once "conexion.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = trim($_POST["codigo"] ?? "");
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $cantidad = (int)($_POST["cantidad"] ?? 0);
    $fecha = $_POST["fecha_vencimiento"] ?? "";

    if ($codigo === "" || $nombre === "" || $cantidad < 0 || $fecha === "") {
        $error = "Completa los campos obligatorios correctamente.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO productos (codigo,nombre,descripcion,cantidad,fecha_vencimiento) VALUES (?,?,?,?,?)");
            $stmt->execute([$codigo,$nombre,$descripcion,$cantidad,$fecha]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = $e->getCode() == 23000 ? "El código ya existe. Utiliza otro código." : "No se pudo guardar el producto.";
        }
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Registrar producto</title><link rel="stylesheet" href="css/estilos.css"></head><body>
<div class="formpage"><a class="back" href="index.php">← Volver al listado</a><div class="formbox"><h1>Registrar producto</h1><p>Ingresa la información del producto y su fecha de vencimiento.</p>
<?php if($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post">
<label>Código *</label><input name="codigo" required maxlength="50" value="<?=htmlspecialchars($_POST['codigo']??'')?>">
<label>Nombre *</label><input name="nombre" required maxlength="150" value="<?=htmlspecialchars($_POST['nombre']??'')?>">
<label>Descripción</label><textarea name="descripcion"><?=htmlspecialchars($_POST['descripcion']??'')?></textarea>
<label>Cantidad *</label><input type="number" name="cantidad" min="0" required value="<?=htmlspecialchars($_POST['cantidad']??'0')?>">
<label>Fecha de vencimiento *</label><input type="date" name="fecha_vencimiento" required value="<?=htmlspecialchars($_POST['fecha_vencimiento']??'')?>">
<div class="buttons"><a class="btn" href="index.php">Cancelar</a><button class="btn primary" type="submit">Guardar producto</button></div>
</form></div></div></body></html>