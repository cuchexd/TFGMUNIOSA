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
    <title>Panel Principal - Sistema de Activos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Bienvenido al Sistema de Gestión de Activos</h1>
        <p>Usuario: <?php echo htmlspecialchars($_SESSION['usuario']); ?> | 
        <a href="../controllers/logout.php" style="color: white;">Cerrar sesión</a></p>
    </header>

    <main>
        <h2>Seleccione una opción</h2>

        <nav>
            <ul style="list-style: none; padding: 0;">
                <li><a href="../views/traslado.php" class="boton">Registrar Traslado de Activo</a></li>
                <li><a href="../views/baja.php" class="boton">Registrar Baja de Activo</a></li>
                <li><a href="../views/vista_traslados.php" class="boton">Ver Activos Trasladados</a></li>
                <li><a href="../views/vista_bajas.php" class="boton">Ver Activos Dados de Baja</a></li>
                <li><a href="../views/registrar_admin.php" class="boton">Registrar Administrador</a></li>
                <li><a href="../views/agregar_destinatario.php" class="boton">Registrar Destinatario</a></li>
            </ul>
        </nav>
    </main>

    <footer>
        <p>&copy; 2025 Municipalidad de Osa - Sistema de Gestión de Activos</p>
    </footer>
</body>
</html>

