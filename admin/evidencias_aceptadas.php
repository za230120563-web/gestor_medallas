<?php
session_start();

include '../config/conexion.php';
include '../includes/header.php';

$sql = "SELECT
u.nombre,
a.titulo,
e.archivo,
e.estado,
e.comentario
FROM evidencias e
INNER JOIN usuarios u ON e.usuario_id = u.id
INNER JOIN actividades a ON e.actividad_id = a.id
WHERE e.estado='Aceptado'";

$resultado = $conn->query($sql);
?>

<h1>✅ Evidencias aceptadas</h1>

<table>
<tr>
<th>Docente</th>
<th>Actividad</th>
<th>Archivo</th>
<th>Estado</th>
<th>Comentario</th>
</tr>

<?php while($e = $resultado->fetch_assoc()){ ?>
<tr>
<td><?php echo $e['nombre']; ?></td>
<td><?php echo $e['titulo']; ?></td>
<td>
<a class="btn" target="_blank" href="../assets/uploads/<?php echo $e['archivo']; ?>">
Ver archivo
</a>
</td>
<td><?php echo $e['estado']; ?></td>
<td><?php echo $e['comentario']; ?></td>
</tr>
<?php } ?>

</table>

<?php include '../includes/footer.php'; ?>