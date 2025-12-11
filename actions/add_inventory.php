<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../index.php?var=login");
    exit;
}
try {
    $item_id  = (int)($_POST['item_id'] ?? 0);
    $size_id  = (int)($_POST['size_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    if ($item_id <= 0 || $size_id <= 0 || $quantity <= 0) {
        header("Location: ../index.php?var=user_profile&view=add_inventory&status=error");
        exit;
    }
    //Comprobamos si tenemos esta combinación de objeto y talla en la BD.
    $sql_check = "SELECT inventory_id, stock_quantity FROM inventory WHERE item_id = :item AND size_id = :size";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([
        ':item' => $item_id,
        ':size' => $size_id
    ]);
    $current_record = $stmt_check->fetch(PDO::FETCH_ASSOC);
    //Si ya hay ese objeto, sumamos cantidad.
    if ($current_record) {
        $sql_update = "UPDATE inventory SET stock_quantity = stock_quantity + :qty WHERE inventory_id = :inv_id";
        $stmt_update = $pdo->prepare($sql_update);
        $outcome = $stmt_update->execute([
            ':qty'    => $quantity,
            ':inv_id' => $current_record['inventory_id']
        ]);
        
    } else {
        //Si no existe esa combinación de objeto y talla, añadimos una fila a la BD.
        $sql_insert = "INSERT INTO inventory (item_id, size_id, stock_quantity) VALUES (:item, :size, :qty)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $outcome = $stmt_insert->execute([
            ':item' => $item_id,
            ':size' => $size_id,
            ':qty'  => $quantity
        ]);
    }
    if ($outcome) {
        header("Location: ../index.php?var=user_profile&view=inventory&status=success");
        exit;
    } else {
        header("Location: ../index.php?var=user_profile&view=add_inventory&status=error");
        exit;
    }
} catch (PDOException $e) {
    header("Location: ../index.php?var=user_profile&view=add_inventory&status=error");
    exit;
}
?>