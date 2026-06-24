<?php

include("data_base.php");
include("narvar.php");
?>
<div class="d-flex justify-content-center align-items-center"
     style="height: 100vh; background: linear-gradient(to bottom right, #fff, #ffff);">

    <div class="card shadow-lg border-0"
        style="max-width: 400px; width: 90%; background-color: rgba(255, 255, 255, 0.95); border-radius: 15px;">

        <div class="card-body p-4 text-center">

            <!-- Logo -->
            <img src="img/logo.png" alt="Logo Enfermería Escolar" class="mb-3 rounded-circle shadow-sm" width="80" height="80">

            <!-- Título -->
            <h3 class="fw-bold text-dark mb-4">Inicio de sesión</h3>

            <!-- Formulario -->
            <form action="" method="POST">
                <div class="mb-3 text-start">
                    <label for="documento" class="form-label fw-semibold text-dark">Documento</label>
                    <input type="number" name="documento" id="documento"
                        class="form-control text-center border-dark shadow-sm" placeholder="Ingrese su documento" required>
                </div>
                <div class="mb-4 text-start">
                    <label for="contrasena" class=" form-label  fw-semibold text-dark">Contraseña</label>
                    <input type="password" name="contrasena" id="contrasena"
                        class="form-control text-center border-dark shadow-sm" placeholder="Ingrese su contraseña" required>
                </div>

                <!-- Botón -->
                <div class="d-grid">
                    <button type="submit" name="iniciar" class="btn btn-dark fw-bold text-light">
                        Iniciar Sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include("include/footer.php");

if (isset($_REQUEST['iniciar'])) {
    $documento = $_REQUEST['documento'];
    $contrasena = $_REQUEST['contrasena'];
    $query = "SELECT * FROM `administrador` WHERE administrador.documento='$documento' AND administrador.contrasena ='$contrasena'";
    $consulta = mysqli_query($conexion, $query);
    if (mysqli_num_rows($consulta) == 1) {
        $row = mysqli_fetch_array($consulta);
        $rol = $row['rol'];

        $_SESSION['rol'] = $rol;
        if ($rol == 1) {
            if (mysqli_num_rows($consulta) > 0) {
                $_SESSION['documento'] = $documento;
                $_SESSION['nombres'] = $row[1] . " " . $row[2];
                 header("location:admin/index.php");
            }
        } 
        if ($rol == 2) {
            if (mysqli_num_rows($consulta) > 0) {
                $_SESSION['documento'] = $documento;
                $_SESSION['nombres'] = $row[1] . " " . $row[2];
                header("location:docentes/index.php");
            }
        } 
        if ($rol==3)  {

            if (mysqli_num_rows($consulta) > 0) {
                $_SESSION['documento'] = $documento;
                $_SESSION['nombres'] = $row[1] . " " . $row[2];
                 header("location:estudiantes/index.php");
                echo $rol;
            }
        }
    } else {
?>
        <script>
            window.onload = function() {
                alert("Verifica tus credenciales");
                window.location.href = "index.php";
            };
        </script>
<?php
    }
}
