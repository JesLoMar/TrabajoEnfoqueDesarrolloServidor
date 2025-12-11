<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../index.php?var=login");
    exit;
}
try {
    $target_id = (int)($_POST['target_user_id'] ?? 0);
    $new_rol   = (int)($_POST['new_rol'] ?? 2);
    //Comprobamos que el id de la sesion y la id objetivo no sean la misma, para que alguien no se quite su rol de admin a si mismo por error.
    if ($target_id === $_SESSION['user_id']) {
        header("Location: ../index.php?var=user_profile&view=users&status=error");
        exit;
    }
    if ($target_id > 0) {
        $sql = "UPDATE user SET rol = :rol WHERE user_id = :id";
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute([
            ':rol' => $new_rol,
            ':id'  => $target_id
        ]);
        if ($resultado) {
            header("Location: ../index.php?var=user_profile&view=users&status=success");
            exit;
        }
    }
    header("Location: ../index.php?var=user_profile&view=users&status=error");
    exit;
} catch (PDOException $e) {
    header("Location: ../index.php?var=user_profile&view=users&status=error");
    exit;
}
?>