<?php include("../../include/header.php");
include("narvar.php");
include("../../data_base.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 1) {
    if (isset($_REQUEST['actualizar'])) {
        $documento = $_REQUEST['actualizar'];
        $Actualizar = $conexion->query("SELECT * FROM `administrador` WHERE administrador.documento=$documento");
        $row = mysqli_fetch_array($Actualizar);
    }
?>
    <div class="centrarpla d-flex justify-content-center align-items-center">
        <div class="container text-center card card-body">
            <h5>Información del usuario</h5>
            <form action="registrar.php" method="REQUEST">
                <div class="form_group">
                    <h6>Identificación del usuario
                    </h6>
                    <input class="text-center" type="number" name="identificacion" value="<?php echo $row['documento']; ?>" class="from_control" readonly>
                </div>
                <div class="form_group">
                    <h6>Nombre del usuario</h6>
                    <input class="text-center" type="text" name="nombre" value="<?php echo $row['nombres']; ?>" class="from_control" required>
                </div>
                <div class="form_group">
                    <h6>Apellidos del usuario</h6>
                    <input class="text-center" type="text" name="apellidos" value="<?php echo $row['apellidos']; ?>" class="from_control" required>
                </div>
                <div class="form_group">
                    <h6>Contraseña</h6>
                    <input class="text-center" type="text" name="contrasena" value="<?php echo $row['contrasena']; ?>" class="from_control" required>
                </div>
                <div class="form_group">
                    <h6>Seleccione el rol</h6>
                    <select name="rol" id="grado" class="select text-center" required>
                        <option value="">-- Selecciona --</option>
                        <option value="1" <?= ($row['rol'] == 1) ? 'selected' : '' ?>>Administrador</option>
                        <option value="2" <?= ($row['rol'] == 2) ? 'selected' : '' ?>>Docente</option>
                        <option value="3" <?= ($row['rol'] == 3) ? 'selected' : '' ?>>Estudiante</option>
                    </select>
                </div>
                <br>
                <button class="btn btn-primary" type="submit" name="actualizar" value="actualizar">Actualizar</button>
            </form>
        </div>
    </div>
<?php include("../../include/footer.php");
} else {
    session_destroy();
    header("location:../index.php");
}
