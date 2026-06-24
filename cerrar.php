
<?php
session_start();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Finalmente, destruye la sesión.
session_destroy();

// Opcional: Redirige al usuario a la página de inicio de sesión o principal
header("location: index.php");
exit;

?>