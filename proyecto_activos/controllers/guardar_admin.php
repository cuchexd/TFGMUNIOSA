<?php
include("../config/db.php");

$nombre = $_POST['nombre'];
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];
$clave_hashed = password_hash($clave, PASSWORD_DEFAULT);

$sql = "INSERT INTO administradores (nombre, usuario, clave) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $nombre, $usuario, $clave_hashed);

if ($stmt->execute()) {
    header("Location: ../views/registrar_admin.php?exito=1");
    exit();
} else {
    echo "❌ Error al registrar: " . $stmt->error;
}
