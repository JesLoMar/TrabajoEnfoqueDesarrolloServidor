<?php
//seccion similar a view-orders.php pero simplificada para el usuario, aquí solo se muestran los pedidos propios.
if (!isset($_SESSION['user_id'])) {
    exit;
}
require 'config/db.php';
$user_id = $_SESSION['user_id'];
$orders = [];
try {
    $sql = "SELECT * FROM orders WHERE user_id = :uid ORDER BY order_time DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':uid' => $user_id]);
    
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert-error'>Error al cargar los pedidos.</div>";
}
?>

<section class="main-orders">
    <h1 class="orders-title">Mis pedidos</h1>
    <?php if (isset($_GET['status']) && $_GET['status'] === 'success_order'): ?>
    <div class="alert-success" style="padding: 15px; margin-bottom: 20px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; text-align: center;">
        ¡Pedido realizado con éxito! Gracias por tu compra.
    </div>
<?php endif; ?>
    <h3 class="orders-subtitle">Aquí podrá visualizar el historial de pedidos que ha hecho con su cuenta</h3>

    <?php if (empty($orders)): ?>
            <div>
                <p>Aún no has realizado ninguna compra.</p>
                <a href="index.php" style="color: #8a8a8aff; font-weight: bold;">Ir a la tienda</a>
            </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Dirección de Envío</th>
                        <th>Importe</th>
                        <th>Estado</th>
                        <th>Acciones</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <?php 
                                    echo date("d/m/Y", strtotime($order['order_time'])); 
                                ?>
                            </td>
                            
                            <td>
                                <?php echo htmlspecialchars($order['order_shipping_address']); ?>
                            </td>

                            <td style="font-weight: bold;">
                                <?php echo number_format($order['order_price'], 2); ?> €
                            </td>

                            <td>
                                <span class="badge badge-<?php echo strtolower($order['order_state']); ?>">
                                    <?php echo htmlspecialchars($order['order_state']); ?>
                                </span>
                            </td>

                            <td>
                                <a href="index.php?var=user_profile&view=order_details&id=<?php echo $order['order_id']; ?>" class="btn-details">
                                    Ver Detalles
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>