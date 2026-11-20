<?php

session_start();

include '../config/conexion.php';
include '../includes/header.php';

$rol_id = $_SESSION['usuario']['rol_id'];

if($rol_id != 1){
    header("Location: ../dashboard.php");
    exit();
}

$sql = "SELECT * FROM usuarios
WHERE rol_id != 1";

$resultado = $conn->query($sql);

?>

<h1>
🗑️ Eliminar usuarios
</h1>

<p style="margin-bottom:20px;">
Aquí puedes eliminar docentes y jefes de carrera.
</p>

<table>

<tr>

<th>ID</th>
<th>Nombre</th>
<th>Correo</th>
<th>Rol</th>
<th>Acción</th>

</tr>

<?php while($u = $resultado->fetch_assoc()){ ?>

<tr>

<td>
<?php echo $u['id']; ?>
</td>

<td>
<?php echo $u['nombre']; ?>
</td>

<td>
<?php echo $u['correo']; ?>
</td>

<td>

<?php

if($u['rol_id'] == 2){
    echo "Jefe de carrera";
}elseif($u['rol_id'] == 3){
    echo "Docente";
}

?>

</td>

<td>

<a class="btn"
style="background:red;"
onclick="return confirm('¿Eliminar usuario?')"
href="eliminar_usuario.php?id=<?php echo $u['id']; ?>">

Eliminar

</a>

</td>

</tr>

<?php } ?>

</table>

<?php include '../includes/footer.php'; ?>