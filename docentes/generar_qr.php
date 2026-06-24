<?php include("../data_base.php");
include("../include/header.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 2) {
    include("narvar.php");
    // Docente logueado
    $id_docente = $_SESSION['documento'];

    // 🔹 Consultamos nombre del docente desde la base de datos
    $docenteQuery = $conexion->query("SELECT Nombre FROM docentes WHERE Id_Docente = $id_docente LIMIT 1");
    $docente = $docenteQuery->fetch_assoc();
    $nombre_docente = $docente ? $docente['Nombre'] : 'Desconocido';

    // 🔹 Traer los grados asociados a ese docente
    $grados = $conexion->query("
    SELECT DISTINCT g.Id_Grado, g.Nombre 
    FROM grados g 
    INNER JOIN grado_materia gm ON g.Id_Grado = gm.Id_Grado 
    INNER JOIN docente_grado_materia dgm ON gm.id_gr_mt = dgm.Id_Grado_Materia 
    WHERE dgm.Id_Docente = $id_docente  
    ORDER BY g.Nombre ASC
");
?>

    <div class="centrarpla d-flex justify-content-center align-items-center">
        <div class="container py-5">
            <h2 class="text-center mb-5">Generar QR de Asistencia</h2>

            <div class="row justify-content-center g-4">
                <div class="col-md-6">
                    <div class="card text-center shadow-lg h-100">
                        <div class="card-body">
                            <i class="bi bi-qr-code display-1 text-success mb-3"></i>
                            <h5 class="card-title mb-3">Selecciona los datos</h5>

                            <!-- Select de grados -->
                            <label for="gradoes" class="form-label">Selecciona un grado:</label>
                            <select id="gradoes" class="form-select">
                                <option value="">-- Selecciona --</option>
                                <?php while ($g = $grados->fetch_assoc()) { ?>
                                    <option value="<?= $g['Id_Grado'] ?>"><?= htmlspecialchars($g['Nombre']) ?></option>
                                <?php } ?>
                            </select>

                            <!-- Select de materias (oculto al inicio) -->
                            <div id="materia-container" class="mt-3" style="display: none;">
                                <label for="materia" class="form-label">Selecciona una materia:</label>
                                <select id="materia" class="form-select">
                                    <option value="">-- Selecciona un grado primero --</option>
                                </select>
                            </div>

                            <!-- Contenedor del QR -->
                            <div id="qr-container" class="text-center mt-4" style="display:none;">
                                <h5>QR de asistencia</h5>
                                <img id="qr-image" src="" alt="Código QR" class="img-fluid border rounded p-2" style="max-width:220px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        $(function() {
            // Al cambiar el grado, cargar materias
            $('#gradoes').on('change', function() {
                let gradoId = $(this).val();

                if (gradoId) {
                    $.ajax({
                        url: 'obtener_materias.php',
                        type: 'POST',
                        data: {
                            grado_id: gradoId
                        },
                        success: function(data) {
                            $('#materia').html(data);
                            $('#materia-container').slideDown();
                            $('#qr-container').hide(); // Oculta QR al cambiar grado
                        }
                    });
                } else {
                    $('#materia-container').slideUp();
                    $('#materia').html('<option value="">-- Selecciona un grado primero --</option>');
                    $('#qr-container').hide();
                }
            });

            // Al cambiar la materia, generar QR
            $('#materia').on('change', function() {
                let materiaId = $(this).val();
                let materiaNombre = $('#materia option:selected').text();
                let gradoId = $('#gradoes').val();
                let gradoNombre = $('#gradoes option:selected').text();

                if (materiaId) {
                    let docenteId = <?= json_encode($id_docente); ?>;
                    let docenteNombre = <?= json_encode($nombre_docente); ?>;

                    // Información dentro del QR
                    // let qrData = `ID Docente: ${docenteId}\nNombre: ${docenteNombre}\nGrado: ${gradoNombre}\nMateria: ${materiaNombre}`;
                    // Información dentro del QR solo con IDs
                    let qrData = `ID_Docente: ${docenteId}\nID_Grado: ${gradoId}\nID_Materia: ${materiaId}`;


                    // URL del servicio gratuito QRServer
                    let qrUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrData)}&size=220x220`;

                    // Mostrar QR
                    $('#qr-image').attr('src', qrUrl);
                    $('#qr-container').fadeIn();
                } else {
                    $('#qr-container').fadeOut();
                }
            });
        });
    </script>


<?php include("../include/footer.php");
} else {
    session_destroy();
    header("location:../index.php");
}

?>