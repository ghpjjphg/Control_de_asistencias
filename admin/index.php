<?php include("../include/header.php");
include("narvar.php");
include("../data_base.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 1) {
  $grados = $conexion->query("SELECT * FROM grados")
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
                <i class="bi bi-people-fill display-1 text-primary mb-3"></i>
                <h5 class="card-title">Administrar Usuarios</h5>
                <p class="card-text">Gestiona los usuarios del sistema.</p>
                <a href="usuarios/index.php" class="btn btn-primary">Ir</a>
              </div>
            </div>
          </div>

          <!-- Card 2: Administrar Grados -->
          <div class="col-md-4">
            <div class="card text-center shadow-lg h-100">
              <div class="card-body">
                <!-- Icono de grados -->
                <i class="bi bi-journal-bookmark-fill display-1 text-success mb-3"></i>
                <h5 class="card-title">Administrar Grados</h5>
                <p class="card-text">Gestiona los grados académicos.</p>
                <a href="grados/index.php" class="btn btn-success">Ir</a>
              </div>
            </div>
          </div>

          <!-- Card 3: Administrar Estudiantes -->
          <div class="col-md-4">
            <div class="card text-center shadow-lg h-100">
              <div class="card-body">
                <!-- Icono de estudiantes -->
                <i class="bi bi-person-lines-fill display-1 text-warning mb-3"></i>
                <h5 class="card-title">Administrar Estudiantes</h5>
                <p class="card-text">Gestiona la información de los estudiantes.</p>
                <a href="estudiantes/index.php" class="btn btn-warning text-white">Ir</a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-center shadow-lg h-100">
              <div class="card-body">
                <!-- Icono de grados -->
                <i class="bi bi-person-workspace display-1 text-success mb-3"></i>
                <h5 class="card-title">Administrar Docentes</h5>
                <p class="card-text">Gestiona los docentes académicos.</p>
                <a href="docentes/index.php" class="btn btn-success">Ir</a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-center shadow-lg h-100">
              <div class="card-body">
                <!-- Icono de grados -->
                <i class="bi bi-book display-1 text-primary mb-3"></i>
                <h5 class="card-title">Administrar Materias</h5>
                <p class="card-text">Gestiona las materia académicas.</p>
                <a href="materias/index.php" class="btn btn-primary">Ir</a>
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