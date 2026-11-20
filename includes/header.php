<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$rol_id = $_SESSION['usuario']['rol_id'];

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Gestor de Medallas
</title>

<link rel="stylesheet"
href="../assets/css/styles.css">

</head>

<body>

<div class="sidebar">

<h2>
🏅 Medallas
</h2>

<a href="../dashboard.php">
🏠 Inicio
</a>

<!-- RH -->
<?php if($rol_id == 1){ ?>

<a href="../admin/actividades.php">
📋 Actividades
</a>

<a href="../admin/revisar_evidencias.php">
📤 Evidencias
</a>

<a href="../admin/evidencias_aceptadas.php">
✅ Aceptadas
</a>

<a href="../admin/evidencias_rechazadas.php">
🔁 Devueltas
</a>

<?php } ?>

<!-- JEFE DE CARRERA -->
<?php if($rol_id == 2){ ?>

<a href="../docente/actividades.php">
📋 Actividades disponibles
</a>

<a href="../admin/revisar_evidencias.php">
📤 Evidencias
</a>

<?php } ?>

<!-- DOCENTE -->
<?php if($rol_id == 3){ ?>

<a href="../docente/actividades.php">
📋 Actividades disponibles
</a>

<a href="../docente/mis_evidencias.php">
📤 Mis evidencias
</a>

<a href="../docente/perfil.php">
🏅 Mis medallas
</a>

<?php } ?>

<a href="../logout.php">
🚪 Cerrar sesión
</a>

</div>

<div class="contenido">