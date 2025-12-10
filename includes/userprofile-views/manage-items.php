<?php
require_once 'config/db.php';
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
    echo "Error al cargar artículos.";
}
?>

<section class="main-mydata">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1 class="mydata-title">Gestión de Artículos</h1>
        <a href="index.php?var=user_profile&view=items" class="btn-save" style="padding: 5px 15px; text-decoration: none; font-size: 0.9em;">+ Añadir Nuevo</a>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
        <div class="alert-success" style="padding:10px; background:#d4edda; color:#155724; margin-bottom:15px; text-align:center;">
            Artículo actualizado correctamente.
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
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="text-mono"><?php echo $item['item_id']; ?></td>
                        <td>
                            <?php if ($item['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" style="width:40px; height:40px; object-fit:cover;">
                            <?php endif; ?>
                        </td>
                        <td style="font-weight:bold;"><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['brand_name']); ?></td>
                        <td class="text-mono"><?php echo htmlspecialchars($item['sku']); ?></td>
                        <td><?php echo number_format($item['price'], 2); ?> €</td>
                        <td>
                            <a href="index.php?var=user_profile&view=edit_item&id=<?php echo $item['item_id']; ?>"
                                class="btn-details" style="text-decoration:none; color:blue;">
                                Modificar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>