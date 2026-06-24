<?php
include("../../data_base.php");
if (isset($_REQUEST['registrar_usurio'])) {
    $identificacion = $_REQUEST['identificacion'];
    $nombre = $_REQUEST['nombre'];
    $apellidos = $_REQUEST['apellidos'];
    $contrasena = $_REQUEST['contrasena'];
    $rol = $_REQUEST['rol'];
    // para verificar si hay un usuario
    $query_duplicado = $conexion->query("SELECT * FROM `administrador` WHERE administrador.documento=$identificacion");
    $row = mysqli_fetch_array($query_duplicado);
    // condicional por si la contulsa viene vacia
    if (empty($row)) {

        $consulta = $conexion->query ("INSERT INTO `administrador`(`documento`, `nombres`, `apellidos`, `contrasena`, `rol`) VALUES ('$identificacion','$nombre','$apellidos','$contrasena','$rol')");
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
                alert("Este usuario esta repetido");
                window.location.href = "index.php";
            };
        </script>
    <?php }
}
// actualizar
if (isset($_REQUEST['actualizar'])) {
     $identificacion = $_REQUEST['identificacion'];
    $nombre = $_REQUEST['nombre'];
    $apellidos = $_REQUEST['apellidos'];
    $contrasena = $_REQUEST['contrasena'];
    $rol = $_REQUEST['rol'];
    $Actualizar = $conexion->query("UPDATE `administrador` SET `documento`='$identificacion',`nombres`='$nombre',`apellidos`='$apellidos',`contrasena`='$contrasena',`rol`='$rol' WHERE administrador.documento=$identificacion");
    if ($Actualizar = true) { ?>
        <script>
            window.onload = function() {
                alert("Usuario actualizado satisfactoriamente");
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
// eliminar
if (isset($_REQUEST['eliminar'])) {
    $identificacion = $_REQUEST['eliminar'];
    $eliminar = $conexion->query("DELETE FROM `administrador` WHERE administrador.documento=$identificacion");
    if ($eliminar = true) { ?>
        <script>
            window.onload = function() {
                alert("Usuario elimnado satisfactoriamente");
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
