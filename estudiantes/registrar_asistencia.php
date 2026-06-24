<?php
header('Content-Type: application/json');
include("../data_base.php"); // Ajusta la ruta si es necesario

$response = ["status" => "error", "message" => "Error desconocido."];

// Iniciar sesión si no está iniciada
if (!isset($_SESSION)) session_start();

// Depuración: datos recibidos
$debug = [
    "POST" => $_POST,
    "SESSION" => $_SESSION
];

// Verificar sesión del estudiante
if (!isset($_SESSION['documento'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No hay sesión activa del estudiante.",
        "debug" => $debug
    ]);
    exit;
}

// Verificar datos del QR
if (!isset($_POST['ID_Docente'], $_POST['ID_Grado'], $_POST['ID_Materia'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Datos incompletos. Verifica el QR.",
        "debug" => $debug
    ]);
    exit;
}

$idDocente = intval($_POST['ID_Docente']);
$idGrado = intval($_POST['ID_Grado']);
$idMateria = intval($_POST['ID_Materia']);
$idEstudiante = intval($_SESSION['documento']);

// 1️⃣ Verificar conexión
if (!isset($conexion)) {
    echo json_encode([
        "status" => "error",
        "message" => "No hay conexión con la base de datos.",
        "debug" => $debug
    ]);
    exit;
}

// 2️⃣ Validar que el estudiante pertenezca al grado
$sqlVerificarGrado = "
    SELECT 1
    FROM estudiantes e
    INNER JOIN grados g ON e.Id_Grado = g.Id_Grado
    WHERE e.Id_Estudiante = ? AND g.Id_Grado = ?
";
$stmtGrado = $conexion->prepare($sqlVerificarGrado);
if (!$stmtGrado) {
    echo json_encode([
        "status" => "error",
        "message" => "Error preparando validación de grado: " . $conexion->error,
        "debug" => $debug
    ]);
    exit;
}

$stmtGrado->bind_param("ii", $idEstudiante, $idGrado);
$stmtGrado->execute();
$resultGrado = $stmtGrado->get_result();

if ($resultGrado->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "El estudiante no pertenece al grado escaneado.",
        "debug" => $debug
    ]);
    exit;
}
$stmtGrado->close();
// 3️⃣ Validar que docente, grado y materia estén relacionados
$sqlVerificarRelacion = "
    SELECT 1 FROM docente_grado_materia dgm INNER JOIN grado_materia gm ON dgm.Id_Grado_Materia = gm.id_gr_mt WHERE dgm.Id_Docente = ? AND gm.Id_Grado = ? AND gm.Id_Materia = ?;
";
$stmtRelacion = $conexion->prepare($sqlVerificarRelacion);
if (!$stmtRelacion) {
    echo json_encode([
        "status" => "error",
        "message" => "Error preparando validación de relación: " . $conexion->error
    ]);
    exit;
}


// 3️⃣ Validar si ya registró asistencia hoy
$sqlVerificar = "
    SELECT 1 
    FROM asistencias
    WHERE Id_Docente = ? 
    AND Id_Grado = ? 
    AND Id_Materia = ? 
    AND Id_Estudiante = ? 
    AND DATE(Fecha_Asistencia) = CURDATE()
";

$stmt = $conexion->prepare($sqlVerificar);
if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Error preparando consulta de verificación: " . $conexion->error,
        "debug" => $debug
    ]);
    exit;
}

$stmt->bind_param("iiii", $idDocente, $idGrado, $idMateria, $idEstudiante);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado && $resultado->num_rows > 0) {
    echo json_encode([
        "status" => "warning",
        "message" => "⚠️ Ya registraste tu asistencia hoy para esta materia.",
        "debug" => $debug
    ]);
    exit;
}
$stmt->close();

// 4️⃣ Registrar asistencia
$sqlInsert = "
    INSERT INTO asistencias (Id_Docente, Id_Grado, Id_Materia, Id_Estudiante, Fecha_Asistencia)
    VALUES (?, ?, ?, ?, NOW())
";

$stmtInsert = $conexion->prepare($sqlInsert);
if (!$stmtInsert) {
    echo json_encode([
        "status" => "error",
        "message" => "Error preparando inserción: " . $conexion->error,
        "debug" => $debug
    ]);
    exit;
}

$stmtInsert->bind_param("iiii", $idDocente, $idGrado, $idMateria, $idEstudiante);

if ($stmtInsert->execute()) {
    $response = [
        "status" => "success",
        "message" => "✅ Asistencia registrada correctamente el " . date("d/m/Y H:i:s"),
        "debug" => $debug
    ];
} else {
    $response = [
        "status" => "error",
        "message" => "Error al insertar asistencia: " . $stmtInsert->error,
        "debug" => $debug
    ];
}

$stmtInsert->close();
echo json_encode($response, JSON_PRETTY_PRINT);
