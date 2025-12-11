<?php
session_start();
require_once '../config/db.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['nuevo_estado'])) {
    
    $order_id = (int)$_POST['order_id'];
    $nuevo_estado = (int)$_POST['nuevo_estado'];
    try {
        //Actualizamos el estado de un pedido.
        $stmt = $pdo->prepare("UPDATE orders SET order_state = :estado WHERE order_id = :id");
        $result = $stmt->execute([
            ':estado' => $nuevo_estado,
            ':id' => $order_id
        ]);
        if ($result) {
            header("Location: ../index.php?var=user_profile&view=admin_orders&id=" . $order_id . "&status=success");
            exit;
        } else {
            header("Location: ../index.php?var=user_profile&view=admin_orders&id=" . $order_id . "&status=error");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: ../index.php?var=user_profile&view=admin_orders&id=" . $order_id . "&status=error");
        exit;
    }
} else {
    header("Location: ../index.php?var=user_profile&view=orders");
    exit;
}