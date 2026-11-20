<?php

session_start();

include '../config/conexion.php';
include '../includes/header.php';
require_once '../ia_helper.php';

$usuario_id = $_SESSION['usuario']['id'];

// Consultar medallas obtenidas
$sql_medallas = "SELECT m.nombre, m.descripcion, m.imagen 
                 FROM medallas_obtenidas mo 
                 INNER JOIN medallas m ON mo.medalla_id = m.id 
                 WHERE mo.usuario_id = '$usuario_id'";
$medallas = $conn->query($sql_medallas);

// Consultar evidencias del docente
$sql_evid = "SELECT a.titulo, e.archivo, e.estado, e.comentario, e.fecha 
             FROM evidencias e 
             INNER JOIN actividades a ON e.actividad_id = a.id 
             WHERE e.usuario_id = '$usuario_id'";
$evidencias = $conn->query($sql_evid);

// Calcular proyección personal con Regresión Polinómica
$q_ac = $conn->query("SELECT COUNT(*) AS total FROM evidencias WHERE usuario_id = '$usuario_id' AND estado = 'Aceptado'");
$total_aceptadas = $q_ac ? (int)$q_ac->fetch_assoc()['total'] : 0;

$eval_personal = consultar_ia('evaluar_docente', [
    'aceptadas' => $total_aceptadas,
    'devueltas' => 0,
    'dias'      => 2
]);
$semanas_meta = $eval_personal['semanas_proxima_medalla'] ?? 3;

?>

<h1>🏅 Mis medallas</h1>

<div class="cards" style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:20px;">
    <?php if($medallas && $medallas->num_rows > 0){ ?>
        <?php while($m = $medallas->fetch_assoc()){ ?>
            <div class="card" style="text-align:center; padding:20px; background:white; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.1); width:220px;">
                <h3><?php echo htmlspecialchars($m['nombre']); ?></h3>
                <p style="font-size:0.9em; color:#666;"><?php echo htmlspecialchars($m['descripcion']); ?></p>
                <?php if(!empty($m['imagen'])){ ?>
                    <img src="../assets/img/<?php echo htmlspecialchars($m['imagen']); ?>" alt="Medalla" style="width:90px; margin-top:10px;">
                <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>Aún no tienes medallas asignadas.</p>
    <?php } ?>
</div>

<!-- Tarjeta de Regresión Polinómica -->
<div style="background:#eef4ff; border-left:5px solid #1b396a; padding:15px 20px; border-radius:8px; margin-bottom:25px; color:#1b396a;">
    📈 <strong>Proyección IA (Regresión Polinómica):</strong> Con base en tus <strong><?php echo $total_aceptadas; ?> evidencias aceptadas</strong>, alcanzarás tu próxima medalla en aproximadamente <strong><?php echo $semanas_meta; ?> semana(s)</strong>.
</div>

<hr style="margin:30px 0;">

<h1>📤 Mis evidencias</h1>

<table style="width:100%; background:white; border-radius:10px; overflow:hidden; box-shadow:0px 0px 10px rgba(0,0,0,0.1);">
<tr style="background:#1f3a68;color:white;">
    <th style="padding:15px;">Actividad</th>
    <th style="padding:15px;">Archivo</th>
    <th style="padding:15px;">Estado</th>
    <th style="padding:15px;">Comentario</th>
    <th style="padding:15px;">Fecha</th>
</tr>
<?php while($ev = $evidencias->fetch_assoc()){ ?>
<tr>
    <td style="padding:15px;"><?php echo htmlspecialchars($ev['titulo']); ?></td>
    <td style="padding:15px;"><a class="btn" target="_blank" href="../assets/uploads/<?php echo $ev['archivo']; ?>">Ver archivo</a></td>
    <td style="padding:15px;"><?php echo htmlspecialchars($ev['estado']); ?></td>
    <td style="padding:15px;"><?php echo htmlspecialchars($ev['comentario']); ?></td>
    <td style="padding:15px;"><?php echo $ev['fecha']; ?></td>
</tr>
<?php } ?>
</table>

<?php include '../includes/footer.php'; ?>