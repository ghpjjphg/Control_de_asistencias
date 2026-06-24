<?php include("../../include/header.php");
include("../../data_base.php");
include("narvar.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 1) {
    $grados = $conexion->query("SELECT * FROM grados");
?>
    <div class="centrarpla d-flex justify-content-center align-items-center">
        <div class="container">
            <!-- Modal -->
            <div class="modal fade" id="modalCheckbox" tabindex="-1" aria-labelledby="modalCheckboxLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCheckboxLabel">Selecciona las materias a asignar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body" id="modalBodyMaterias">
                            <!-- Aquí se cargan dinámicamente las materias disponibles -->
                            <p class="text-center text-muted">Cargando materias...</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="guardarMaterias">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container py-5">
                <h2 class="text-center mb-5">Materias y Grados</h2>

                <!-- Row centrada -->
                <div class="row justify-content-center g-4">
                    <!-- Card 3: Administrar Estudiantes -->
                    <div class="col-md-4">
                        <div class="card text-center shadow-lg h-100">
                            <div class="card-body">
                                <!-- Icono de grados -->
                             <i class="bi bi-journal-bookmark-fill display-1 text-primary mb-3"></i> <i class="bi bi-book display-1 text-primary mb-3"></i> 
                                <h5 class="card-title">Administrar Materias y Grados</h5>
                                <p class="card-text">Gestiona las materias académicas asignadas a los grados.</p>

                                <!-- Botones alineados y del mismo tamaño -->
                                <div class="d-flex justify-content-center flex-wrap gap-2 mt-3">
                                    <label for="grado" class="form-label">Selecciona un grado:</label>
                                    <select id="gradoMa" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <?php while ($g = $grados->fetch_assoc()) { ?>
                                            <option value="<?= $g['Id_Grado'] ?>"><?= $g['Nombre'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-md-flex justify-content-md-end mb-2">
                        <!-- Este botón inicia oculto -->
                        <button type="button" id="btnAsignarMaterias" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#modalCheckbox">
                            <i class="bi bi-book"></i> Asignar materia
                        </button>
                    </div>
                    <table class="table table-bordered table-hover text-center" id="tablaGradoMateria">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3">Selecciona un grado para ver las materias asignadas</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Cuando cambia el grado
        $('#gradoMa').on('change', function() {
            let gradoId = $(this).val();
            if (gradoId) {
                $('#btnAsignarMaterias').removeClass('d-none');
                $('#btnAsignarMaterias').data('grado', gradoId);
                $.ajax({
                    url: 'registrar.php',
                    type: 'POST',
                    data: {
                        grado_ma: gradoId
                    },
                    success: function(data) {
                        $('#tablaGradoMateria tbody').html(data);
                    }
                });
            } else {
                $('#btnAsignarMaterias').addClass('d-none');
                $('#tablaGradoMateria tbody').html('<tr><td colspan="3">Selecciona un grado para ver las materias asignadas</td></tr>');
            }
        });

        // Cuando se abre el modal, traer materias disponibles
        $(document).on('click', '#btnAsignarMaterias', function() {
            let gradoId = $('#gradoMa').val();
            if (gradoId) {
                $.ajax({
                    url: 'registrar.php',
                    type: 'POST',
                    data: {
                        obtener_materias_disponibles: gradoId
                    },
                    success: function(data) {
                        $('#modalBodyMaterias').html(data);
                    }
                });
            }
        });

        // Guardar materias seleccionadas
        $('#guardarMaterias').on('click', function() {
            let gradoId = $('#gradoMa').val();
            let materiasSeleccionadas = [];
            $('input[name="materias[]"]:checked').each(function() {
                materiasSeleccionadas.push($(this).val());
            });

            if (materiasSeleccionadas.length === 0) {
                alert('Por favor selecciona al menos una materia.');
                return;
            }

            $.ajax({
                url: 'registrar.php',
                type: 'POST',
                data: {
                    asignar_materias: true,
                    grado_id: gradoId,
                    materias: materiasSeleccionadas
                },
                success: function(response) {
                    alert('Materias asignadas correctamente.');
                    $('#modalCheckbox').modal('hide');
                    $('#gradoMa').trigger('change'); // Actualiza la tabla
                }
            });
        });
        //Eliminar
        $(document).on('click', '.btnEliminarMateria', function() {
            let idMateria = $(this).data('id');
            let idGrado = $(this).data('grado');
            let nombreMateria = $(this).data('nombre');

            // Alerta personalizada
            if (confirm("¿Desea eliminar la materia '" + nombreMateria + "' asociada a este grado?")) {
                $.ajax({
                    url: 'registrar.php',
                    type: 'POST',
                    data: {
                        eliminar_materia: true,
                        id_materia: idMateria,
                        id_grado: idGrado
                    },
                    success: function(response) {
                        if (response.trim() === "ok") {
                            alert("La relación fue eliminada correctamente.");
                            $('#gradoMa').trigger('change'); // recarga la tabla
                        } else {
                            alert("Ocurrió un error al eliminar la relación.");
                        }
                    }
                });
            }
        });
    </script>

<?php include("../../include/footer.php");
} else {
    session_destroy();
    header("location:../../index.php");
}

?>