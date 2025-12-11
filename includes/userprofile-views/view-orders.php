<?php
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    echo "<div class='alert-error'>Acceso denegado.</div>";
    return;
}

require_once 'config/db.php';

// Configuracion de la paginacion.
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$offset = ($pagina - 1) * $limite;

// Consulta total para saber cuantas paginas hay.
$sql_count = "SELECT COUNT(*) FROM orders";
$stmt_count = $pdo->query($sql_count);
$total_pedidos = $stmt_count->fetchColumn();
$total_paginas = ceil($total_pedidos / $limite);

//Consulta de cantidad de pedidos.
$sql = "
    SELECT o.order_id, o.order_time, o.order_price, o.order_state, 
        u.username, os.state_name
    FROM orders o
    JOIN user u ON o.user_id = u.user_id
    JOIN order_state os ON o.order_state = os.state_id
    ORDER BY o.order_time DESC
    LIMIT $limite OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="main-mydata">
    <h1 class="mydata-title">Gestión de Pedidos</h1>
    <h3 class="mydata-subtitle">Listado completo de ventas</h3>

    <div class="table-responsive">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td class="text-mono">#<?php echo $pedido['order_id']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($pedido['order_time'])); ?></td>
                        <td><?php echo htmlspecialchars($pedido['username']); ?></td>
                        <td><?php echo number_format($pedido['order_price'], 2); ?> €</td>
                        <td>
                            <span class="badge" style="background:#eee; color:#333; padding:4px 8px; border-radius:4px;">
                                <?php echo htmlspecialchars($pedido['state_name']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?var=user_profile&view=order_details&id=<?php echo $pedido['order_id']; ?>"
                                class="btn-details" style="text-decoration:none; color:blue;">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($total_paginas > 1): //Contolador del paginado ?>
        <div style="margin-top: 20px; display: flex; gap: 5px;">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="index.php?var=user_profile&view=admin_orders&pagina=<?php echo $i; ?>"
                    style="padding: 5px 10px; border: 1px solid #ccc; <?php echo ($i == $pagina) ? 'background:#333; color:white;' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</section>