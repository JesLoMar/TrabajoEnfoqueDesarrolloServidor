<?php
session_start();
require '../config/db.php';
$errors = [];
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../index.php");
    exit;
}
try {
    $item_id = (int)$_POST['item_id'];
    //Limpiamos los datos introducidos.
    $brand_id    = trim($_POST['brand_id'] ?? ''); 
    $name        = trim($_POST['name'] ?? '');
    $sku         = trim($_POST['sku'] ?? '');
    $colorway    = trim($_POST['colorway'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $gender      = trim($_POST['gender'] ?? '');
    $image_url   = trim($_POST['image_url'] ?? '');
    // Comprobamos que no haya campos vacíos.
    if (empty($brand_id)) $errors[] = "Marca obligatoria.";
    if (empty($name)) $errors[] = "Nombre obligatorio.";
    if (empty($sku)) $errors[] = "SKU obligatorio.";
    if (empty($price)) $errors[] = "Precio obligatorio.";
    $price = str_replace(',', '.', $price); //Estandarizamos el formato del precio.
    if (!is_numeric($price) || $price < 0) $errors[] = "Precio inválido."; //Si no es numérico el valor del campo "price" marcamos error.
    if (empty($errors)) {
        //Si no hay errores, actualizamos los datos del objeto con la ID dada por los nuevos valores.
        $sql = "UPDATE items SET 
                    brand_id = :brand_id,
                    name = :name,
                    sku = :sku,
                    colorway = :colorway,
                    description = :description,
                    price = :price,
                    gender = :gender,
                    image_url = :image_url
                WHERE item_id = :item_id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':brand_id'    => $brand_id,
            ':name'        => $name,
            ':sku'         => $sku,
            ':colorway'    => $colorway,
            ':description' => $description,
            ':price'       => $price,
            ':gender'      => $gender,
            ':image_url'   => $image_url,
            ':item_id'     => $item_id
        ]);
        if ($result) { //Sacamos aviso de datos cambiados si todo va bien.
            header("Location: ../index.php?var=user_profile&view=manage_items&status=updated");
            exit;
        } else { //Si no, mostramos error.
            $errors[] = "Error al actualizar la base de datos.";
        }
    }
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        $errors[] = "Ese SKU ya está siendo usado por otro producto.";
    } else {
        $errors[] = "Error SQL: " . $e->getMessage();
    }
}
$_SESSION['errores_update'] = $errors;
header("Location: ../index.php?var=user_profile&view=edit_item&id=$item_id&status=error");
exit;
?>