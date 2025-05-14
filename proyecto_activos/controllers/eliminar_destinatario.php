<?php
include("../config/db.php");

$id = intval($_GET['id']);
$conexion->query("DELETE FROM destinatarios WHERE id = $id");

header("Location: ../views/agregar_destinatario.php");
?>
