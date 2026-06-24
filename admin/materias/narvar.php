<?php include("../../include/header.php"); ?>


<style>
    /* Ajustes generales */
    @media (min-width: 768px) {
        .btn-menu {
            min-width: 150px;
            height: 40px;
        }
    }

    /* --- NUEVO --- */
    /* Estilo para modo móvil (menú hamburguesa abierto) */
    @media (max-width: 767.98px) {
        .navbar-nav .btn-menu {
            width: 100%;
            /* todos los botones del menú ocuparán el ancho completo */
            text-align: center;
            /* centramos el texto e íconos */
        }

        .navbar-nav .nav-item {
            width: 100%;
        }

        .navbar-nav .nav-item a {
            width: 100%;
        }
    }
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

<nav id="mainNavbar" class="navbar navbar-expand-md navbar-dark fixed-top" style="background-color: #000000ff;">
    <div class="container">
        <!-- Logo -->
         <a class="navbar-brand d-flex align-items-center fw-bold text-light" href="../index.php">
            <img src="../../img/logo.png" alt="Logo" width="40" height="40" class="me-2 rounded-circle">
            Cal Ko
        </a>

        <!-- Botón hamburguesa -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <!-- Contenido del menú -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarResponsive">
            <ul class="navbar-nav d-flex flex-column flex-md-row align-items-md-center gap-2 mt-3 mt-md-0">
                <li class="nav-item">
                    <a href="index.php" class="text-decoration-none w-100">
                        <button type="button" class="btn btn-light btn-menu w-100">
                            Materias <i class="bi bi-book"></i>
                        </button>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="materias_grados.php" class="text-decoration-none w-100">
                        <button type="button" class="btn btn-light btn-menu w-100">
                            <i class="bi bi-journal-bookmark-fill"></i> Grados Materias <i class="bi bi-book"></i>
                        </button>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="docente_grados.php" class="text-decoration-none w-100">
                        <button type="button" class="btn btn-light btn-menu w-100">
                            <i class="bi bi-person-workspace"></i> Docentes Materias <i class="bi bi-book"></i>
                        </button>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="../../cerrar.php" class="text-decoration-none w-100">
                        <button type="button" class="btn btn-light btn-menu w-100">
                            Salir <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    // Ajustar el padding del body según la altura del navbar
    function ajustarEspacioNavbar() {
        const navbar = document.getElementById("mainNavbar");
        const navbarHeight = navbar.offsetHeight;
        document.body.style.paddingTop = navbarHeight + "px";
    }

    document.addEventListener("DOMContentLoaded", ajustarEspacioNavbar);
    window.addEventListener("resize", ajustarEspacioNavbar);
</script>