<?php include("../../include/header.php");

include("../../data_base.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 1) {
    include("narvar.php");
    $grados = $conexion->query("SELECT * FROM grados");
?>
    <div class="centrarpla d-flex justify-content-center align-items-center">
        <div class="container">
            <!-- alerta de usurio correctamente registrado  -->
            <script>
                window.onload = function() {
                    if (localStorage.getItem("mostrarAlerta") === "true") {
                        alert("¡Estuadiante registrado correctamente!");
                        localStorage.removeItem("mostrarAlerta"); // Limpia después de mostrar
                    }
                };
            </script>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Estudiante</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <form action="registrar.php" method="REQUEST">
                                    <label for="exampleFormControlInput1" class="form-label">Identificación</label>
                                    <input type="number" name="identificacion" class="form-control" id="email" placeholder="Ingrese su identificacón" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ingrese su nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Apeliidos</label>
                                <input type="text" name="apellidos" class="form-control" id="apellidos" placeholder="Ingrese su apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Correo</label>
                                <input type="email" name="correo" class="form-control" id="apellidos" placeholder="Ingrese su correo" required>
                            </div>
                            <div class="mb-3">
                                <?php $grados2 = $conexion->query("SELECT * FROM grados"); ?>
                                <label for="grado" class="form-label">Selecciona un grado:</label>
                                <select name="grado" id="grado" class="form-select">
                                    <option value="">-- Selecciona --</option>
                                    <?php while ($g2 = $grados2->fetch_assoc()) { ?>
                                        <option value="<?= $g2['Id_Grado'] ?>"><?= $g2['Nombre'] ?></option>
                                    <?php }; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Informacion del Acudiente</h1>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nombre del acudiente</label>
                                <input type="text" name="nombre_acu" class="form-control" id="nombre" placeholder="Ingrese el nombre de su acudiente" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Contacto</label>
                                <input type="tel" name="contacto" class="form-control" placeholder="Ingrese el contacto de su acudiente" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" name="registrar_usuario" class="btn btn-primary"> Registrar Usuario</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container py-5">
                <h2 class="text-center mb-5">Estudiantes</h2>

                <!-- Row centrada -->
                <div class="row justify-content-center g-4">



                    <!-- Card 3: Administrar Estudiantes -->
                    <div class="col-md-4">
                        <div class="card text-center shadow-lg h-100">
                            <div class="card-body">
                                <!-- Icono de estudiantes -->
                                <i class="bi bi-person-lines-fill display-1 text-warning mb-3"></i>
                                <h5 class="card-title">Verificar Estudiantes de un Grado</h5>
                                <label for="grado" class="form-label">Selecciona un grado:</label>
                                <select id="gradoes" class="form-select">
                                    <option value="">-- Selecciona --</option>
                                    <?php while ($g = $grados->fetch_assoc()) { ?>
                                        <option value="<?= $g['Id_Grado'] ?>"><?= $g['Nombre'] ?></option>
                                    <?php }; ?>
                                </select> <br>
                                <button type="button" class="btn btn-warning w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Registrar <i class="bi bi-person-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-bordered table-hover text-center" id="tablaEstudiantes">
                        <thead class="table-dark">
                            <tr>
                                <th>Documento</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">Selecciona un grado para ver estudiantes</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Evento cuando cambia el grado
        $('#gradoes').on('change', function() {
            let gradoId = $(this).val();
            if (gradoId) {
                $.ajax({
                    url: 'obtener_estudiantes.php',
                    type: 'POST',
                    data: {
                        grado_id: gradoId
                    },
                    success: function(data) {
                        $('#tablaEstudiantes tbody').html(data);
                    }
                });
            } else {
                $('#tablaEstudiantes tbody').html('<tr><td colspan="4">Selecciona un grado para ver estudiantes</td></tr>');
            }
        });
    </script>
<?php include("../../include/footer.php");
} else {
    session_destroy();
    header("location:../../index.php");
}

?>