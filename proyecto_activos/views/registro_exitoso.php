<?php
session_start();
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'registro';
$error = isset($_GET['error']) ? $_GET['error'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado del Registro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header>
    <h1>Resultado del Registro</h1>
</header>

<main>
    <?php if ($error == "correo"): ?>
        <div style="background-color: #ffcccc; color: #990000; padding: 10px; margin: 20px; border-radius: 5px;">
            ❌ El registro de <?php echo htmlspecialchars($tipo); ?> se guardó, pero hubo un error al enviar el correo.
        </div>
    <?php elseif ($error == "guardar"): ?>
        <div style="background-color: #ffcccc; color: #990000; padding: 10px; margin: 20px; border-radius: 5px;">
            ❌ Error al guardar el registro. Intente nuevamente.
        </div>
    <?php else: ?>
        <div style="background-color: #ccffcc; color: #006600; padding: 10px; margin: 20px; border-radius: 5px;">
            ✅ Registro de <?php echo htmlspecialchars($tipo); ?> realizado exitosamente.
        </div>
    <?php endif; ?>

    <br>
    <a href="dashboard.php" class="boton">⬅️ Volver al Dashboard</a>
</main>

<footer>
    <p>&copy; 2025 Municipalidad de Osa</p>
</footer>

</body>
</html>
