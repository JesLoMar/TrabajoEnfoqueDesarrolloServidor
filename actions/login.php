<?php

require '../TrabajoEnfoqueDesarrolloServidor/config/db.php';

$errors = [];
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    try {
        $sql = "SELECT * FROM user WHERE username = :username OR email = :email";
        $smt = $pdo->prepare($sql);

        $smt->execute([
            ':username' => $username,
            ':email' => $email,
        ]);
        $user = $smt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Usuario, contraseña o email incorrectos.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error en el sistema.";
    }
}

//(ej: SELECT pedidos FROM pedidos WHERE user_id = $_SESSION['user_id']).