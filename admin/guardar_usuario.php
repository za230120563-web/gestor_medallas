<?php

session_start();

include '../config/conexion.php';

$rol_actual = $_SESSION['usuario']['rol_id'];

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$rol_id = $_POST['rol_id'];

if($rol_actual != 1 && $rol_actual != 2){
    die("No tienes permiso para registrar usuarios.");
}

if($rol_actual == 2 && $rol_id != 3){
    die("La División de Carrera solo puede registrar docentes.");
}

if($rol_actual == 1 && ($rol_id != 2 && $rol_id != 3)){
    die("El Admin solo puede registrar División de Carrera o docentes.");
}

$sql = "INSERT INTO usuarios
(nombre, correo, password, rol_id)
VALUES
('$nombre', '$correo', '$password', '$rol_id')";

$conn->query($sql);

header("Location: usuarios.php");

?>