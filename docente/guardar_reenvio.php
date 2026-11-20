<?php

session_start();

include '../config/conexion.php';

$id = $_POST['id'];

$archivo = $_FILES['archivo']['name'];
$tmp = $_FILES['archivo']['tmp_name'];

$ruta = "../assets/uploads/";

move_uploaded_file($tmp, $ruta.$archivo);

$sql = "UPDATE evidencias
SET archivo='$archivo',
estado='Enviado',
comentario=''
WHERE id='$id'";

$conn->query($sql);

header("Location: mis_evidencias.php");

?>