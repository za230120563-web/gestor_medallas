<?php

session_start();

include '../config/conexion.php';
include '../includes/header.php';

$usuario_id = $_SESSION['usuario']['id'];

$sql = "SELECT
e.id,
a.titulo,
e.archivo,
e.estado,
e.comentario,
e.fecha,
e.validacion_ia,
e.confianza_ia
FROM evidencias e
INNER JOIN actividades a
ON e.actividad_id = a.id
WHERE e.usuario_id='$usuario_id'";

$evidencias = $conn->query($sql);

?>

<h1>📤 Mis evidencias</h1>

<table style="width:100%; background:white; border-radius:10px; overflow:hidden; box-shadow:0px 0px 10px rgba(0,0,0,0.1);">

<tr style="background:#1f3a68;color:white;">
    <th style="padding:15px;">Actividad</th>
    <th style="padding:15px;">Archivo</th>
    <th style="padding:15px;">Estado</th>
    <th style="padding:15px;">Validación IA (Red Neuronal)</th>
    <th style="padding:15px;">Comentario RH</th>
    <th style="padding:15px;">Fecha</th>
    <th style="padding:15px;">Acción</th>
</tr>

<?php while($ev = $evidencias->fetch_assoc()){ ?>

<tr>
    <td style="padding:15px;"><?php echo $ev['titulo']; ?></td>

    <td style="padding:15px;">
        <a class="btn" target="_blank" href="../assets/uploads/<?php echo $ev['archivo']; ?>">
            Ver archivo
        </a>
    </td>

    <td style="padding:15px;"><?php echo $ev['estado']; ?></td>

    <td style="padding:15px;">
        <?php 
        $val_ia = $ev['validacion_ia'] ?? 'Sin analizar';
        $conf_ia = $ev['confianza_ia'] ?? 0;
        $bg_ia = ($val_ia === 'Pre-aprobado IA') ? '#198754' : (($val_ia === 'Formato Dudoso') ? '#d97706' : '#6c757d');
        ?>
        <span style="background: <?php echo $bg_ia; ?>; color: #fff; padding: 4px 10px; border-radius: 12px; font-size: 0.85em; font-weight: bold;">
            <?php echo htmlspecialchars($val_ia); ?> (<?php echo $conf_ia; ?>%)
        </span>
    </td>

    <td style="padding:15px;"><?php echo $ev['comentario']; ?></td>

    <td style="padding:15px;"><?php echo $ev['fecha']; ?></td>

    <td style="padding:15px;">
        <?php if($ev['estado'] == 'Pendiente revisión docente'){ ?>
            <a class="btn" href="reenviar_evidencia.php?id=<?php echo $ev['id']; ?>">🔄 Reenviar</a>
        <?php }else{ ?>
            Sin acción
        <?php } ?>
    </td>
</tr>

<?php } ?>

</table>

<?php include '../includes/footer.php'; ?>