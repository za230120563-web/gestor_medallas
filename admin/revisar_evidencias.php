<?php

session_start();

include '../config/conexion.php';
include '../includes/header.php';

$sql = "SELECT
e.id,
u.nombre AS docente,
a.titulo AS actividad,
e.archivo,
e.estado,
e.validacion_ia,
e.confianza_ia,
e.fecha
FROM evidencias e
INNER JOIN usuarios u ON e.usuario_id = u.id
INNER JOIN actividades a ON e.actividad_id = a.id
WHERE e.estado IN ('Enviado', 'Pendiente')
ORDER BY e.confianza_ia ASC";

$evidencias = $conn->query($sql);

?>

<h1>📤 Evidencias enviadas por revisar</h1>

<table style="width:100%; background:white; border-radius:10px; overflow:hidden; box-shadow:0px 0px 10px rgba(0,0,0,0.1);">

<tr style="background:#1f3a68;color:white;">
    <th style="padding:15px;">Docente</th>
    <th style="padding:15px;">Actividad</th>
    <th style="padding:15px;">Archivo</th>
    <th style="padding:15px;">Validación IA (Red Neuronal)</th>
    <th style="padding:15px;">Fecha</th>
    <th style="padding:15px;">Acciones</th>
</tr>

<?php if($evidencias && $evidencias->num_rows > 0){ ?>
    <?php while($ev = $evidencias->fetch_assoc()){ ?>
    <tr>
        <td style="padding:15px;"><?php echo htmlspecialchars($ev['docente']); ?></td>
        <td style="padding:15px;"><?php echo htmlspecialchars($ev['actividad']); ?></td>
        <td style="padding:15px;">
            <a class="btn" target="_blank" href="../assets/uploads/<?php echo $ev['archivo']; ?>">Ver archivo</a>
        </td>
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
        <td style="padding:15px;"><?php echo $ev['fecha']; ?></td>
        <td style="padding:15px;">
            <form action="procesar_evidencia.php" method="POST" style="display:flex; gap:8px; align-items:center;">
                <input type="hidden" name="evidencia_id" value="<?php echo $ev['id']; ?>">
                <input type="text" name="comentario" placeholder="Comentario RH..." style="padding:6px; border-radius:6px; border:1px solid #ccc;">
                <button type="submit" name="accion" value="Aceptado" style="background:#198754; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer;">✅ Aceptar</button>
                <button type="submit" name="accion" value="Devuelto" style="background:#dc3545; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer;">🔁 Devolver</button>
            </form>
        </td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="6" style="padding:20px; text-align:center;">No hay evidencias pendientes por revisar.</td>
    </tr>
<?php } ?>

</table>

<?php include '../includes/footer.php'; ?>