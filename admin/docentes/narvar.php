<?php include("../../include/header.php"); ?>

<style>
    @media (min-width: 768px) {
        .btn-menu {
            min-width: 130px;
            height: 40px;
        }
    }

    /* --- Botón hamburguesa blanco --- */
    .navbar-toggler {
        background-color: white !important;
        border: none !important;
        padding: 6px 10px;
        border-radius: 6px;
    }

    /* --- Ícono hamburguesa negro --- */
    .navbar-toggler-icon {
        filter: invert(0) brightness(0);
    }

    .navbar-toggler:hover {
        background-color: #f1f1f1 !important;
    }
</style>



<nav id="mainNavbar" class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color: #000000ff; margin-bottom: 50px;">
    <div class="container">
        <!-- Logo e identificación -->
        <a class="navbar-brand d-flex align-items-center fw-bold text-light" href="../index.php">
            <img src="../../img/logo.png" alt="Logo" width="40" height="40" class="me-2 rounded-circle">
            Cal Ko
        </a>

        <!-- Botón hamburguesa blanco -->
        <button
            class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarResponsive"
            aria-controls="navbarResponsive"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <!-- Contenido del menú -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarResponsive">
            <!-- Contenedor de botones -->
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 mt-3 mt-md-0">
                <!-- Botón Registrar -->
                <a href="index.php" class="text-decoration-none w-100 w-md-auto"> <button type="button" class="btn btn-light btn-menu w-100 w-md-auto">
                        Docentes <i class="bi bi-person-workspace"></i>
                    </button>
                </a>
                <!-- Botón Salir -->
                <a href="../../cerrar.php" class="text-decoration-none w-100 w-md-auto">
                    <button type="button" class="btn btn-light btn-menu w-100 w-md-auto">
                        Salir <i class="bi bi-box-arrow-right"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>
</nav>
<script>
    // Función que ajusta el espacio del body según la altura del navbar
    function ajustarEspacioNavbar() {
        const navbar = document.getElementById("mainNavbar");
        const navbarHeight = navbar.offsetHeight;
        document.body.style.paddingTop = navbarHeight + "px";
    }

    // Ejecutar cuando la página cargue
    document.addEventListener("DOMContentLoaded", ajustarEspacioNavbar);

    // Ejecutar también cada vez que se cambie el tamaño de la ventana
    window.addEventListener("resize", ajustarEspacioNavbar);
</script>