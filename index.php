<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'includes/header.php';

// Controlador de sección main a mostrar según variable
$section = $_GET['var'] ?? '';
switch ($section) {
    case 'login':
        if (isset($_SESSION['user_id'])) {
            include 'includes/userprofile.php';
            break;
        } else {
            require 'actions/login.php';
            include 'includes/loginf.php';
            break;
        }
    case 'register':
        require 'actions/register.php';
        include 'includes/registerf.php';
        break;
    case 'user_profile':
        include 'includes/userprofile.php';
        break;
    case 'shopping_cart':
        include 'includes/shoppingcart.php';
        break;
    case 'logout':
        include 'actions/logout.php';
        break;
    default:
        include 'includes/general-display.php';
        break;
}

require 'includes/footer.php';