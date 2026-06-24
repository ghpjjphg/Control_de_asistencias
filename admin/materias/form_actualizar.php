<?php include("../../include/header.php");

include("../../data_base.php");
include("narvar.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 1) {
    if (isset($_REQUEST['actualizar'])) {
        $documento = $_REQUEST['actualizar'];
        $Actualizar = $conexion->query("SELECT * FROM `materias` WHERE materias.Id_Materia=$documento");
        $row = mysqli_fetch_array($Actualizar);
    }
?>

    <div class="centrarpla d-flex justify-content-center align-items-center">
        <div class="container text-center card card-body">
            <h5>Información del usuario</h5>
            <form action="registrar.php" method="REQUEST">
                <div class="form_group">
                    <h6>Identificación de la materia
                    </h6>
                    <input class="text-center" type="number" name="identificacion" value="<?php echo $row['Id_Materia']; ?>" class="from_control" readonly>
                </div>
                <div class="form_group">
                    <h6>Nombre de la materia</h6>
                    <input class="text-center" type="text" name="nombre" value="<?php echo $row['Nombre']; ?>" class="from_control" required>
                </div>
                <br>
                <button class="btn btn-primary" type="submit" name="actualizar" value="actualizar">Actualizar</button>
            </form>
        </div>
    </div>

<?php
include("../../include/footer.php");
} else {
    session_destroy();
    header("location:../index.php");
}
