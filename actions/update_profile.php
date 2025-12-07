<?php
session_start();
$errors = [];

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php?var=login");
    exit;
}

require '../config/db.php';

try {
    $id_usuario = $_SESSION['user_id'];

    $name     = trim($_POST['name'] ?? '');
    $surname1 = trim($_POST['surname1'] ?? '');
    $surname2 = trim($_POST['surname2'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $city     = trim($_POST['city'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');

    if (empty($name)) {
        $errors[] = "El nombre es obligatorio.";
    }
    if (empty($surname1)) {
        $errors[] = "El primer apellido es obligatorio.";
    }

    if (empty($errors)) {

        $sql = "UPDATE user SET name = :name, surname1 = :surname1,
                surname2 = :surname2, address = :address, city = :city,
                zip_code = :zip_code WHERE user_id = :id";

        $stmt = $pdo->prepare($sql);

        $resultado = $stmt->execute([
            ':name'     => $name,
            ':surname1' => $surname1,
            ':surname2' => $surname2,
            ':address'  => $address,
            ':city'     => $city,
            ':zip_code' => $zip_code,
            ':id'       => $id_usuario
        ]);

        if ($resultado) {
            $_SESSION['name']     = $name;
            $_SESSION['surname1'] = $surname1;
            $_SESSION['surname2'] = $surname2;
            $_SESSION['address']  = $address;
            $_SESSION['city']     = $city;
            $_SESSION['zip_code'] = $zip_code;

            header("Location: ../index.php?var=user_profile&view=my_data&status=success");
            exit;
        } else {
            $errors[] = "No se pudieron guardar los cambios en la base de datos.";
        }
    }
} catch (PDOException $e) {
    $errors[] = "Error en el sistema: " . $e->getMessage();
}
$_SESSION['errores_update'] = $errors;
header("Location: ../index.php?var=user_profile&view=my_data&status=error");
exit;
