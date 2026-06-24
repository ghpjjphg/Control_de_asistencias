<?php include("../../include/header.php");
include("narvar.php");
include("../../data_base.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 1) {
    $grados = $conexion->query("SELECT * FROM grados");
?>
    <div class="centrarpla d-flex justify-content-center align-items-center">
        <div class="container">

            <!-- Alerta de usuario correctamente registrado -->
            <script>
                window.onload = function() {
                    if (localStorage.getItem("mostrarAlerta") === "true") {
                        alert("¡Usuario registrado correctamente!");
                        localStorage.removeItem("mostrarAlerta");
                    }
                };
            </script>

            <!-- MODAL: Registrar usuario -->
            <div class="modal fade" id="exampleModal-usuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog"> <!-- centrado y más ancho -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Usuario</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="registrar.php" method="REQUEST">
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Identificación</label>
                                        <input type="number" name="identificacion" class="form-control" placeholder="Ingrese la identificación" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Nombres</label>
                                        <input type="text" name="nombre" class="form-control" placeholder="Ingrese los nombres" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Apellidos</label>
                                        <input type="text" name="apellidos" class="form-control" placeholder="Ingrese los apellidos" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Contraseña</label>
                                        <input type="text" name="contrasena" class="form-control" placeholder="Ingrese una contraseña" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Rol</label>
                                        <select name="rol" class="form-select" required>
                                            <option value="">-- Selecciona --</option>
                                            <option value="1">Administrador</option>
                                            <option value="2">Docente</option>
                                            <option value="3">Estudiante</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" name="registrar_usurio" class="btn btn-primary">Registrar Usuario</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: Usuarios -->
            <div class="container py-5">
                <h2 class="text-center mb-5">Usuarios</h2>

                <!-- Card para administrar usuarios -->
                <div class="row justify-content-center g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="card text-center shadow-lg h-100">
                            <div class="card-body">
                                <i class="bi bi-people-fill display-1 text-primary mb-3"></i>
                                <h5 class="card-title">Administrar Usuarios</h5>
                                <p class="card-text">Gestiona los usuarios del sistema.</p>
                                <button type="button" class="btn btn-primary w-100 mt-2" data-bs-toggle="modal" data-bs-target="#exampleModal-usuario">
                                    Registrar <i class="bi bi-person-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLA DE USUARIOS -->
            <div class="card shadow mb-5">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle" id="tablaEstudiantes">
                            <thead class="table-dark">
                                <tr>
                                    <th>Identificación</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Contraseña</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $grados = $conexion->query("SELECT * FROM `administrador`");
                                while ($row = mysqli_fetch_array($grados)) {
                                    $rol = $row['rol'] == 1 ? 'Administrador' : (($row['rol'] == 2) ? 'Docente' : 'Estudiante');
                                ?>
                                    <tr>
                                        <td><?php echo $row['documento'] ?></td>
                                        <td><?php echo $row['nombres'] ?></td>
                                        <td><?php echo $row['apellidos'] ?></td>
                                        <td><?php echo $row['contrasena'] ?></td>
                                        <td><?php echo $rol ?></td>
                                        <td>
                                            <a href="form_actualizar.php?actualizar=<?php echo $row['documento'] ?>" class="btn btn-light btn-sm">
                                                <i class="bi bi-pen"></i>
                                            </a>
                                            <a href="registrar.php?eliminar=<?php echo $row['documento'] ?>" class="btn btn-danger btn-sm" onclick="return confirmacion('<?php echo $row['nombres'] ?>','<?php echo $row['documento'] ?>')">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <script>
                function confirmacion(nombre, identificacion) {
                    return confirm(`¿Desea eliminar el usuario ${nombre} con identificación ${identificacion}?`);
                }
            </script>

        </div>
    </div>


<?php include("../../include/footer.php");
} else {
    session_destroy();
    header("location:../../index.php");
}

?>