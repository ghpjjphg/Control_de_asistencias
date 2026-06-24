<?php
include("../../data_base.php");
if (isset($_REQUEST['registrar_grado'])) {
    $identificacion = $_REQUEST['identificacion'];
    $nombre = $_REQUEST['nombre'];
    $correo = $_REQUEST['correo'];
    // para verificar si hay un usuario
    $query_duplicado = $conexion->query("SELECT docentes.Id_Docente FROM `docentes` WHERE Id_Docente=$identificacion");
    $row = mysqli_fetch_array($query_duplicado);
    // condicional por si la contulsa viene vacia
    if (empty($row)) {
        $consulta = $conexion->query ("INSERT INTO `docentes`(`Nombre`, `Id_Docente`, `Correo`) VALUES ('$nombre','$identificacion','$correo')");
        if ($consulta = true) { ?>
            <!-- Aletar para registro satisfactorio -->
            <script>
                window.onload = function irADestino() {
                    localStorage.setItem("mostrarAlerta", "true");
                    window.location.href = "index.php";
                }
            </script>
        <?php } else {
            echo "Sucedio un error";
        }
    } else { ?>

        <!-- Aletar para usuario repetido -->
        <script>
            window.onload = function() {
                alert("Este docente esta repetido");
                window.location.href = "index.php";
            };
        </script>
    <?php }
}
// actualizar
if (isset($_REQUEST['actualizar'])) {
    $identificacion = $_REQUEST['identificacion'];
    $nombre = $_REQUEST['nombre'];
    $correo = $_REQUEST['correo'];
    $Actualizar = $conexion->query("UPDATE `docentes` SET `Nombre`='$nombre',`Id_Docente`='$identificacion',`Correo`='$correo' WHERE docentes.Id_Docente=$identificacion");
    if ($Actualizar = true) { ?>
        <script>
            window.onload = function() {
                alert("Docente actualizado satisfactoriamente");
                window.location.href = "index.php";
            };
        </script>
    <?php } else { ?>
        <script>
            window.onload = function() {
                alert("A sucedido un error");
                window.location.href = "indexad.php";
            };
        </script>
    <?php }
}
// eliminar
if (isset($_REQUEST['eliminar'])) {
    $identificacion = $_REQUEST['eliminar'];
    $eliminar = $conexion->query("DELETE FROM `docentes` WHERE docentes.Id_Docente=$identificacion");
    if ($eliminar = true) { ?>
        <script>
            window.onload = function() {
                alert("Docente elimnado satisfactoriamente");
                window.location.href = "index.php";
            };
        </script>
    <?php } else { ?>
        <script>
            window.onload = function() {
                alert("A sucedido un error");
                window.location.href = "index.php";
            };
        </script>
<?php }
}