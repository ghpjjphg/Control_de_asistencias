<?php include("../data_base.php");
include("../include/header.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 2) {
    include("narvar.php");


    // Suponemos que el docente inició sesión y su ID está en $_SESSION['Id_Docente']
    if (!isset($_SESSION['documento'])) {
        echo "<script>alert('No ha iniciado sesión como docente.'); window.location.href='../login.php';</script>";
        exit;
    }

    $idDocente = $_SESSION['documento'];

    // Traer los grados del docente
    $sqlGrados = "
    SELECT DISTINCT gm.Id_Grado, g.Nombre
    FROM docente_grado_materia dgm
    INNER JOIN grado_materia gm ON dgm.Id_Grado_Materia = gm.id_gr_mt
    INNER JOIN grados g ON gm.Id_Grado = g.Id_Grado
    WHERE dgm.Id_Docente = ?
";
    $stmt = $conexion->prepare($sqlGrados);
    $stmt->bind_param("i", $idDocente);
    $stmt->execute();
    $resultGrados = $stmt->get_result();
?>
    <div class="centrarpla d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="container py-5">
                <h2 class="text-center mb-5">Panel de Administración</h2>
                <div class="row justify-content-center g-4">
                    <div class="col-md-6">
                        <div class="card text-center shadow-lg h-100">
                            <div class="card-body">
                                <i class="bi bi-clipboard-check display-1 text-success mb-3"></i>
                                <h5 class="card-title mb-3">Selecciona los datos</h5>
                                <!-- Select de grados -->
                                <label for="grado" class="form-label">Selecciona un grado:</label>
                                <select id="grado" class="form-select mb-3">
                                    <option value="">-- Selecciona --</option>
                                    <?php while ($g = $resultGrados->fetch_assoc()) { ?>
                                        <option value="<?= $g['Id_Grado'] ?>"><?= htmlspecialchars($g['Nombre']) ?></option>
                                    <?php } ?>
                                </select>

                                <!-- Select de materias (oculto al inicio) -->
                                <div id="materia-container" class="mt-3" style="display: none;">
                                    <label for="materia" class="form-label">Selecciona una materia:</label>
                                    <select id="materia" class="form-select mb-3">
                                        <option value="">-- Selecciona un grado primero --</option>
                                    </select>
                                </div>

                                <!-- Input de fecha (oculto al inicio) -->
                                <div id="fecha-container" class="mt-3" style="display: none;">
                                    <label for="fecha" class="form-label">Selecciona la fecha:</label>
                                    <input type="date" id="fecha" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLA DE ASISTENCIAS -->
            <div class="row justify-content-center mt-5">
                <div class="col-md-10">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title text-center mb-3">Listado de asistencias</h5>
                            <div id="tablaAsistencias" class="table-responsive text-center">
                                <p class="text-muted">Seleccione un grado, materia y fecha para ver las asistencias.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <script>
                // --- ELEMENTOS ---
                const gradoSelect = document.getElementById('grado');
                const materiaSelect = document.getElementById('materia');
                const fechaInput = document.getElementById('fecha');
                const materiaContainer = document.getElementById('materia-container');
                const fechaContainer = document.getElementById('fecha-container');
                const tabla = document.getElementById('tablaAsistencias');

                // --- EVENTO: SELECCIONAR GRADO ---
                gradoSelect.addEventListener('change', async function() {
                    const idGrado = this.value;
                    materiaContainer.style.display = 'none';
                    fechaContainer.style.display = 'none';
                    tabla.innerHTML = "<p class='text-muted'>Seleccione un grado, materia y fecha para ver las asistencias.</p>";

                    if (!idGrado) return;

                    materiaSelect.innerHTML = '<option value="">-- Cargando materias... --</option>';
                    try {
                        const res = await fetch('obtener_materias_a.php?id_grado=' + idGrado);
                        const materias = await res.json();

                        if (materias.length > 0) {
                            materiaSelect.innerHTML = '<option value="">-- Selecciona --</option>';
                            materias.forEach(m => {
                                materiaSelect.innerHTML += `<option value="${m.Id_Materia}">${m.Nombre}</option>`;
                            });
                            materiaContainer.style.display = 'block';
                        } else {
                            materiaSelect.innerHTML = '<option value="">No hay materias asignadas</option>';
                            materiaContainer.style.display = 'block';
                        }
                    } catch (e) {
                        Swal.fire("Error", "No se pudieron cargar las materias.", "error");
                    }
                });

                // --- EVENTO: SELECCIONAR MATERIA ---
                materiaSelect.addEventListener('change', function() {
                    fechaContainer.style.display = this.value ? 'block' : 'none';
                    tabla.innerHTML = "<p class='text-muted'>Seleccione una fecha para ver las asistencias.</p>";
                });

                // --- EVENTO: SELECCIONAR FECHA ---
                fechaInput.addEventListener('change', async function() {
                    const idGrado = gradoSelect.value;
                    const idMateria = materiaSelect.value;
                    const fecha = this.value;

                    if (!idGrado || !idMateria || !fecha) return;

                    tabla.innerHTML = "<p class='text-muted'>Cargando asistencias...</p>";

                    try {
                        const res = await fetch(`obtener_asistencias.php?id_grado=${idGrado}&id_materia=${idMateria}&fecha=${fecha}`);
                        const data = await res.json();

                        if (data.length === 0) {
                            tabla.innerHTML = "<p class='text-danger'>No hay estudiantes registrados en ese grado.</p>";
                            return;
                        }

                        let html = `
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Estudiante</th>
                            <th>Documento</th>
                            <th>Asistencia</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

                        data.forEach((row, i) => {
                            const estado = row.Asistio == 1 ?
                                `<span class='badge bg-success'>Asistió</span>` :
                                `<span class='badge bg-danger'>No asistió</span>`;

                            html += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${row.NombreCompleto}</td>
                        <td>${row.Id_Estudiante}</td>
                        <td>${estado}</td>
                        <td>${row.Fecha_Asistencia ?? '-'}</td>
                    </tr>
                `;
                        });

                        html += "</tbody></table>";
                        tabla.innerHTML = html;
                    } catch (e) {
                        Swal.fire("Error", "No se pudieron cargar las asistencias.", "error");
                    }
                });
            </script>

        <?php include("../include/footer.php");
    } else {
        session_destroy();
        header("location:../index.php");
    }

        ?>