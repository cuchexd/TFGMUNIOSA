<?php
include("../config/db.php");

$correo = trim($_POST['correo']);
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("❌ Correo inválido.");
}

$sql = "INSERT INTO destinatarios (correo) VALUES (?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);

if ($stmt->execute()) {
    header("Location: ../views/agregar_destinatario.php");
} else {
    echo "❌ Error al guardar: " . $stmt->error;
}
?>
