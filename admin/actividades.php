<?php

session_start();

include '../config/conexion.php';
include '../includes/header.php';

$esRH = isset($_SESSION['usuario']['rol_id'])
        && $_SESSION['usuario']['rol_id'] == 1;

$sql = "SELECT
a.id,
a.titulo,
a.descripcion,
a.fecha_limite,
a.medalla_sugerida_ia,
m.nombre AS medalla
FROM actividades a
INNER JOIN medallas m
ON a.medalla_id = m.id";

$resultado = $conn->query($sql);

?>

<div class="page-header">
    <h1>📋 Actividades</h1>
    <p>Administra las actividades disponibles para los docentes.</p>
</div>

<a class="btn-actividad" href="crear_actividad.php">
➕ Nueva actividad
</a>

<br><br>

<div class="actividad-grid">

<?php while($a = $resultado->fetch_assoc()){ ?>

<div class="actividad-card">

    <div class="actividad-icono">📘</div>

    <h3><?php echo $a['titulo']; ?></h3>

    <p class="actividad-descripcion"><?php echo $a['descripcion']; ?></p>

    <div class="actividad-info">
        <span>🏅 <?php echo $a['medalla']; ?></span>

        <?php if(!empty($a['medalla_sugerida_ia'])){ ?>
        <span style="background:#eef2ff; color:#1b396a; padding:2px 8px; border-radius:8px; font-size:0.8em; font-weight:bold;">
            🤖 Sugerida IA: <?php echo htmlspecialchars($a['medalla_sugerida_ia']); ?>
        </span>
        <?php } ?>

        <span>📅 <?php echo $a['fecha_limite']; ?></span>
    </div>

    <?php if ($esRH): ?>
        <form action="eliminar_actividad.php" method="POST"
              onsubmit="return confirm('¿Estás seguro de eliminar esta actividad?');">
            <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
            <button type="submit" class="btn-eliminar"
               style="background-color:#ef6f6f !important; color:#5c0000 !important; width:auto !important; padding:10px 18px !important; border:none !important; border-radius:8px !important; cursor:pointer !important; font-weight:800 !important; font-size:14px !important; box-shadow:0 2px 5px rgba(0,0,0,0.15) !important;">
                🗑️ Eliminar
            </button>
        </form>
    <?php endif; ?>

</div>

<?php } ?>
</div>

<?php include '../includes/footer.php'; ?>