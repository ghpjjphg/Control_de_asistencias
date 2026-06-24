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
                <h2 class="text-center mb-5">Materias y Docentes</h2>

                <!-- Row centrada -->
                <div class="row justify-content-center g-4">
                    <!-- Card 3: Administrar Estudiantes -->
                    <div class="col-md-4">
                        <div class="card text-center shadow-lg h-100">
                            <div class="card-body">
                                <!-- Icono de grados -->
                                <i class="bi bi-person-workspace display-1 text-primary mb-3"></i> <i class="bi bi-book display-1 text-primary mb-3"></i>
                                <h5 class="card-title">Administrar Materias y Docentes</h5>
                                <p class="card-text">Gestiona las materias académicas asignada a los docentes.</p>

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
                            <i class="bi bi-book"></i> Asignar Docentes
                        </button>
                    </div>
                    <table class="table table-bordered table-hover text-center" id="tablaGradoMateria">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Docente</th>
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
                        grado_ma_d: gradoId
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
                        obtener_materias_disponibles_d: gradoId
                    },
                    success: function(data) {
                        $('#modalBodyMaterias').html(data);
                    }
                });
            }
        });

        // Guardar asignaciones de docentes
        $('#guardarMaterias').on('click', function() {
            let gradoId = $('#gradoMa').val();
            let docentesAsignados = {};

            // Recorremos todos los selects de docentes
            $('select[name^="docente["]').each(function() {
                let idGradoMateria = $(this).attr('name').match(/\[(\d+)\]/)[1];
                let idDocente = $(this).val();

                if (idDocente) {
                    docentesAsignados[idGradoMateria] = idDocente;
                }
            });

            if (Object.keys(docentesAsignados).length === 0) {
                alert('Por favor selecciona al menos un docente.');
                return;
            }

            $.ajax({
                url: 'registrar.php',
                type: 'POST',
                data: {
                    asignar_materias_docente: true,
                    grado_id: gradoId,
                    docentes: docentesAsignados
                },
                success: function(response) {
                    alert(response);
                    $('#modalCheckbox').modal('hide');
                    $('#gradoMa').trigger('change'); // refresca la tabla
                }
            });
        });

        //Eliminar
        $(document).on('click', '.btnEliminarDocenteMateria', function() {
            let idGradoMateria = $(this).data('id');
            let docente = $(this).data('docente');
            let materia = $(this).data('materia');

            if (confirm(`¿Deseas eliminar la asignación del docente "${docente}" para la materia "${materia}"?`)) {
                $.ajax({
                    url: 'registrar.php',
                    type: 'POST',
                    data: {
                        eliminar_docente_materia: true,
                        id_grado_materia: idGradoMateria
                    },
                    success: function(response) {
                        if (response.trim() === "ok") {
                            alert("Se eliminó la relación del docente con la materia.");
                            $('#gradoMa').trigger('change'); // Recarga la tabla
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