<?php 
include("../../data_base.php");
include("../../include/header.php");
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
                        alert("¡Docente registrado correctamente!");
                        localStorage.removeItem("mostrarAlerta"); // Limpia después de mostrar
                    }
                };
            </script>
            <!-- modal grado -->
            <div class="modal fade" id="exampleModal-grado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Docente</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <form action="registrar.php" method="REQUEST">
                                    <label for="exampleFormControlInput1" class="form-label">Identificación del docente</label>
                                    <input type="number" name="identificacion" class="form-control" id="email" placeholder="Ingrese la identificacion del docente" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nombre completo</label>
                                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ingrese el nombre completo del docente" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Correo</label>
                                <input type="email" name="correo" class="form-control" id="nombre" placeholder="Ingrese el correo del docente" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" name="registrar_grado" class="btn btn-primary"> Registrar Docente</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container py-5">
                <h2 class="text-center mb-5">Docentes</h2>

                <!-- Row centrada -->
                <div class="row justify-content-center g-4">



                    <!-- Card 3: Administrar Estudiantes -->
                    <div class="col-md-4">
                        <div class="card text-center shadow-lg h-100">
                            <div class="card-body">
                                <!-- Icono de grados -->
                                <i class="bi bi-person-workspace display-1 text-success mb-3"></i>
                                <h5 class="card-title">Administrar Docentes</h5>
                                <p class="card-text">Gestiona los Docentes académicos.</p><br>
                                <button type="button" class="btn btn-success w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#exampleModal-grado">
                                    Registrar <i class="bi bi-person-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center" id="tablaEstudiantes">
                            <thead class="table-dark">
                                <tr>
                                    <th>Identificación</th>
                                    <th>Nombre Completo</th>
                                    <th>Correo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $docentes = $conexion->query("SELECT * FROM `docentes`");
                                while ($row = mysqli_fetch_array($docentes)) {
                                    # code...

                                ?>
                                    <tr>
                                        <td><?php echo $row['Id_Docente'] ?></td>
                                        <td><?php echo $row['Nombre'] ?></td>
                                        <td><?php echo $row['Correo'] ?></td>
                                        <td><a href="form_actualizar.php?actualizar=<?php echo $row['Id_Docente'] ?>" class="btn btn-light"><i class="bi bi-pen"></i> </a> <a href="registrar.php?eliminar=<?php echo $row['Id_Docente'] ?>" class="btn btn-danger" onclick=" return confirmacion('<?php echo $row['Nombre'] ?>','<?php echo $row['Id_Docente'] ?>')"><i class="bi bi-trash-fill"></i></a></td>
                                    </tr>
                                <?php } ?>
                                <script>
                                    function confirmacion(nombre, identificacion) {
                                        var confirmaction = confirm("¿Desea eliminar el Docente " + nombre + " con identificación " + identificacion)
                                        if (confirmaction == true) {
                                            return true
                                        } else {
                                            return false
                                        }
                                    }
                                </script>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include("../../include/footer.php");
} else {
    session_destroy();
    header("location:../../index.php");
}

?>