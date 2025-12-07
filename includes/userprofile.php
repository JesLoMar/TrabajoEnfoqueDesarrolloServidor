<link rel="stylesheet" href="assets/css/userprofile.css">
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
                    <a href="index.php?var=user_profile&view=my_data">Mis datos</a>
                </div>
                <div class="menu-btn orders">
                    <a href="index.php?var=user_profile&view=orders">Pedidos</a>
                </div>
            </section>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1): ?>
                <section class="admin-view">
                    <div class="menu-btn admin-users">
                        <a href="index.php?var=user_profile&view=users">Administrar usuarios</a>
                    </div>
                    <div class="menu-btn add-items">
                        <a href="index.php?var=user_profile&view=items">Añadir Objetos</a>
                    </div>
                    <div class="menu-btn orders-view">
                        <a href="index.php?var=user_profile&view=admin_orders">Ver pedidos</a>
                    </div>
                    <div class="menu-btn inventory-view">
                        <a href="index.php?var=user_profile&view=inventory">Ver inventario</a>
                    </div>
                </section>
            <?php endif; ?>
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
            case 'users':
                include 'includes/userprofile-views/admin-users.php';
                break;
            case 'admin_orders':
                include 'includes/userprofile-views/view-orders.php';
                break;
            case 'inventory':
                include 'includes/userprofile-views/view-inventory.php';
                break;
            case 'items':
                include 'includes/userprofile-views/add-items.php';
                break;
            default:
                break;
        }
        ?>
    </div>
</main>