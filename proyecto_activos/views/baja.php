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
    <title>Baja de Activos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header style="position: relative;">
        <h1>Formulario de Baja de Activos</h1>
              <a href="dashboard.php" style="position: absolute; top: 20px; right: 20px; background-color: #0c6d45; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-size: 14px;"> Volver al Dashboard</a>

    </header>

    <form action="../controllers/guardar_baja.php" method="POST" style="margin-top: 30px;">
        <label>N° Activo (si aplica):</label>
        <input type="text" name="numero_activo">

        <label>Fecha de Baja:</label>
        <input type="date" name="fecha_baja" required>

        <label>Fecha Recepción Proveeduría:</label>
        <input type="date" name="fecha_recepcion" required>

        <label>Oficio de Baja:</label>
        <input type="text" name="oficio_baja">

        <label>Tipo de Baja:</label>
        <input type="text" name="tipo_baja">

        <label>Clase de Activo:</label>
        <input type="text" name="clase_activo">

        <label>Motivo de la Baja:</label>
        <textarea name="motivo"></textarea>

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

        <label>Ubicación:</label>
        <input type="text" name="ubicacion">

        <input type="submit" value="Guardar Baja" style="margin-top: 20px;">
    </form>
</body>
</html>
