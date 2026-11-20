<?php

session_start();

include '../config/conexion.php';
include '../includes/header.php';

$id = $_GET['id'];

?>

<h1>
🔄 Reenviar evidencia
</h1>

<form
action="guardar_reenvio.php"
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="id"
value="<?php echo $id; ?>">

<label>
Selecciona nuevo archivo
</label>

<input
type="file"
name="archivo"
required>

<br><br>

<button type="submit">

Enviar corrección

</button>

</form>

<?php
include '../includes/footer.php';
?>