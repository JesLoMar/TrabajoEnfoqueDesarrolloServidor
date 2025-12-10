<?php
require_once 'config/db.php';
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) return;
if (!isset($_GET['id'])) {
    echo "ID de producto no especificado.";
    return;
}

$id = (int)$_GET['id'];
$item = null;
$brands = [];

try {
    $stmtItem = $pdo->prepare("SELECT * FROM items WHERE item_id = :id");
    $stmtItem->execute([':id' => $id]);
    $item = $stmtItem->fetch(PDO::FETCH_ASSOC);

    $stmtBrands = $pdo->query("SELECT brand_id, brand_name FROM brands ORDER BY brand_name ASC");
    $brands = $stmtBrands->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error de BD.";
}

if (!$item) {
    echo "Producto no encontrado.";
    return;
}
?>

<form action="actions/update_item.php" method="POST" class="add-item-form">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h3>Editar Artículo #<?php echo $id; ?></h3>
        <a href="index.php?var=user_profile&view=manage_items" style="color:#666; text-decoration:none;">&laquo; Volver</a>
    </div>

    <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">

    <?php if (isset($_GET['status']) && $_GET['status'] === 'error' && isset($_SESSION['errores_update'])): ?>
        <div class="alert-error" style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px;">
            <?php foreach ($_SESSION['errores_update'] as $error) echo "<p>$error</p>"; ?>
            <?php unset($_SESSION['errores_update']); ?>
        </div>
    <?php endif; ?>

    <div class="form-row">
        <label>Nombre:</label>
        <input name="name" type="text" required value="<?php echo htmlspecialchars($item['name']); ?>">
    </div>

    <div class="form-row">
        <label>Marca:</label>
        <select name="brand_id" required style="padding:10px; border:1px solid #ccc; width:100%;">
            <?php foreach ($brands as $brand): ?>
                <option value="<?php echo $brand['brand_id']; ?>" 
                    <?php echo ($brand['brand_id'] == $item['brand_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-row">
        <label>SKU:</label>
        <input name="sku" type="text" required value="<?php echo htmlspecialchars($item['sku']); ?>">
    </div>

    <div class="form-row">
        <label>Colorway:</label>
        <input name="colorway" type="text" value="<?php echo htmlspecialchars($item['colorway']); ?>">
    </div>

    <div class="form-row">
        <label>Descripción:</label>
        <input name="description" type="text" required value="<?php echo htmlspecialchars($item['description']); ?>">
    </div>

    <div class="form-row">
        <label>Precio:</label>
        <input name="price" type="number" step="0.01" required value="<?php echo $item['price']; ?>">
    </div>

    <div class="form-row">
        <label>Género:</label>
        <select name="gender" required style="padding:10px; border:1px solid #ccc; width:100%;">
            <option value="Men" <?php echo ($item['gender'] == 'Men') ? 'selected' : ''; ?>>Hombre</option>
            <option value="Women" <?php echo ($item['gender'] == 'Women') ? 'selected' : ''; ?>>Mujer</option>
            <option value="Unisex" <?php echo ($item['gender'] == 'Unisex') ? 'selected' : ''; ?>>Unisex</option>
            <option value="Kids" <?php echo ($item['gender'] == 'Kids') ? 'selected' : ''; ?>>Niño</option>
        </select>
    </div>

    <div class="form-row">
        <label>URL Imagen:</label>
        <input name="image_url" type="text" required value="<?php echo htmlspecialchars($item['image_url']); ?>">
    </div>

    <button type="submit" class="btn-save">Guardar Cambios</button>
</form>