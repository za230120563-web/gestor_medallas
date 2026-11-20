<?php

session_start();

include '../config/conexion.php';
include '../includes/header.php';

$sql = "SELECT
a.id,
a.titulo,
a.descripcion,
a.fecha_limite,
m.nombre AS medalla
FROM actividades a
INNER JOIN medallas m
ON a.medalla_id = m.id";

$resultado = $conn->query($sql);

?>

<div class="page-header">
    <h1>📋 Actividades disponibles</h1>
    <p>Consulta las actividades asignadas y sube tu evidencia correspondiente.</p>
</div>

<div class="actividad-grid">

<?php while($a = $resultado->fetch_assoc()){ ?>

<div class="actividad-card">

    <div class="actividad-icono">
        📘
    </div>

    <h3>
        <?php echo htmlspecialchars($a['titulo']); ?>
    </h3>

    <p class="actividad-descripcion">
        <?php echo htmlspecialchars($a['descripcion']); ?>
    </p>

    <div class="actividad-info">
        <span>
            🏅 <?php echo htmlspecialchars($a['medalla']); ?>
        </span>

        <span>
            📅 <?php echo $a['fecha_limite']; ?>
        </span>
    </div>

    <a class="btn-actividad" href="subir_evidencia.php?id=<?php echo $a['id']; ?>">
        📤 Subir evidencia
    </a>

</div>

<?php } ?>

</div>

<?php include '../includes/footer.php'; ?>