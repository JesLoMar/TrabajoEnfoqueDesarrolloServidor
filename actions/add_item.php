<?php
session_start();
require '../config/db.php';

$errors = [];

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../index.php?var=login");
    exit;
}

try {
    $brand_id    = trim($_POST['brand_id'] ?? ''); 
    $name        = trim($_POST['name'] ?? '');
    $sku         = trim($_POST['sku'] ?? '');
    $colorway    = trim($_POST['colorway'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $gender      = trim($_POST['gender'] ?? '');
    $image_url     = trim($_POST['image_url'] ?? '');

    if (empty($brand_id)) $errors[] = "Debe seleccionar una marca.";
    if (empty($name)) $errors[] = "El nombre es obligatorio.";
    if (empty($sku)) $errors[] = "El SKU es obligatorio.";
    if (empty($description)) $errors[] = "La descripción es obligatoria.";
    if (empty($price)) $errors[] = "El precio es obligatorio.";
    if (empty($gender)) $errors[] = "La selección de tarjet objetivo es obligatoria.";
    if (empty($image_url)) $errors[] = "La imagen es obligatoria.";
    
    $generos_validos = ['Men', 'Women', 'Unisex', 'Kids'];
    if (!in_array($gender, $generos_validos)) {
        $errors[] = "El género seleccionado no es válido.";
    }

    $price = str_replace(',', '.', $price);
    if (!is_numeric($price) || $price < 0) {
        $errors[] = "El precio debe ser un número válido.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO items (brand_id, name, sku, colorway, description, price, gender, image_url) 
                VALUES (:brand_id, :name, :sku, :colorway, :description, :price, :gender, :image_url)";
        
        $stmt = $pdo->prepare($sql);
        
        $resultado = $stmt->execute([
            ':brand_id'    => $brand_id,
            ':name'        => $name,
            ':sku'         => $sku,
            ':colorway'    => $colorway,
            ':description' => $description,
            ':price'       => $price,
            ':gender'      => $gender,
            ':image_url'     => $image_url
        ]);

        if ($resultado) {
            header("Location: ../index.php?var=user_profile&view=items&status=success");
            exit;
        } else {
            $errors[] = "Error al insertar en la base de datos.";
        }
    }

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        $errors[] = "El SKU introducido ya existe.";
    } else {
        $errors[] = "Error del sistema: " . $e->getMessage();
    }
}

$_SESSION['errores_update'] = $errors;
header("Location: ../index.php?var=user_profile&view=items&status=error");
exit;
?>