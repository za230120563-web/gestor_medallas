<?php

session_start();

include '../config/conexion.php';

$id = $_GET['id'];

$sql = "UPDATE evidencias
SET estado='Rechazado',
comentario='La evidencia necesita correcciones'
WHERE id='$id'";

$conn->query($sql);

header("Location: revisar_evidencias.php");

?>