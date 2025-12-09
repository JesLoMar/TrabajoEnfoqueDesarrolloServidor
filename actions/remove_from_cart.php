<?php
session_start();

$key = $_GET['key'] ?? '';

if (!empty($key) && isset($_SESSION['cart'][$key])) {
    unset($_SESSION['cart'][$key]);
}

header("Location: ../index.php?var=shopping_cart");
exit;
?>