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
    <title>Agregar Destinatario</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Estilo adicional para mejorar la presentación */
        input[type="email"] {
            width: 300px;
            padding: 10px;
            font-size: 14px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            color: red;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Agregar Correo Destinatario</h1>
        <a href="dashboard.php" style="position: absolute; top: 20px; right: 20px; background-color: #0c6d45; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-size: 14px;">Volver al Dashboard</a>
    </header>

    <main>
        <form action="../controllers/guardar_destinatario.php" method="POST">
            <label for="correo">Correo electrónico:</label><br>
            <input type="email" id="correo" name="correo" required><br><br>
            <input type="submit" value="Agregar">
        </form>

        <h2>Destinatarios Registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Correo Electrónico</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("../config/db.php");
                $resultado = $conexion->query("SELECT * FROM destinatarios ORDER BY id DESC");
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr><td>" . htmlspecialchars($fila['correo']) . "</td>" .
                         "<td><a href='../controllers/eliminar_destinatario.php?id=" . $fila['id'] . "'>❌ Eliminar</a></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2025 Municipalidad de Osa - Sistema de Gestión de Activos</p>
    </footer>
</body>
</html>
