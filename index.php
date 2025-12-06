<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'includes/header.php';

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
    default:
        break;
}

require 'includes/footer.php';
