<?php
include("../data_base.php");

header('Content-Type: application/json');

if (!isset($_SESSION['documento'])) {
    echo json_encode([]);
    exit;
}

$idDocente = $_SESSION['documento'];
$idGrado = intval($_GET['id_grado'] ?? 0);

$sql = "
    SELECT DISTINCT m.Id_Materia, m.Nombre FROM docente_grado_materia dgm INNER JOIN grado_materia gm ON dgm.Id_Grado_Materia = gm.id_gr_mt INNER JOIN materias m ON gm.Id_Materia = m.Id_Materia WHERE dgm.Id_Docente = ? AND gm.Id_Grado = ?;
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $idDocente, $idGrado);
$stmt->execute();
$result = $stmt->get_result();

$materias = [];
while ($row = $result->fetch_assoc()) {
    $materias[] = $row;
}

echo json_encode($materias);
?>
