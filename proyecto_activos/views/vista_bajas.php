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
    <title>Vista de Activos Dados de Baja</title>
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
        <h1>Vista de Activos Dados de Baja</h1>
        <a href="dashboard.php" style="position: absolute; top: 20px; right: 20px; background-color: #0c6d45; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">Volver al Dashboard</a>
    </header>

    <main>
        <h2>Buscar Activos Dados de Baja</h2>
        <form method="GET" action="">
            <input type="text" name="busqueda" placeholder="Buscar por número o tipo de baja" required>
            <input type="submit" value="Buscar">
        </form>

        <?php
        include("../config/db.php");

        $filtro = "";
        if (isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
            $busqueda = $conexion->real_escape_string($_GET['busqueda']);
            $filtro = "WHERE numero_activo LIKE '%$busqueda%' OR tipo_baja LIKE '%$busqueda%'";
        }

        $query = "SELECT * FROM bajas $filtro ORDER BY fecha_baja DESC";
        $resultado = $conexion->query($query);

        if ($resultado->num_rows > 0) {
            echo "<table>
                <thead>
                    <tr>
                        <th>Número Activo</th>
                        <th>Fecha Baja</th>
                        <th>Fecha Recepción</th>
                        <th>Oficio Baja</th>
                        <th>Tipo Baja</th>
                        <th>Clase Activo</th>
                        <th>Motivo</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Serie</th>
                        <th>Departamento</th>
                        <th>Checklist</th>
                        <th>Ubicación</th>
                    </tr>
                </thead>
                <tbody>";

            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row['numero_activo']) . "</td>
                    <td>" . htmlspecialchars($row['fecha_baja']) . "</td>
                    <td>" . htmlspecialchars($row['fecha_recepcion']) . "</td>
                    <td>" . htmlspecialchars($row['oficio_baja']) . "</td>
                    <td>" . htmlspecialchars($row['tipo_baja']) . "</td>
                    <td>" . htmlspecialchars($row['clase_activo']) . "</td>
                    <td>" . htmlspecialchars($row['motivo']) . "</td>
                    <td>" . htmlspecialchars($row['modelo']) . "</td>
                    <td>" . htmlspecialchars($row['marca']) . "</td>
                    <td>" . htmlspecialchars($row['serie']) . "</td>
                    <td>" . htmlspecialchars($row['departamento']) . "</td>
                    <td>" . htmlspecialchars($row['checklist_completado']) . "</td>
                    <td>" . htmlspecialchars($row['ubicacion']) . "</td>
                </tr>";
            }

            echo "</tbody></table>";

            // Botón para descargar PDF
            echo "<form method='POST' action='../controllers/descargar_pdf_bajas.php'>
                <input type='hidden' name='busqueda' value='" . htmlspecialchars($busqueda ?? "") . "'>
                <input type='submit' value='Descargar PDF'>
            </form>";

        } else {
            echo "<p style='text-align: center;'>No se encontraron activos dados de baja.</p>";
        }
        ?>
    </main>

    <footer>
        <p>&copy; 2025 Municipalidad de Osa - Sistema de Gestión de Activos</p>
    </footer>
</body>
</html>
