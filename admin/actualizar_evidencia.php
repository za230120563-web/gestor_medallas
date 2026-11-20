<?php

session_start();

include '../config/conexion.php';

$id = $_POST['id'];
$comentario = $_POST['comentario'];
$accion = $_POST['accion'];

if($accion == "Aceptar"){
    $estado = "Aceptado";
}else{
    $estado = "Pendiente revisión docente";
}

$sql = "UPDATE evidencias
SET estado='$estado',
comentario='$comentario'
WHERE id='$id'";

$conn->query($sql);

if($accion == "Aceptar"){

    $consulta = "SELECT
    e.usuario_id,
    a.medalla_id

    FROM evidencias e

    INNER JOIN actividades a
    ON e.actividad_id = a.id

    WHERE e.id='$id'";

    $resultado = $conn->query($consulta);

    $datos = $resultado->fetch_assoc();

    $usuario_id = $datos['usuario_id'];
    $medalla_id = $datos['medalla_id'];

    $verificar = "SELECT *
    FROM medallas_obtenidas

    WHERE usuario_id='$usuario_id'
    AND medalla_id='$medalla_id'";

    $existe = $conn->query($verificar);

    if($existe->num_rows == 0){

        $guardar = "INSERT INTO medallas_obtenidas
        (usuario_id, medalla_id)

        VALUES

        ('$usuario_id','$medalla_id')";

        $conn->query($guardar);
    }
}

header("Location: revisar_evidencias.php");

?>