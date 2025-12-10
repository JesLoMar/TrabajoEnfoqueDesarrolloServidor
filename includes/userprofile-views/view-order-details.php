<?php
// includes/userprofile-views/view-order-details.php

// 1. Verificar si hay usuario logueado (sesión iniciada en el padre)
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert-error'>Debes iniciar sesión.</div>";
    return;
}

require_once 'config/db.php';

// 2. Validar que viene un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert-error'>Pedido no especificado.</div>";
    return;
}

$order_id = (int)$_GET['id'];
$current_user_id = $_SESSION['user_id'];
// Determinamos si es admin (asumiendo rol 1 = admin)
$is_admin = (isset($_SESSION['rol']) && $_SESSION['rol'] == 1);

try {
    // 3. CONSULTA PRINCIPAL
    // Obtenemos los datos del pedido INCLUYENDO el user_id del comprador para verificar seguridad
    $sql_header = "
        SELECT 
            o.order_id, o.order_time, o.order_price, o.order_shipping_address, o.order_state, o.user_id,
            u.username, u.email, u.name, u.surname1,
            os.state_name
        FROM orders o
        JOIN user u ON o.user_id = u.user_id
        JOIN order_state os ON o.order_state = os.state_id
        WHERE o.order_id = :id
    ";
    $stmt = $pdo->prepare($sql_header);
    $stmt->execute([':id' => $order_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        echo "<div class='alert-error'>El pedido no existe.</div>";
        return;
    }

    // 4. CANDADO DE SEGURIDAD
    // Si NO es admin Y el pedido NO pertenece al usuario logueado -> Bloquear
    if (!$is_admin && $pedido['user_id'] != $current_user_id) {
        echo "<div class='alert-error'>⛔ No tienes permiso para ver este pedido.</div>";
        return;
    }

    // 5. Obtener los productos (Items)
    $sql_items = "
        SELECT 
            oi.quantity, oi.unit_price, 
            i.name as item_name, i.image_url, i.sku,
            s.size_name
        FROM order_items oi
        JOIN items i ON oi.item_id = i.item_id
        JOIN sizes s ON oi.size = s.size_id
        WHERE oi.order_id = :id
    ";
    $stmtItems = $pdo->prepare($sql_items);
    $stmtItems->execute([':id' => $order_id]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // 6. Cargar lista de estados (SOLO SI ES ADMIN, para ahorrar recursos)
    $estados_posibles = [];
    if ($is_admin) {
        $stmtStates = $pdo->query("SELECT * FROM order_state");
        $estados_posibles = $stmtStates->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    echo "<div class='alert-error'>Error de base de datos.</div>";
    return;
}

// 7. Configurar enlace 'Volver' dinámicamente
$back_link = $is_admin 
    ? "index.php?var=user_profile&view=admin_orders"  // Admin vuelve a la lista global
    : "index.php?var=user_profile&view=orders";       // Usuario vuelve a sus pedidos
?>

<section class="main-mydata">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 class="mydata-title" style="margin:0;">Pedido #<?php echo $order_id; ?></h1>
        <a href="<?php echo $back_link; ?>" class="btn-save" style="padding: 6px 15px; text-decoration: none; font-size: 0.9em; background-color: #6c757d;">
            &laquo; Volver
        </a>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'success'): ?>
            <div style="padding:10px; background:#d4edda; color:#155724; border-radius:4px; margin-bottom:15px; text-align:center;">
                ✅ Estado actualizado correctamente.
            </div>
        <?php elseif ($_GET['status'] === 'error'): ?>
            <div style="padding:10px; background:#f8d7da; color:#721c24; border-radius:4px; margin-bottom:15px; text-align:center;">
                ❌ Hubo un error al actualizar.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div style="background: #fdfdfd; padding: 20px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.03);">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div>
                <h4 style="margin-top: 0; color: #444; border-bottom: 1px solid #eee; padding-bottom: 5px;">Datos del Cliente</h4>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['name'] . ' ' . $pedido['surname1']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($pedido['email']); ?></p>
            </div>
            
            <div>
                <h4 style="margin-top: 0; color: #444; border-bottom: 1px solid #eee; padding-bottom: 5px;">Detalles de Envío</h4>
                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['order_shipping_address']); ?></p>
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['order_time'])); ?></p>
                <p><strong>Estado:</strong> 
                    <span style="background:#333; color:white; padding:3px 8px; border-radius:4px; font-size: 0.9em;">
                        <?php echo htmlspecialchars($pedido['state_name']); ?>
                    </span>
                </p>
            </div>
        </div>
    </div>

    <h3 style="margin-bottom: 15px; border-left: 4px solid #333; padding-left: 10px;">Artículos comprados</h3>
    <div class="table-responsive">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Talla</th>
                    <th>Precio</th>
                    <th>Cant.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php $subtotal = $item['quantity'] * $item['unit_price']; ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <?php if(!empty($item['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                <?php endif; ?>
                                <div>
                                    <div style="font-weight:bold;"><?php echo htmlspecialchars($item['item_name']); ?></div>
                                    <div style="font-size: 0.85em; color: #666;">SKU: <?php echo htmlspecialchars($item['sku']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($item['size_name']); ?></td>
                        <td><?php echo number_format($item['unit_price'], 2); ?> €</td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td style="font-weight: bold;"><?php echo number_format($subtotal, 2); ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa;">
                    <td colspan="4" style="text-align: right; padding: 15px; font-size: 1.1em; font-weight: bold;">TOTAL PEDIDO:</td>
                    <td style="padding: 15px; font-size: 1.1em; font-weight: bold; color: #007bff;"><?php echo number_format($pedido['order_price'], 2); ?> €</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <?php if ($is_admin): ?>
        <div style="margin-top: 40px; background: #eef2f5; padding: 20px; border-radius: 8px; border: 1px solid #dbe2e8;">
            <h4 style="margin-top: 0; color: #2c3e50; display:flex; align-items:center; gap:10px;">
                ⚙️ Gestión Administrativa
            </h4>
            <p style="font-size: 0.9em; margin-bottom: 15px; color: #666;">Modifica el estado del envío para que el cliente pueda ver el progreso.</p>
            
            <form action="actions/update_order_state.php" method="POST" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                
                <div style="flex-grow: 1; max-width: 300px;">
                    <select name="nuevo_estado" id="nuevo_estado" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                        <?php foreach ($estados_posibles as $estado): ?>
                            <option value="<?php echo $estado['state_id']; ?>" 
                                <?php echo ($pedido['order_state'] == $estado['state_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($estado['state_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn-save" style="margin: 0; background-color: #28a745;">Guardar Cambios</button>
            </form>
        </div>
    <?php endif; ?>

</section>