<?php
include("../../data_base.php");
if (isset($_POST['grado_id'])) {
    $grado_id = intval($_POST['grado_id']);
    $query = $conexion->query("SELECT * FROM estudiantes WHERE Id_Grado = $grado_id");

    if ($query->num_rows > 0) {
        while ($e = $query->fetch_assoc()) {
            echo "<tr>
                    <td>{$e['Id_Estudiante']}</td>
                    <td>{$e['Nombre']}</td>
                    <td>{$e['Apellidos']}</td>" ?>
            <td><a href="form_actualizar.php?actualizar=<?php echo $e['Id_Estudiante'] ?>" class="btn btn-light"><i class="bi bi-pen"></i> </a> <a href="registrar.php?eliminar=<?php echo $e['Id_Estudiante'] ?>" class="btn btn-danger" onclick=" return confirmacion('<?php echo $e['Nombre'] ?>','<?php echo $e['Id_Estudiante'] ?>')"><i class="bi bi-trash-fill"></i></a> <a href="visualizar_estudiante.php?vizualizar=<?php echo $e['Id_Estudiante'] ?>" class="btn btn-light"><i class="bi bi-eye"></i></td>
            </tr>
            <script>
                function confirmacion(nombre, identificacion) {
                    var confirmaction = confirm("Desea eliminar el estudiante " + nombre + " con identificacion " + identificacion + "?")
                    if (confirmaction == true) {
                        return true
                    } else {
                        return false
                    }
                }
            </script>
<?php
        }
    } else {
        echo "<tr><td colspan='4'>No hay estudiantes en este grado</td></tr>";
    }
}


?>