<?php
include("../data_base.php");

$idGrado = $_GET['id_grado'] ?? null;
$idMateria = $_GET['id_materia'] ?? null;
$fecha = $_GET['fecha'] ?? null;
$idDocente = $_SESSION['documento'] ?? null;

if (!$idGrado || !$idMateria || !$fecha || !$idDocente) {
    echo json_encode([]);
    exit;
}

// 🔹 Obtener estudiantes del grado (ordenados alfabéticamente)
$sqlEstudiantes = "
    SELECT 
        e.Id_Estudiante, 
        CONCAT(e.Nombre, ' ', e.Apellidos) AS NombreCompleto
    FROM estudiantes e
    WHERE e.Id_Grado = ?
    ORDER BY e.Nombre ASC, e.Apellidos ASC;
";
$stmt = $conexion->prepare($sqlEstudiantes);
$stmt->bind_param("i", $idGrado);
$stmt->execute();
$resultEstudiantes = $stmt->get_result();

$estudiantes = [];
while ($row = $resultEstudiantes->fetch_assoc()) {
    $estudiantes[$row['Id_Estudiante']] = [
        "Id_Estudiante" => $row['Id_Estudiante'],
        "NombreCompleto" => $row['NombreCompleto'],
        "Asistio" => 0,
        "Fecha_Asistencia" => null
    ];
}

// 🔹 Obtener asistencias reales
$sqlAsistencias = "
    SELECT Id_Estudiante, Fecha_Asistencia
    FROM asistencias
    WHERE Id_Grado = ? AND Id_Materia = ? AND Id_Docente = ? AND DATE(Fecha_Asistencia) = ?
";
$stmt2 = $conexion->prepare($sqlAsistencias);
$stmt2->bind_param("iiis", $idGrado, $idMateria, $idDocente, $fecha);
$stmt2->execute();
$resultAsistencias = $stmt2->get_result();

while ($a = $resultAsistencias->fetch_assoc()) {
    if (isset($estudiantes[$a['Id_Estudiante']])) {
        $estudiantes[$a['Id_Estudiante']]['Asistio'] = 1;
        $estudiantes[$a['Id_Estudiante']]['Fecha_Asistencia'] = $a['Fecha_Asistencia'];
    }
}

// 🔹 Convertir en arreglo ordenado (por nombre completo)
usort($estudiantes, function ($a, $b) {
    return strcmp($a['NombreCompleto'], $b['NombreCompleto']);
});

echo json_encode($estudiantes);
?>
