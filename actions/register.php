<?php
require '../config/db.php';
$errors = [];
$data = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $name = $_POST["name"] ?? '';
    $surname1 = $_POST["surname1"] ?? '';
    $surname2 = $_POST["surname2"] ?? '';
    $address = $_POST["address"] ?? '';
    $city = $_POST["city"] ?? '';
    $zipCode = $_POST["zip_code"] ?? '';
    $data = $_POST;
    $errors = validate($username, $email, $password, $passwordConfirm, $name, $surname1, $surname2, $address, $city, $zipCode);
    if (empty($errors)) {
        try {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            //Añadimos los valores indicados por el usuario en la base de datos si no hay problemas.
            $sql = "INSERT INTO user (username, email, password, name, surname1, surname2, address, city, zip_code) 
                    VALUES (:username, :email, :password, :name, :surname1, :surname2, :address, :city, :zip_code)";
            $smt = $pdo->prepare($sql);
            $smt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $passwordHash,
                ':name' => $name,
                ':surname1' => $surname1,
                ':surname2' => $surname2,
                ':address' => $address,
                ':city' => $city,
                ':zip_code' => $zipCode,
            ]);
            echo "Usuario registrado!";
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Error SQL (" . $e->getCode() . "): " . $e->getMessage();
            } else {
                $errors[] = "Error en la base de datos: " . $e->getMessage();
            }
        }
    }
}

function validate($username, $email, $password, $passwordConfirm, $name, $surname1, $surname2, $address, $city, $zipCode)
{ //Función de validación de datos del formulario.
    $localErrors = [];
    if (empty($username)) {
        $localErrors[] = "El nombre de usuario es obligatorio.";
    } elseif (strlen($username) < 2) {
        $localErrors[] = "El usuario debe tener al menos 2 caracteres.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $localErrors[] = "El formato del email no es válido.";
    }
    if ($password !== $passwordConfirm) {
        $localErrors[] = "Las contraseñas no coinciden.";
    }
    if (empty($name)) {
        $localErrors[] = "El nombre es obligatorio.";
    } elseif (strlen($name) < 2) {
        $localErrors[] = "El nombre debe tener al menos 2 caracteres.";
    }
    if (empty($surname1)) {
        $localErrors[] = "El apellido es obligatorio.";
    } elseif (strlen($surname1) < 4) {
        $localErrors[] = "El apellido debe tener al menos 4 caracteres.";
    }
    if (!empty($surname2)) {
        if (strlen($surname2) < 4) {
            $localErrors[] = "El segundo apellido debe tener al menos 4 caracteres.";
        }
    }
    if (!empty($address)) {
        if (strlen($address) < 10) {
            $localErrors[] = "La dirección debe ser mayor a 10 caracteres.";
        }
    }
    if (!empty($city)) {
        if (strlen($city) < 3) {
            $localErrors[] = "La ciudad debe tener al menos 3 caracteres.";
        }
    }
    if (!empty($zipCode)) {
        if (!is_numeric($zipCode) || strlen($zipCode) != 5) {
            $localErrors[] = "El código postal ha de ser numérico.";
        }
    }
    return $localErrors;
};