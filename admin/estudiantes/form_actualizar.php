<?php include("../../include/header.php");

include("../../data_base.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 1) {
    if (isset($_REQUEST['actualizar'])) {
        $documento = $_REQUEST['actualizar'];
        $Actualizar = $conexion->query("SELECT * FROM `estudiantes` WHERE estudiantes.Id_Estudiante=$documento");
        $row = mysqli_fetch_array($Actualizar);
    }
    $grados = $conexion->query("SELECT * FROM grados");
    include("narvar.php");
?>
    <div class="centrarpla d-flex justify-content-center align-items-center">
        <div class="container text-center card card-body">
            <h5>Información del usuario</h5>
            <form action="registrar.php" method="REQUEST">
                <div class="form_group">
                    <h6>Identificación del usuario
                    </h6>
                    <input class="text-center" type="number" name="identificacion" value="<?php echo $row['Id_Estudiante']; ?>" class="from_control" readonly>
                </div>
                <div class="form_group">
                    <h6>Nombre del usuario</h6>
                    <input class="text-center" type="text" name="nombre" value="<?php echo $row['Nombre']; ?>" class="from_control" required>
                </div>
                <div class="form_group">
                    <h6>Apellidos del usuario</h6>
                    <input class="text-center" type="text" name="apellidos" value="<?php echo $row['Apellidos']; ?>" class="from_control" required>
                </div>
                <div class="form_group">
                    <h6>Apellidos del usuario</h6>
                    <input class="text-center" type="email" name="correo" value="<?php echo $row['Correo']; ?>" class="from_control" required>
                </div>
                <div class="form_group">
                    <h6>Seleccione el grado</h6>
                    <select name="grado" id="grado" class="select text-center">
                        <option value="">-- Selecciona --</option>
                        <?php while ($g = $grados->fetch_assoc()) { ?>
                            <option value="<?= $g['Id_Grado'] ?>"
                                <?= ($g['Id_Grado'] == $row['Id_Grado']) ? 'selected' : '' ?>>
                                <?= $g['Nombre'] ?>
                            </option>
                        <?php }; ?>
                    </select>
                </div>
                <div class="form_group">
                    <h6>Nombre del acudiente
                    </h6>
                    <input class="text-center" type="text" name="nombre_acu" value="<?php echo $row['nom_cont_emer']; ?>" class="from_control">
                </div>
                <div class="form_group">
                    <h6>Contacto del acudiente</h6>
                    <input class="text-center" type="number" name="contacto" value="<?php echo $row['cont_emer']; ?>" class="from_control" required>
                </div><br>
                <button class="btn btn-primary" type="submit" name="actualizar" value="actualizar">Actualizar</button>
            </form>
        </div>
    </div>
<?php include("../../include/footer.php");
} else {
    session_destroy();
    header("location:../index.php");
}
