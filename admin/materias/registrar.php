<?php
include("../../data_base.php");
if (isset($_REQUEST['registrar_grado'])) {
    $identificacion = $_REQUEST['identificacion'];
    $nombre = $_REQUEST['nombre'];
    // para verificar si hay un usuario
    $query_duplicado = $conexion->query("SELECT materias.Id_Materia FROM `materias` WHERE materias.Id_Materia=$identificacion");
    $row = mysqli_fetch_array($query_duplicado);
    // condicional por si la contulsa viene vacia
    if (empty($row)) {

        $consulta = $conexion->query("INSERT INTO `materias`(`Nombre`, `Id_Materia`) VALUES ('$nombre','$identificacion')");
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
                alert("Esta materia esta repetida");
                window.location.href = "index.php";
            };
        </script>
    <?php }
}
// actualizar
if (isset($_REQUEST['actualizar'])) {
    $identificacion = $_REQUEST['identificacion'];
    $nombre = $_REQUEST['nombre'];
    $Actualizar = $conexion->query("UPDATE `materias` SET `Nombre`='$nombre',`Id_Materia`='$identificacion' WHERE materias.Id_Materia=$identificacion");
    if ($Actualizar = true) { ?>
        <script>
            window.onload = function() {
                alert("Materia actualizada satisfactoriamente");
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
    $eliminar = $conexion->query("DELETE FROM `materias` WHERE materias.Id_Materia=$identificacion");
    if ($eliminar = true) { ?>
        <script>
            window.onload = function() {
                alert("Materias eliminada satisfactoriamente");
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
if (isset($_POST['grado_ma'])) {
    $grado_ma = intval($_POST['grado_ma']);
    $query = $conexion->query("SELECT materias.Nombre, materias.Id_Materia FROM materias 
    INNER JOIN grado_materia ON materias.Id_Materia = grado_materia.Id_Materia 
    INNER JOIN grados ON grados.Id_Grado = grado_materia.Id_Grado 
    WHERE grados.Id_Grado = $grado_ma ORDER BY materias.Id_Materia ASC;");

    if ($query->num_rows > 0) {
        while ($e = $query->fetch_assoc()) {
            echo "
                <tr>
                    <td>{$e['Id_Materia']}</td>
                    <td>{$e['Nombre']}</td>
                    <td>
                        <button class='btn btn-danger btnEliminarMateria' 
                            data-id='{$e['Id_Materia']}' 
                            data-grado='{$grado_ma}' 
                            data-nombre='{$e['Nombre']}'>
                            <i class='bi bi-trash-fill'></i>
                        </button>
                    </td>
                </tr>
            ";
        }
    } else {
        echo "<tr><td colspan='3'>No hay materias asignadas a este grado</td></tr>";
    }
}
if (isset($_POST['obtener_materias_disponibles'])) {
    $grado_id = intval($_POST['obtener_materias_disponibles']);

    $query = $conexion->query("SELECT Id_Materia, Nombre FROM materias WHERE Id_Materia NOT IN (SELECT Id_Materia FROM grado_materia WHERE Id_Grado = $grado_id) ORDER BY Id_Materia ASC");

    if ($query->num_rows > 0) {
        echo '<form id="formMateriasDisponibles">';
        while ($m = $query->fetch_assoc()) {
            echo "
              <div class='form-check'>
                <input class='form-check-input' type='checkbox' name='materias[]' value='{$m['Id_Materia']}' id='mat_{$m['Id_Materia']}'>
                <label class='form-check-label' for='mat_{$m['Id_Materia']}'>{$m['Nombre']}</label>
              </div>
            ";
        }
        echo '</form>';
    } else {
        echo "<p class='text-center text-muted'>Todas las materias ya están asignadas a este grado.</p>";
    }
}
// Asignar materias seleccionadas
if (isset($_POST['asignar_materias'])) {
    $grado_id = intval($_POST['grado_id']);
    $materias = $_POST['materias'];

    foreach ($materias as $id_materia) {
        $conexion->query("INSERT INTO grado_materia (Id_Grado, Id_Materia) VALUES ($grado_id, $id_materia)");
    }
    echo "Materias asignadas correctamente.";
}
//  Eliminar materia asociada a un grado
if (isset($_POST['eliminar_materia'])) {
    $id_materia = intval($_POST['id_materia']);
    $id_grado = intval($_POST['id_grado']);

    $conexion->query("DELETE FROM grado_materia WHERE Id_Grado = $id_grado AND Id_Materia = $id_materia");

    echo ($conexion->affected_rows > 0) ? "ok" : "error";
}
?>
<?php if (isset($_POST['grado_ma_d'])) {
    $gradoId = $_POST['grado_ma_d'];

    $query = "SELECT gm.Id_Gr_Mt, m.Nombre AS Materia, d.Nombre AS Docente, dgm.Id_DGM FROM grado_materia gm INNER JOIN materias m ON gm.Id_Materia = m.Id_Materia LEFT JOIN docente_grado_materia dgm ON gm.Id_Gr_Mt = dgm.Id_Grado_Materia LEFT JOIN docentes d ON dgm.Id_Docente = d.Id_Docente WHERE gm.Id_Grado = $gradoId ORDER BY m.Nombre ASC;
    ";

    $resultado = $conexion->query($query);

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Id_Gr_Mt'] . "</td>";
            echo "<td>" . htmlspecialchars($row['Materia']) . "</td>";

            if (!empty($row['Docente'])) {
                // ✅ Mostrar docente y botón de eliminar (solo relación)
                echo "<td class='text-success fw-semibold'>" . htmlspecialchars($row['Docente']) . "</td>";
                echo "<td>
                        <button class='btn btn-danger btn-sm btnEliminarDocenteMateria' 
                            data-id='" . $row['Id_Gr_Mt'] . "' 
                            data-docente='" . htmlspecialchars($row['Docente']) . "' 
                            data-materia='" . htmlspecialchars($row['Materia']) . "'>
                            <i class='bi bi-trash'></i>
                        </button>
                      </td>";
            } else {
                // ❌ No mostrar botón si no tiene docente
                echo "<td class='text-muted fst-italic'>Sin docente</td>";
                echo "<td>-</td>";
            }

            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No hay materias asignadas a este grado.</td></tr>";
    }
} ?>
<?php if (isset($_POST['obtener_materias_disponibles_d'])) {
    $gradoId = intval($_POST['obtener_materias_disponibles_d']);

    // 🔹 Consulta de materias del grado SIN docente asignado
    $materiasQuery = "SELECT gm.id_gr_mt, m.Nombre
        FROM grado_materia gm
        INNER JOIN materias m ON gm.Id_Materia = m.Id_Materia
        LEFT JOIN docente_grado_materia dgm ON gm.id_gr_mt = dgm.Id_Grado_Materia
        WHERE gm.Id_Grado = $gradoId
        AND dgm.Id_Grado_Materia IS NULL
        ORDER BY m.Nombre ASC
    ";
    $materias = $conexion->query($materiasQuery);

    // 🔹 Consulta de docentes disponibles (todos los docentes)
    $docentesQuery = "SELECT Id_Docente, Nombre FROM docentes ORDER BY Nombre ASC";
    $docentes = $conexion->query($docentesQuery);

    if ($materias && $materias->num_rows > 0) {
        echo '<form id="formMateriasDocentes">';
        while ($m = $materias->fetch_assoc()) {
            echo '<div class="mb-3 border p-3 rounded">';

            // Nombre de la materia
            echo '<label class="fw-bold d-block mb-2">' . htmlspecialchars($m['Nombre']) . '</label>';

            // Select de docentes
            echo '<select name="docente[' . $m['id_gr_mt'] . ']" class="form-select">';
            echo '<option value="">-- Selecciona un docente --</option>';

            mysqli_data_seek($docentes, 0); // reiniciar puntero de docentes
            while ($d = $docentes->fetch_assoc()) {
                echo '<option value="' . $d['Id_Docente'] . '">' . htmlspecialchars($d['Nombre']) . '</option>';
            }

            echo '</select>';
            echo '</div>';
        }
        echo '</form>';
    } else {
        echo '<p class="text-center text-muted">No hay materias disponibles para este grado.</p>';
    }
} ?>
<?php
if (isset($_POST['asignar_materias_docente'])) {
    $grado_id = intval($_POST['grado_id']);
    $docentes = $_POST['docentes'];

    if (!empty($docentes)) {
        $errores = 0;
        foreach ($docentes as $id_gr_mt => $id_docente) {
            $id_gr_mt = intval($id_gr_mt);
            $id_docente = intval($id_docente);

            // Evitar duplicados: solo insertar si no existe
            $check = $conexion->query("SELECT 1 FROM docente_grado_materia WHERE Id_Grado_Materia = $id_gr_mt");
            if ($check->num_rows == 0) {
                $insert = $conexion->query("INSERT INTO docente_grado_materia (Id_Docente, Id_Grado_Materia)
                    VALUES ($id_docente, $id_gr_mt)
                ");
                if (!$insert) {
                    $errores++;
                }
            }
        }

        if ($errores == 0) {
            echo "✅ Asignaciones guardadas correctamente.";
        } else {
            echo "⚠️ Algunas asignaciones no se pudieron guardar.";
        }
    } else {
        echo "❌ No se recibieron datos de docentes.";
    }
    exit;
}
?>
<?php if (isset($_POST['eliminar_docente_materia'])) {

    $idGradoMateria = $_POST['id_grado_materia'];

    $delete = $conexion->query("DELETE FROM docente_grado_materia WHERE Id_Grado_Materia = $idGradoMateria");

    if ($delete) {
        echo "ok";
    } else {
        echo "error";
    }
} ?>