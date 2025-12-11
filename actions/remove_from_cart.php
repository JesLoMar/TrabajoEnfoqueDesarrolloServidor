<?php
session_start();
$key = $_GET['key'] ?? '';
if (!empty($key) && isset($_SESSION['cart'][$key])) {
    //Eliminamos el elemento con la clave aportada del carrito.
    unset($_SESSION['cart'][$key]);
}
header("Location: ../index.php?var=shopping_cart");
exit;
?>