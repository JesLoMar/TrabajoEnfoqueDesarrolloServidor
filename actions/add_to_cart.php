<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$item_id  = (int)($_POST['item_id'] ?? 0);
$size_id  = (int)($_POST['size_id'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 1);

if ($item_id <= 0 || $size_id <= 0 || $quantity <= 0) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_key = $item_id . '_' . $size_id;

if (isset($_SESSION['cart'][$cart_key])) {
    $_SESSION['cart'][$cart_key] += $quantity;
} else {
    $_SESSION['cart'][$cart_key] = $quantity;
}

header("Location: ../index.php?var=shopping_cart");
exit;
?>