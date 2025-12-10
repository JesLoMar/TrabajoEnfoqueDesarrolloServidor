<?php
require_once 'config/db.php';

$brands = [];
try {
    $stmt = $pdo->query("SELECT brand_id, brand_name FROM brands ORDER BY brand_name ASC");
    $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
}
?>

<form action="actions/add_item.php" method="POST" class="add-item-form">
    <h3>Introduzca los valores para añadir un nuevo artículo</h3>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'success'): ?>
            <div class="alert-success" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; text-align: center;">
                Objeto añadido.
            </div>
        <?php elseif ($_GET['status'] === 'error' && isset($_SESSION['errores_update'])): ?>
            <div class="alert-error" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                <?php foreach ($_SESSION['errores_update'] as $error): ?>
                    <p style="margin: 0; padding: 2px;">Error <?php echo $error; ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['errores_update']); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="form-row">
        <label for="name">Nombre:</label>
        <input id="name" name="name" type="text" required>
    </div>

    <div class="form-row">
        <label for="brand">Marca del producto:</label>
        <select id="brand" name="brand_id" required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; background: white;">
            <option value="">-- Seleccione una marca --</option>
            <?php foreach ($brands as $brand): ?>
                <option value="<?php echo $brand['brand_id']; ?>">
                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (empty($brands)): ?>
            <small style="color: red;">No hay marcas creadas en la base de datos.</small>
        <?php endif; ?>
    </div>

    <div class="form-row">
        <label for="sku">SKU:</label>
        <input id="sku" name="sku" type="text" required>
    </div>

    <div class="form-row">
        <label for="colorway">Colorway:</label>
        <input id="colorway" name="colorway" type="text">
    </div>

    <div class="form-row">
        <label for="description">Descripción:</label>
        <input id="description" name="description" type="text" required>
    </div>

    <div class="form-row">
        <label for="price">Precio:</label>
        <input id="price" name="price" type="number" step="0.01" required>
    </div>

    <div class="form-row">
        <label for="gender">Género:</label>
        <select id="gender" name="gender" required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; background: white;">
            <option value="">-- Seleccione --</option>
            <option value="Men">Hombre</option>
            <option value="Women">Mujer</option>
            <option value="Unisex">Unisex</option>
            <option value="Kids">Niño</option>
        </select>
    </div>

    <div class="form-row">
        <label for="image_url">URL de la imagen:</label>
        <input id="image_url" name="image_url" type="text" required>
    </div>

    <button type="submit" class="btn-save">Añadir objeto</button>

    <a href="index.php?var=user_profile&view=manage_items" class="btn-save" style="background-color: #6c757d; text-decoration: none; text-align: center;">
        Ir a Gestión de Artículos
    </a>
</form>