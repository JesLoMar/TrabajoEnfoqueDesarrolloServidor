<?php
require_once 'config/db.php';
//En esta vista mostramos todos los objetos y permitimos modificar cada uno individualmente a través de "edit-item.php".
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    echo "<div class='alert-error'>Acceso denegado.</div>";
    return;
}
$items = [];
try {
    $sql = "SELECT i.*, b.brand_name 
            FROM items i 
            LEFT JOIN brands b ON i.brand_id = b.brand_id 
            ORDER BY i.item_id DESC";
    $stmt = $pdo->query($sql);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert-error'>Error al cargar artículos.</div>";
}
?>
<section class="main-mydata">
    <div>
        <h1 class="mydata-title">Gestión de Artículos</h1>
        <a href="index.php?var=user_profile&view=items" class="btn-save">+ Añadir Nuevo</a>
    </div>
    <?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
        <div class="alert-success">
            Artículo actualizado correctamente.
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['status']) && $_GET['status'] === 'deleted'): ?>
        <div class="alert-success" style="background:#f8d7da; color:#721c24; border-color:#f5c6cb;">
            Artículo eliminado correctamente.
        </div>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>SKU</th>
                    <th>Precio</th>
                    <th class="text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="text-mono">#<?php echo $item['item_id']; ?></td>
                        <td>
                            <?php if ($item['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="img-thumbnail-sm">
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['brand_name']); ?></td>
                        <td class="text-mono"><?php echo htmlspecialchars($item['sku']); ?></td>
                        <td><?php echo number_format($item['price'], 2); ?> €</td>
                        <td class="text-center">
                            <div style="display: flex; justify-content: center; gap: 10px;">
                                <a href="index.php?var=user_profile&view=edit_item&id=<?php echo $item['item_id']; ?>"
                                    class="btn-details">
                                    Modificar
                                </a>
                                <form action="actions/delete_item.php" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de borrar este producto?\n\nSe eliminará también del stock.\nEsta acción es irreversible.');"
                                    style="margin: 5;">
                                    <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                    <button type="submit" class="btn-details"
                                        style="border-color: #dc3545; color: #dc3545;">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>