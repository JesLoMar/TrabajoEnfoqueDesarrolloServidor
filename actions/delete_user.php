<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../index.php?var=login");
    exit;
}

try {
    $target_id = (int)($_POST['target_user_id'] ?? 0);
    if ($target_id === $_SESSION['user_id']) {
        header("Location: ../index.php?var=user_profile&view=users&status=error_self");
        exit;
    }

    if ($target_id > 0) {
        $pdo->beginTransaction();
        $sql_items = "DELETE oi FROM order_items oi 
                    INNER JOIN orders o ON oi.order_id = o.order_id 
                    WHERE o.user_id = :id";
        $stmt1 = $pdo->prepare($sql_items);
        $stmt1->execute([':id' => $target_id]);
        $sql_orders = "DELETE FROM orders WHERE user_id = :id";
        $stmt2 = $pdo->prepare($sql_orders);
        $stmt2->execute([':id' => $target_id]);
        $sql_user = "DELETE FROM user WHERE user_id = :id";
        $stmt3 = $pdo->prepare($sql_user);
        $stmt3->execute([':id' => $target_id]);
        $pdo->commit();

        header("Location: ../index.php?var=user_profile&view=users&status=deleted");
        exit;
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    header("Location: ../index.php?var=user_profile&view=users&status=error");
    exit;
}