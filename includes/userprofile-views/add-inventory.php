<?php
require_once 'config/db.php';

$items = [];
try {
    $stmt = $pdo->query("SELECT item_id, name, sku FROM items ORDER BY name ASC");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}

$sizes = [];
try {
    $stmt = $pdo->query("SELECT size_id, size_name FROM sizes ORDER BY size_id ASC");
    $sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
}
?>

<section class="main-mydata">
    <h1 class="mydata-title">Añadir Stock</h1>
    <h3 class="mydata-subtitle">Registrar unidades en el inventario</h3>
    <h3 class="mydata-subtitle"><a href="index.php?var=user_profile&view=inventory">Volver al inventario</a></h3>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'success'): ?>
            <div class="alert-success" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; text-align: center;">
                Stock actualizado correctamente.
            </div>
        <?php elseif ($_GET['status'] === 'error'): ?>
            <div class="alert-error" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; text-align: center;">
                Error al actualizar el inventario.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form action="actions/add_inventory.php" method="POST" class="add-item-form">
        
        <div class="form-row">
            <label for="item_id">Artículo:</label>
            <select id="item_id" name="item_id" required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; background: white; width: 100%;">
                <option value="">-- Seleccione un producto --</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?php echo $item['item_id']; ?>">
                        <?php echo htmlspecialchars($item['name']); ?> (SKU: <?php echo htmlspecialchars($item['sku']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if(empty($items)): ?>
                <small style="color: red;">No hay productos creados. <a href="index.php?var=user_profile&view=items">Crea uno aquí</a>.</small>
            <?php endif; ?>
        </div>

        <div class="form-row">
            <label for="size_id">Talla:</label>
            <select id="size_id" name="size_id" required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; background: white; width: 100%;">
                <option value="">-- Seleccione talla --</option>
                <?php foreach ($sizes as $size): ?>
                    <option value="<?php echo $size['size_id']; ?>">
                        <?php echo htmlspecialchars($size['size_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <label for="quantity">Cantidad a añadir:</label>
            <input id="quantity" name="quantity" type="number" min="1" required placeholder="Ej: 10" style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%;">
        </div>

        <button type="submit" class="btn-save">Añadir stock</button>
    </form>
</section>