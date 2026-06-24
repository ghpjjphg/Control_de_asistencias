<?php include("../../include/header.php");
include("../../data_base.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 1) {
    if (isset($_REQUEST['vizualizar'])) {
        $identificacion = $_REQUEST['vizualizar'];
        $informacion = $conexion->query("SELECT * FROM `estudiantes` WHERE estudiantes.Id_Estudiante=$identificacion");
        $row = mysqli_fetch_array($informacion);
    }
    include("narvar.php");
?>
   <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card text-center shadow-lg h-100">
                <div class="card-body">
                    <!-- Icono del estudiante -->
                    <i class="bi bi-person-fill fs-2 text-primary mb-3"></i>
                    <a href="form_actualizar.php?actualizar=<?php echo $row['Id_Estudiante'] ?>" class="text-decoration-none text-dark">
                        <h5 class="card-title">
                            <?php echo $row['Nombre'], " ", $row['Apellidos']; ?>
                        </h5>
                    </a>

                    <!-- Datos del estudiante -->
                    <div class="text-center mt-3">
                        <h6>Identificación:</h6>
                        <p><?php echo $row['Id_Estudiante']; ?></p>

                        <!-- Botón de contacto centrado -->
                        <div class="d-flex justify-content-center mt-4">
                            <a href="tel:<?php echo $row['cont_emer']; ?>"
                                class="btn btn-success d-flex flex-column justify-content-center align-items-center p-3 shadow-sm text-decoration-none"
                                style="width: 250px;">
                                <i class="bi bi-person-rolodex fs-3 mb-1"></i>
                                <span class="fw-bold">Acudiente</span>
                                <small class="text-center">
                                    <?php echo $row['nom_cont_emer']; ?><br>
                                    📞 <?php echo $row['cont_emer']; ?>
                                </small>
                            </a>
                        </div>
                    </div> <!-- cierre text-center -->
                </div> <!-- cierre card-body -->
            </div> <!-- cierre card -->
        </div>
    </div>
</div>



<?php include("../../include/footer.php");
} else {
    session_destroy();
    header("location:../index.php");
}

?>