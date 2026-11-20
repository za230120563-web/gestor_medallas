<?php

session_start();

include '../config/conexion.php';
include '../includes/header.php';

$medallas = $conn->query("SELECT * FROM medallas");

?>

<h1>Crear actividad</h1>

<form action="guardar_actividad.php" method="POST">

    <input type="text" name="titulo" id="titulo" placeholder="Título de la actividad" required>

    <textarea name="descripcion" id="descripcion" rows="4" placeholder="Descripción de la actividad" required></textarea>

    <!-- Caja donde aparecerá la sugerencia de Aprendizaje Automático -->
    <div id="sugerencia-ia" style="display: none; margin: 12px 0; padding: 12px 15px; background: #eef2ff; border-left: 5px solid #1b396a; border-radius: 6px; color: #1b396a; font-weight: bold;">
        🤖 Sugerencia IA (Aprendizaje Automático): <span id="texto-medalla"></span>
    </div>

    <select name="medalla_id" id="medalla_id" required>
        <option value="">Selecciona medalla</option>
        <?php while($m = $medallas->fetch_assoc()){ ?>
            <option value="<?php echo $m['id']; ?>" data-nombre="<?php echo strtolower($m['nombre']); ?>">
                <?php echo htmlspecialchars($m['nombre']); ?>
            </option>
        <?php } ?>
    </select>

    <input type="date" name="fecha_limite" required>

    <br><br>
    <button type="submit">Guardar actividad</button>

</form>

<script>
const tituloInput = document.getElementById('titulo');
const descInput = document.getElementById('descripcion');
const cajaIA = document.getElementById('sugerencia-ia');
const textoMedalla = document.getElementById('texto-medalla');
const selectMedalla = document.getElementById('medalla_id');

let timer;
function consultarSugerencia() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        if (tituloInput.value.trim().length < 4) return;

        fetch('../ajax_sugerir_actividad.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                titulo: tituloInput.value,
                descripcion: descInput.value
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.medalla_sugerida) {
                cajaIA.style.display = 'block';
                textoMedalla.textContent = 'Se recomienda medalla de ' + data.medalla_sugerida;
                
                for (let opt of selectMedalla.options) {
                    if (opt.dataset.nombre && opt.dataset.nombre.includes(data.medalla_sugerida.toLowerCase())) {
                        selectMedalla.value = opt.value;
                    }
                }
            }
        });
    }, 400);
}

tituloInput.addEventListener('input', consultarSugerencia);
descInput.addEventListener('input', consultarSugerencia);
</script>

<?php include '../includes/footer.php'; ?>