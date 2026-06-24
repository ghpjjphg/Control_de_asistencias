<?php
include("../data_base.php");

if (isset($_POST['grado_id'])) {
   
    $id_docente = $_SESSION['documento']; // el docente logueado
    $grado_id = $_POST['grado_id'];

    $materiasQuery = "SELECT m.Id_Materia, m.Nombre
        FROM materias m
        INNER JOIN grado_materia gm ON m.Id_Materia = gm.Id_Materia
        INNER JOIN docente_grado_materia dgm ON gm.id_gr_mt = dgm.Id_Grado_Materia
        WHERE gm.Id_Grado = $grado_id
          AND dgm.Id_Docente = $id_docente
        ORDER BY m.Nombre ASC
    ";

    $materias = $conexion->query($materiasQuery);

    if ($materias->num_rows > 0) {
        echo '<option value="">-- Selecciona una materia --</option>';
        while ($m = $materias->fetch_assoc()) {
            echo '<option value="' . $m['Id_Materia'] . '">' . htmlspecialchars($m['Nombre']) . '</option>';
        }
    } else {
        echo '<option value="">No hay materias asignadas</option>';
    }
    exit;
}
?>