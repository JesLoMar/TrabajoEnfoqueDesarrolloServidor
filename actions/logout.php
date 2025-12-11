<?php
session_start();
//Reseteamos los valores de la sesión con un array vacío.
$_SESSION = array();
if (ini_get("session.use_cookies")) { //Si el server usa cookies para sesiones optiene config de la cookie actual.
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, //Borramos el contenido y damos tiempo de validez negativo para que el navegador la borre automáticamente.
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy(); //Eliminamos el archivo de sesión en el servidor.

//Añadimos información al header para indicar que no se guarde nada de la página actual ni anteriores en caché. (Seguridad para evitar volver)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//Redirección.
header("Location: index.php");
exit;
?>