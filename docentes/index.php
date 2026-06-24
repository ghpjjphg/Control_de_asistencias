<?php include("../data_base.php");
include("../include/header.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 2) {
  include("narvar.php");
?>
  <div class="centrarpla d-flex justify-content-center align-items-center">
    <div class="container">
      <div class="container py-5">
        <h2 class="text-center mb-5">Panel de Administración</h2>

        <!-- Row centrada -->
        <div class="row justify-content-center g-4">

          <!-- Card 1: Administrar Usuarios -->
          <div class="col-md-4">
            <div class="card text-center shadow-lg h-100">
              <div class="card-body">
                <!-- Icono de usuarios -->
                <i class="bi bi-qr-code display-1 text-primary mb-3"></i>
                <h5 class="card-title">Generar para Qr las asistencias</h5>
                <p class="card-text">Gestiona los Qr de las asistencias.</p>
                <a href="generar_qr.php" class="btn btn-primary">Ir</a>
              </div>
            </div>
          </div>

          <!-- Card 2: Administrar Grados -->
          <div class="col-md-4">
            <div class="card text-center shadow-lg h-100">
              <div class="card-body">
                <!-- Icono de grados -->
                <i class="bi bi-clipboard-check display-1 text-success mb-3"></i>
                <h5 class="card-title">Administrar Asistencias</h5>
                <p class="card-text">Gestiona las asistecnias</p>
                <a href="ver_asistencias.php" class="btn btn-success">Ir</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include("../include/footer.php");
} else {
  session_destroy();
  header("location:../index.php");
}

?>