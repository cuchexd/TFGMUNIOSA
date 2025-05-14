<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Traslado de Activos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header style="position: relative;">
        <h1>Formulario de Traslado de Activos</h1>
        <!-- Botón Volver al Dashboard -->
        <a href="dashboard.php" style="position: absolute; top: 20px; right: 20px; background-color: #0c6d45; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-size: 14px;"> Volver al Dashboard</a>
    </header>

    <form action="../controllers/guardar_traslado.php" method="POST" style="margin-top: 30px;">
        <label>N° Activo:</label>
        <input type="text" name="numero_activo" required>

        <label>Fecha Aprobación Proveeduría:</label>
        <input type="date" name="fecha_aprobacion" required>

        <label>Fecha Realización del Traslado:</label>
        <input type="date" name="fecha_traslado" required>

        <label>Canal de Comunicación:</label>
        <input type="text" name="canal_comunicacion">

        <label>Motivo del Traslado:</label>
        <textarea name="motivo"></textarea>

        <label>Tipo de Activo:</label>
        <input type="text" name="tipo_activo">

        <label>Modelo:</label>
        <input type="text" name="modelo">

        <label>Marca:</label>
        <input type="text" name="marca">

        <label>Serie:</label>
        <input type="text" name="serie">

        <label>Departamento:</label>
        <input type="text" name="departamento">

        <label>¿Formulario Lista de Chequeo Completado? (S/N):</label>
        <input type="text" name="checklist_completado" maxlength="1">

        <label>Ubicación Anterior:</label>
        <input type="text" name="ubicacion_anterior">

        <label>Ubicación Actual:</label>
        <input type="text" name="ubicacion_actual">

        <input type="submit" value="Guardar Traslado" style="margin-top: 20px;">
    </form>
    
</body>
</html>
