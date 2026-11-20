<?php

session_start();

include '../config/conexion.php';
require_once '../ia_helper.php';

$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$medalla_id = $_POST['medalla_id'];
$fecha_limite = $_POST['fecha_limite'];
$creado_por = $_SESSION['usuario']['id'];

$sug = consultar_ia('sugerir_actividad', [
    'titulo' => $titulo,
    'descripcion' => $descripcion
]);
$medalla_ia = $sug['medalla_sugerida'] ?? 'Plata';

$sql = "INSERT INTO actividades
(titulo, descripcion, medalla_id, medalla_sugerida_ia, fecha_limite, creado_por)
VALUES
('$titulo',
'$descripcion',
'$medalla_id',
'$medalla_ia',
'$fecha_limite',
'$creado_por')";

$conn->query($sql);

header("Location: actividades.php");

?>