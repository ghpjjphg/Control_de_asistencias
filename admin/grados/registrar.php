<?php
include("../../data_base.php");
if (isset($_REQUEST['registrar_grado'])) {
    $identificacion = $_REQUEST['identificacion'];
    $nombre = $_REQUEST['nombre'];
    // para verificar si hay un usuario
    $query_duplicado = $conexion->query("SELECT grados.Id_Grado FROM `grados` WHERE grados.Id_Grado=$identificacion");
    $row = mysqli_fetch_array($query_duplicado);
    // condicional por si la contulsa viene vacia
    if (empty($row)) {

        $consulta = $conexion->query ("INSERT INTO `grados`(`Id_Grado`,`Nombre`) VALUES ('$identificacion','$nombre')");
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
    $Actualizar = $conexion->query("UPDATE `grados` SET `Id_Grado`='$identificacion',`Nombre`='$nombre' WHERE grados.Id_Grado=$identificacion");
    if ($Actualizar = true) { ?>
        <script>
            window.onload = function() {
                alert("Grado actualizado satisfactoriamente");
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
    $eliminar = $conexion->query("DELETE FROM `grados` WHERE grados.Id_Grado=$identificacion");
    if ($eliminar = true) { ?>
        <script>
            window.onload = function() {
                alert("Grado elimnado satisfactoriamente");
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