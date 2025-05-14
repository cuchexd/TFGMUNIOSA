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
    <title>Registrar Administrador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Registrar Nuevo Administrador</h1>
        <a href="dashboard.php" style="position: absolute; top: 20px; right: 20px; background-color: #0c6d45; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-size: 14px;">Volver al Dashboard</a>
    </header>

    <main>
        <?php
        if (isset($_GET['exito']) && $_GET['exito'] == 1) {
            echo "<p style='color: green;'>✅ Administrador registrado correctamente.</p>";
        }
        ?>

        <form action="../controllers/guardar_admin.php" method="POST">
            <label for="nombre">Nombre Completo:</label><br>
            <input type="text" id="nombre" name="nombre" required><br><br>

            <label for="usuario">Usuario:</label><br>
            <input type="text" id="usuario" name="usuario" required><br><br>

            <label for="clave">Contraseña:</label><br>
            <input type="password" id="clave" name="clave" required><br><br>

            <input type="submit" value="Registrar Administrador">
        </form>
    </main>
    <footer>
        <p>&copy; 2025 Municipalidad de Osa - Sistema de Gestión de Activos</p>
    </footer>
</body>
</html>
