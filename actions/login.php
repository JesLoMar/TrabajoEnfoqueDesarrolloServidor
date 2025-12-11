<?php
require '../TrabajoEnfoqueDesarrolloServidor/config/db.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    try {
        //Buscamos un usuario que tenga el email o usuario dados.
        $sql = "SELECT * FROM user WHERE username = :username OR email = :email";
        $smt = $pdo->prepare($sql);
        $smt->execute([
            ':username' => $username,
            ':email' => $email,
        ]);
        $user = $smt->fetch(PDO::FETCH_ASSOC);
        //Si la contraseña introducida por el usuario coincide con el hash al verificarla, iniciamos la sesión con todos los datos. 
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); //Regeneramos sesion para evitar problemas.
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['rol'] = $user['rol'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['surname1'] = $user['surname1'];
            $_SESSION['surname2'] = $user['surname2'];
            $_SESSION['address'] = $user['address'];
            $_SESSION['city'] = $user['city'];
            $_SESSION['zip_code'] = $user['zip_code'];
            header("Location: index.php?var=user_profile");//Redirigimos al perfil.
            exit;
        } else {
            $errors[] = "Usuario o contraseña incorrecto.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error en el sistema.";
    }
}