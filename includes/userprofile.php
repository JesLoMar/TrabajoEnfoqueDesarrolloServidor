<?php
$username = $_SESSION['username'] ?? '';
$username = ucfirst($username);
$email = $_SESSION['email'] ?? '';
$rol = $_SESSION['rol'] ?? '';
if ($rol == 1) {
    $rol = 'Administrador';
} else {
    $rol = 'Usuario';
}
?>
<link rel="stylesheet" href="assets/css/admin.css">
<main class="main-container">
    <div class="frame">
        <div class="avatar-container">
            <img src="assets/img/icon.png" alt="Icono Web">
        </div>
        <p class="welcome-text">USUARIO: <?php echo htmlspecialchars($username); ?></p>
        <p class="welcome-text">CORREO: <?php echo htmlspecialchars($email); ?></p>
        <p class="welcome-text">ROL: <?php echo htmlspecialchars($rol); ?></p>

        <div class="menu-options">
            <section class="user-view">
                <div class="menu-btn my-data">
                    <a href="index.php?var=user_profile&view=my_data">Mis datos</a>
                </div>
                <div class="menu-btn orders">
                    <a href="index.php?var=user_profile&view=orders">Mis pedidos</a>
                </div>
            </section>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1): ?>
                <section class="admin-view">
                    <div class="menu-btn admin-users">
                        <a href="index.php?var=user_profile&view=users">Administrar usuarios</a>
                    </div>
                    <div class="menu-btn add-items">
                        <a href="index.php?var=user_profile&view=items">Añadir objetos</a>
                    </div>
                    <div class="menu-btn orders-view">
                        <a href="index.php?var=user_profile&view=admin_orders">Pedidos</a>
                    </div>
                    <div class="menu-btn inventory-view">
                        <a href="index.php?var=user_profile&view=inventory">Organizar inventario</a>
                    </div>
                </section>
            <?php endif; ?>
            <section class="user-view">
                <div class="menu-btn logout">
                    <a href="index.php?var=logout">Cerrar sesión</a>
                </div>
            </section>
        </div>
    </div>

    <div class="section-content">
        <?php
        $view = $_GET['view'] ?? '';
        switch ($view) {
            case 'my_data':
                include 'includes/userprofile-views/my-data.php';
                break;
            case 'orders':
                include 'includes/userprofile-views/orders.php';
                break;
            case 'order_details':
                include 'includes/userprofile-views/view-order-details.php';
                break;
            case 'users':
                include 'includes/userprofile-views/admin-users.php';
                break;
            case 'items':
                include 'includes/userprofile-views/add-items.php';
                break;
            case 'manage_items':
                include 'includes/userprofile-views/manage-items.php';
                break;
            case 'edit_item':
                include 'includes/userprofile-views/edit-item.php';
                break;
            case 'inventory':
                include 'includes/userprofile-views/view-inventory.php';
                break;
            case 'add_inventory';
                include 'includes/userprofile-views/add-inventory.php';
                break;
            case 'admin_orders':
                include 'includes/userprofile-views/view-orders.php';
                break;
            default:
                break;
        }
        ?>
    </div>
</main>