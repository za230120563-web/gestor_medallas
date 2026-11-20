<?php

session_start();

include '../config/conexion.php';
include '../includes/header.php';

$actividad_id = $_GET['id'];

?>

<h1>Subir evidencia</h1>

<form action="guardar_evidencia.php"
      method="POST"
      enctype="multipart/form-data">

    <input type="hidden"
           name="actividad_id"
           value="<?php echo htmlspecialchars($actividad_id); ?>">

    <input type="file"
           name="archivo"
           id="archivo"
           accept=".pdf,.png,.jpg,.jpeg"
           required>

    <div id="archivo-seleccionado"
         style="display:none;
                margin-top:15px;
                padding:12px;
                background:#e8f8ee;
                border:1px solid #9edbb3;
                border-radius:8px;
                color:#176b35;
                font-weight:bold;">

        ✅ Archivo listo para enviar
        <br>
        <span id="nombre-archivo" style="font-weight:normal;"></span>
    </div>

    <br>

    <button type="submit">
        Enviar evidencia
    </button>

</form>

<script>
const archivo = document.getElementById('archivo');
const mensaje = document.getElementById('archivo-seleccionado');
const nombre = document.getElementById('nombre-archivo');

archivo.addEventListener('change', function() {
    if (archivo.files.length > 0) {
        nombre.textContent = "📄 " + archivo.files[0].name;
        mensaje.style.display = "block";
    } else {
        mensaje.style.display = "none";
        nombre.textContent = "";
    }
});
</script>

<?php include '../includes/footer.php'; ?>