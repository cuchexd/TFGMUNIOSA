<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Activos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Estilo para mensajes de error */
        .error-message {
            background-color: #ffcccc;
            color: #990000;
            padding: 10px;
            margin: 15px auto;
            width: 300px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

    <header>
        <h1>Municipalidad de Osa</h1>
    </header>

    <main>
        <h2>Iniciar Sesión</h2>

        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'contraseña') {
                echo '<div class="error-message">❌ Contraseña incorrecta. Por favor intenta de nuevo.</div>';
            } elseif ($_GET['error'] == 'usuario') {
                echo '<div class="error-message">❌ Usuario no encontrado. Verifica tus datos.</div>';
            }
        }
        ?>

        <form action="../controllers/validar_login.php" method="POST">
            <label>Usuario:</label><br>
            <input type="text" name="usuario" required><br><br>

            <label>Contraseña:</label><br>
            <input type="password" name="clave" required><br><br>

            <input type="submit" value="Ingresar">
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Municipalidad de Osa - Sistema de Gestión de Activos</p>
    </footer>

</body>
</html>
