<?php
include("../../data_base.php");
if (isset($_REQUEST['registrar_usuario'])) {

    $identificacion = $_REQUEST['identificacion'];
    $nombre = $_REQUEST['nombre'];
    $apellido = $_REQUEST['apellidos'];
    $grado = $_REQUEST['grado'];
    $nombre_acu = $_REQUEST['nombre_acu'];
    $contacto = $_REQUEST['contacto'];
    $correo = $_REQUEST['correo'];

    // para verificar si hay un usuario
    $query_duplicado = "SELECT estudiantes.Id_Estudiante FROM `estudiantes` WHERE estudiantes.Id_Estudiante=$identificacion";
    $resltado_duplicado = mysqli_query($conexion, $query_duplicado);
    $row = mysqli_fetch_array($resltado_duplicado);
    // condicional por si la contulsa viene vacia
    if (empty($row)) {
        $consulta = "INSERT INTO `estudiantes`(`Nombre`, `Apellidos`, `Id_Estudiante`, `Correo`, `Id_Grado`, `cont_emer`, `nom_cont_emer`) VALUES ('$nombre','$apellido','$identificacion','$correo','$grado','$contacto','$nombre_acu')";
        $resultado_consulta = mysqli_query($conexion, $consulta);
        if ($resultado_consulta = true) { ?>
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
    $apellido = $_REQUEST['apellidos'];
    $grado = $_REQUEST['grado'];
    $nombre_acu = $_REQUEST['nombre_acu'];
    $contacto = $_REQUEST['contacto'];
    $correo = $_REQUEST['correo'];

    $Actualizar = $conexion->query("UPDATE `estudiantes` SET `Nombre`='$nombre',`Apellidos`='$apellido',`Id_Estudiante`='$identificacion',`Correo`='$correo',`Id_Grado`='$grado',`cont_emer`='$contacto',`nom_cont_emer`='$nombre_acu' WHERE estudiantes.Id_Estudiante=$identificacion");
    if ($Actualizar = true) { ?>
        <script>
            window.onload = function() {
                alert("Estuadiante actualizado satisfactoriamente");
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
    $eliminar = $conexion->query("DELETE FROM `estudiantes` WHERE estudiantes.Id_Estudiante=$identificacion");
    if ($eliminar = true) { ?>
        <script>
            window.onload = function() {
                alert("Estuadiante elimnado satisfactoriamente");
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