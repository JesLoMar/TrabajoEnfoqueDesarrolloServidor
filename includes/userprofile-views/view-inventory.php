<?php
if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    echo "<div class='alert-error'>Acceso denegado.</div>";
    exit;
}

require_once 'config/db.php';

$inventory = [];

try {
    $sql = "SELECT 
                inv.inventory_id,
                inv.stock_quantity,
                it.name AS item_name,
                it.brand_id,
                it.sku,
                it.gender,
                sz.size_name,
                b.brand_name
            FROM inventory inv
            INNER JOIN items it ON inv.item_id = it.item_id
            INNER JOIN sizes sz ON inv.size_id = sz.size_id
            LEFT JOIN brands b ON it.brand_id = b.brand_id
            ORDER BY it.name ASC, sz.size_id ASC";
    $stmt = $pdo->query($sql);
    $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert-error'>Error al cargar el inventario: " . $e->getMessage() . "</div>";
}
?>
<section class="main-mydata">
    <h1 class="mydata-title">Inventario</h1>
    <h3 class="mydata-subtitle">Gestión de stock por talla y producto</h3>

    <div class="table-header-actions">
        <a href="index.php?var=user_profile&view=add_inventory" class="btn-save">
            + Nuevo Stock
        </a>
    </div>

    <?php if (empty($inventory)): ?>
        <div class="empty-state">
            <p>No hay stock registrado en el inventario.</p>
            <br>
            <a href="index.php?var=user_profile&view=add_inventory" class="btn-details">Añadir Stock Inicial</a>
        </div>
    <?php else: ?>
        
        <div class="table-responsive">
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Marca</th>
                        <th>Género</th>
                        <th class="text-center">Talla</th>
                        <th class="text-center">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $row): ?>
                        <tr>
                            <td class="fw-bold">
                                <?php echo htmlspecialchars($row['item_name']); ?>
                            </td>

                            <td class="text-mono text-muted">
                                <?php echo htmlspecialchars($row['sku']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['brand_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['gender']); ?>
                            </td>

                            <td class="text-center">
                                <span class="badge badge-size">
                                    <?php echo htmlspecialchars($row['size_name']); ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <?php 
                                    $stockClass = ($row['stock_quantity'] < 5) ? 'stock-low' : 'stock-ok'; 
                                ?>
                                <span class="<?php echo $stockClass; ?>">
                                    <?php echo $row['stock_quantity']; ?> uds.
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</section>