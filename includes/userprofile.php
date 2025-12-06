<link rel="stylesheet" href="assets/css/userprofile-frame.css">
<?php
$username = $_SESSION['username'] ?? '';
$username = ucfirst($username);
?>
<main class="main-container">
    <div class="frame">

        <div class="avatar-container">
            <img src="assets/img/icon.png" alt="Icono Web">
        </div>
        <p class="welcome-text">Bienvenido/a, <?php echo htmlspecialchars($username); ?>.</p>

        <div class="menu-options">
            <section class="user-view">
                <div class="menu-btn my-data">
                    <a href="index.php?var=user_profile&page=my_data">Mis datos</a>
                </div>
                <div class="menu-btn orders">
                    <a href="index.php?var=user_profile&page=orders">Pedidos</a>
                </div>
            </section>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1): ?>
                <section class="admin-view">
                    <div class="menu-btn admin-users">
                        <a href="index.php?var=user_profile&page=users">Administrar usuarios</a>
                    </div>
                    <div class="menu-btn add-items">
                        <a href="index.php?var=user_profile&page=items">Añadir Objetos</a>
                    </div>
                    <div class="menu-btn orders-view">
                        <a href="index.php?var=user_profile&page=admin_orders">Ver pedidos</a>
                    </div>
                    <div class="menu-btn inventory-view">
                        <a href="index.php?var=user_profile&page=inventory">Ver inventario</a>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>

    <div class="section-content">
        <?php
        $page = $_GET['page'] ?? '';
        switch ($page) {
            case 'my_data':
                include 'includes/userprofile-views/my-data.php';
                break;
            case 'orders':
                include 'includes/userprofile-views/orders.php';
                break;
            case 'users':
                include 'includes/userprofile-views/admin-users.php';
                break;
            case 'admin_orders':
                include 'includes/userprofile-views/view-orders.php';
                break;
            case 'inventory':
                include 'includes/userprofile-views/view-inventory.php';
                break;
            default:
                break;
        }
        ?>
    </div>
</main>