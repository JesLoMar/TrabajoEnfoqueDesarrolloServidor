<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../index.php?var=login");
    exit;
}
try {
    $item_id = (int)($_POST['item_id'] ?? 0);
    if ($item_id > 0) {
        //Transacción por seguridad.
        $pdo->beginTransaction();
        //Borramos inventario asociado.
        $sql_inv = "DELETE FROM inventory WHERE item_id = :id";
        $stmt1 = $pdo->prepare($sql_inv);
        $stmt1->execute([':id' => $item_id]);
        //Borramos artículo.
        $sql_item = "DELETE FROM items WHERE item_id = :id";
        $stmt3 = $pdo->prepare($sql_item);
        $stmt3->execute([':id' => $item_id]);
        //Confirmamos cambios si no hay errores.
        $pdo->commit();
        header("Location: ../index.php?var=user_profile&view=manage_items&status=deleted");
        exit;
    } else {
        header("Location: ../index.php?var=user_profile&view=manage_items&status=error");
        exit;
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage(); die();
    header("Location: ../index.php?var=user_profile&view=manage_items&status=error");
    exit;
}
?>