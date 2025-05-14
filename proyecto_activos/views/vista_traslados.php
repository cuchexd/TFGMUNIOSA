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
    <title>Vista de Activos Trasladados</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table {
            width: 95%;
            border-collapse: collapse;
            margin: 20px auto;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        th {
            background-color: #0c6d45;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        input[type="submit"] {
            background-color: #0c6d45;
            color: white;
            padding: 10px 15px;
            border: none;
            margin: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Vista de Activos Trasladados</h1>
        <a href="dashboard.php" style="position: absolute; top: 20px; right: 20px; background-color: #0c6d45; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">Volver al Dashboard</a>
    </header>

    <main>
        <h2>Buscar por número o tipo de activo</h2>
        <form method="GET" action="">
            <input type="text" name="busqueda" placeholder="Ej: 123 o computadora" required>
            <input type="submit" value="Buscar">
        </form>

        <?php
        include("../config/db.php");

        $filtro = "";
        if (isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
            $busqueda = $conexion->real_escape_string($_GET['busqueda']);
            $filtro = "WHERE numero_activo LIKE '%$busqueda%' OR tipo_activo LIKE '%$busqueda%'";
        }

        $query = "SELECT * FROM traslados $filtro ORDER BY fecha_traslado DESC";
        $resultado = $conexion->query($query);

        if ($resultado->num_rows > 0) {
            echo "<table>
                <thead>
                    <tr>
                        <th>Número Activo</th>
                        <th>Fecha Aprobación</th>
                        <th>Fecha Traslado</th>
                        <th>Canal Comunicación</th>
                        <th>Motivo</th>
                        <th>Tipo de Activo</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Serie</th>
                        <th>Departamento</th>
                        <th>Checklist</th>
                        <th>Ubicación Anterior</th>
                        <th>Ubicación Actual</th>
                    </tr>
                </thead>
                <tbody>";

            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['numero_activo']}</td>
                    <td>{$row['fecha_aprobacion']}</td>
                    <td>{$row['fecha_traslado']}</td>
                    <td>{$row['canal_comunicacion']}</td>
                    <td>{$row['motivo']}</td>
                    <td>{$row['tipo_activo']}</td>
                    <td>{$row['modelo']}</td>
                    <td>{$row['marca']}</td>
                    <td>{$row['serie']}</td>
                    <td>{$row['departamento']}</td>
                    <td>{$row['checklist_completado']}</td>
                    <td>{$row['ubicacion_anterior']}</td>
                    <td>{$row['ubicacion_actual']}</td>
                </tr>";
            }

            echo "</tbody></table>";

            // Botón para generar PDF
            echo "<form method='POST' action='../controllers/descargar_pdf_traslados.php'>
                <input type='hidden' name='busqueda' value='" . htmlspecialchars($busqueda ?? "") . "'>
                <input type='submit' value='Descargar PDF'>
            </form>";

        } else {
            echo "<p style='text-align: center;'>No se encontraron resultados.</p>";
        }
        ?>
    </main>

    <footer>
        <p>&copy; 2025 Municipalidad de Osa - Sistema de Gestión de Activos</p>
    </footer>
</body>
</html>
