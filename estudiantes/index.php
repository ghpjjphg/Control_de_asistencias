<?php include("../data_base.php");
include("../include/header.php");
if (isset($_SESSION['rol']) and $_SESSION['rol'] == 3) {
    include("narvar.php");  ?>
    <!-- Librería del lector QR -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>


    <div class="centrarpla d-flex justify-content-center align-items-center">
        <div class="container py-5">
            <h2 class="text-center mb-5">Registrar asistencia</h2>

            <!-- Modal -->
            <div class="modal fade" id="modalQR" tabindex="-1" aria-labelledby="modalQRLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalQRLabel">Escanea tu código QR</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body text-center">
                            <!-- Lector QR -->
                            <div id="reader" style="width:100%;"></div>
                            <!-- Resultado -->
                            <div id="qr-result" class="alert alert-info mt-3 d-none"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center g-4">

                <!-- Card 1: Administrar Usuarios -->
                <div class="col-md-4">
                    <div class="card text-center shadow-lg h-100">
                        <div class="card-body">
                            <!-- Icono de usuarios -->
                            <i class="bi bi-qr-code-scan display-1 text-primary mb-3"></i>
                            <h5 class="card-title">Registar asistencia</h5>
                            <p class="card-text">Registra tus asistencias </p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalQR">
                                <i class="bi bi-qr-code-scan"></i> Escanear QR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Botón de escanear -->

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const modalQR = document.getElementById('modalQR');
        const resultDiv = document.getElementById('qr-result');
        let html5QrCode = null;
        let isScanning = false;
        let currentCameraId = null;
        let cameras = [];
        let isFrontCamera = false;

        // Botón para cambiar cámara
        const switchBtn = document.createElement("button");
        switchBtn.textContent = "🔄 Cambiar cámara";
        switchBtn.className = "btn btn-secondary mt-3";
        switchBtn.style.display = "none";

        document.addEventListener("DOMContentLoaded", () => {
            const readerDiv = document.querySelector("#reader");
            readerDiv.after(switchBtn);
        });

        async function startScanner(cameraId = null) {
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");
            if (isScanning) return;
            isScanning = true;

            try {
                await html5QrCode.start(
                    cameraId ? {
                        deviceId: {
                            exact: cameraId
                        }
                    } : {
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    qrMessage => {
                        stopScanner();
                        resultDiv.classList.remove("d-none");
                        resultDiv.textContent = `Código detectado: ${qrMessage}`;

                        // ✅ Normalizar texto y convertirlo a objeto
                        const cleanText = qrMessage.replace(/\r/g, "").trim();
                        const data = {};
                        cleanText.split("\n").forEach(line => {
                            const [key, value] = line.split(":");
                            if (key && value) data[key.trim()] = value.trim();
                        });

                        console.log("Datos leídos del QR:", data);

                        if (!data.ID_Docente || !data.ID_Grado || !data.ID_Materia) {
                            Swal.fire({
                                icon: "warning",
                                title: "QR inválido",
                                text: "El código QR no contiene todos los datos requeridos.",
                            });
                            return;
                        }

                        // ✅ Enviar datos al backend
                        fetch("registrar_asistencia.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                body: new URLSearchParams({
                                    ID_Docente: data.ID_Docente,
                                    ID_Grado: data.ID_Grado,
                                    ID_Materia: data.ID_Materia
                                })
                            })
                            .then(res => res.json())
                            .then(response => {
                                console.log("Respuesta del servidor:", response);

                                const icons = {
                                    success: "success",
                                    warning: "warning",
                                    error: "error"
                                };

                                Swal.fire({
                                    icon: icons[response.status] || "info",
                                    title: response.status === "success" ? "Asistencia registrada" : response.status === "warning" ? "Atención" : "Error",
                                    text: response.message,
                                    timer: 4000,
                                    confirmButtonText: "Aceptar"
                                });

                                const modal = bootstrap.Modal.getInstance(modalQR);
                                modal.hide();
                            })
                            .catch(err => {
                                console.error("Error al procesar la respuesta del servidor:", err);
                                Swal.fire({
                                    icon: "error",
                                    title: "Error de conexión",
                                    text: "No se pudo registrar la asistencia. Inténtalo de nuevo.",
                                });
                            });
                    },
                    errorMessage => {
                        /* Ignorar errores de lectura menores */
                    }
                );
            } catch (err) {
                resultDiv.classList.remove("d-none");
                resultDiv.textContent = "Error al acceder a la cámara: " + err;
                isScanning = false;
            }
        }

        async function stopScanner() {
            if (html5QrCode && isScanning) {
                await html5QrCode.stop();
                html5QrCode.clear();
                isScanning = false;
            }
        }

        modalQR.addEventListener('shown.bs.modal', async function() {
            resultDiv.classList.add("d-none");
            resultDiv.textContent = "";
            const devices = await Html5Qrcode.getCameras();
            if (devices && devices.length) {
                cameras = devices;
                switchBtn.style.display = "block";
                currentCameraId = devices[0].id;
                await startScanner(currentCameraId);
            } else {
                resultDiv.classList.remove("d-none");
                resultDiv.textContent = "No se detectaron cámaras en el dispositivo.";
            }
        });

        modalQR.addEventListener('hidden.bs.modal', async function() {
            await stopScanner();
        });

        switchBtn.addEventListener("click", async function() {
            if (!cameras.length) return;
            await stopScanner();
            isFrontCamera = !isFrontCamera;
            const newCamera = isFrontCamera ? cameras[cameras.length - 1] : cameras[0];
            currentCameraId = newCamera.id;
            await startScanner(currentCameraId);
        });
    </script>


<?php include("../include/footer.php");
} else {
    session_destroy();
    header("location:../index.php");
}

?>