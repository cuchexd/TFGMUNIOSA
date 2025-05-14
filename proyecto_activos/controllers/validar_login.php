<?php
session_start();
include("../config/db.php");

// Recolectar datos
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

// Buscar el usuario en la base de datos
$sql = "SELECT * FROM administradores WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario_data = $resultado->fetch_assoc();
    
    if ($clave === $usuario_data['clave']) {
        // Inicio de sesión exitoso
        $_SESSION['usuario'] = $usuario_data['usuario'];
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        // Contraseña incorrecta
        header("Location: ../views/login.php?error=contraseña");
        exit();
    }
} else {
    // Usuario no encontrado
    header("Location: ../views/login.php?error=usuario");
    exit();
}
?>
