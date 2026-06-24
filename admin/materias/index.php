<?php include("../../include/header.php");
include("../../data_base.php");
include("narvar.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 1) {
    $materias = $conexion->query("SELECT * FROM materias");
?>
    <div class="centrarpla d-flex justify-content-center align-items-center">
        <div class="container">
            <!-- alerta de usurio correctamente registrado  -->
            <script>
                window.onload = function() {
                    if (localStorage.getItem("mostrarAlerta") === "true") {
                        alert("¡Materia registrada correctamente!");
                        localStorage.removeItem("mostrarAlerta"); // Limpia después de mostrar
                    }
                };
            </script>
            <!-- modal grado -->
            <div class="modal fade" id="exampleModal-grado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Materia</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <form action="registrar.php" method="REQUEST">
                                    <label for="exampleFormControlInput1" class="form-label">Identificación de la Materia</label>
                                    <input type="number" name="identificacion" class="form-control" id="email" placeholder="Ingrese la identificacion de la materia" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nombre de la materia</label>
                                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ingrese el nombre de la materia" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" name="registrar_grado" class="btn btn-primary"> Registrar Materia</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container py-5">
                <h2 class="text-center mb-5">Materias</h2>

                <!-- Row centrada -->
                <div class="row justify-content-center g-4">
                    <!-- Card 3: Administrar Estudiantes -->
                    <div class="col-md-4">
                        <div class="card text-center shadow-lg h-100">
                            <div class="card-body">
                                <!-- Icono de grados -->
                                <i class="bi bi-book display-1 text-primary mb-3"></i>
                                <h5 class="card-title">Administrar Materias</h5>
                                <p class="card-text">Gestiona las materias académicas.</p>

                                <!-- Botones alineados y del mismo tamaño -->
                                <div class="d-flex justify-content-center flex-wrap gap-2 mt-3">
                                    <button type="button" class="btn btn-primary flex-fill" style="min-width: 150px;" data-bs-toggle="modal" data-bs-target="#exampleModal-grado">
                                        Registrar <i class="bi bi-book"></i>
                                    </button>
                                    <a href="materias_grados.php"><button type="button" class="btn btn-primary flex-fill" style="min-width: 150px;">
                                            <i class="bi bi-journal-bookmark-fill"></i> Grados y Materias <i class="bi bi-book"></i>
                                        </button></a>
                                   <a href="docente_grados.php"> <button type="button" class="btn btn-primary flex-fill" style="min-width: 150px;">
                                        <i class="bi bi-person-workspace"></i> Docentes y Materias <i class="bi bi-book"></i>
                                    </button></a>
                                </div>
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
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            while ($row = mysqli_fetch_array($materias)) {
                                # code...

                            ?>
                                <tr>
                                    <td><?php echo $row['Id_Materia'] ?></td>
                                    <td><?php echo $row['Nombre'] ?></td>
                                    <td><a href="form_actualizar.php?actualizar=<?php echo $row['Id_Materia'] ?>" class="btn btn-light"><i class="bi bi-pen"></i> </a> <a href="registrar.php?eliminar=<?php echo $row['Id_Materia'] ?>" class="btn btn-danger" onclick=" return confirmacion('<?php echo $row['Nombre'] ?>','<?php echo $row['Id_Materia'] ?>')"><i class="bi bi-trash-fill"></i></a></td>
                                </tr>
                            <?php } ?>
                            <script>
                                function confirmacion(nombre, identificacion) {
                                    var confirmaction = confirm("¿Desea eliminar la " + nombre + " con identificacion " + identificacion)
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

<?php include("../../include/footer.php");
} else {
    session_destroy();
    header("location:../../index.php");
}

?>