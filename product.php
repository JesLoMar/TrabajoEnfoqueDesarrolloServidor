<?php
session_start();
require 'config/db.php';

//Almacenamos la ID del objeto
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    header("Location: index.php");
    exit;
}

$product = null;
$available_sizes = [];

//Consultas de los datos del objeto según su ID
try {
    $sql_product = "SELECT i.*, b.brand_name 
                    FROM items i 
                    JOIN brands b ON i.brand_id = b.brand_id 
                    WHERE i.item_id = :id";
    $stmt = $pdo->prepare($sql_product);
    $stmt->execute([':id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header("Location: index.php");
        exit;
    }

    $sql_stock = "SELECT s.size_id, s.size_name, inv.stock_quantity 
                FROM inventory inv 
                JOIN sizes s ON inv.size_id = s.size_id 
                WHERE inv.item_id = :id AND inv.stock_quantity > 0 
                ORDER BY s.size_id ASC";

    $stmt_stock = $pdo->prepare($sql_stock);
    $stmt_stock->execute([':id' => $product_id]);
    $available_sizes = $stmt_stock->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar producto.");
}
require 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/product.css">
<main class="product-container">
    <div class="product-gallery">
        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="main-image">
    </div>

    <div class="product-details">

        <div class="product-header">
            <span class="brand-tag"><?php echo htmlspecialchars($product['brand_name']); ?></span>
            <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="sku">SKU: <?php echo htmlspecialchars($product['sku']); ?></p>
        </div>

        <div class="product-price">
            <?php echo number_format($product['price'], 2); ?> €
        </div>

        <div class="product-description">
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        </div>

        <form action="actions/add_to_cart.php" method="POST" class="add-cart-form">
            <input type="hidden" name="item_id" value="<?php echo $product['item_id']; ?>">

            <div class="options-grid">
                <div class="option-group">
                    <div class="label-row">
                        <label for="size">Talla</label>
                        <button type="button" class="btn-size-guide" onclick="openModal()">Guía de Tallas</button>
                    </div>

                    <select name="size_id" id="size" required class="form-select">
                        <option value="">Selecciona tu talla</option>
                        <?php foreach ($available_sizes as $size): ?>
                            <option value="<?php echo $size['size_id']; ?>">
                                <?php echo htmlspecialchars($size['size_name']); ?>
                                <?php if ($size['stock_quantity'] < 3) echo " (¡Quedan pocas!)"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php if (empty($available_sizes)): ?>
                        <p class="out-of-stock">Producto agotado temporalmente</p>
                    <?php endif; ?>
                </div>

                <div class="option-group">
                    <label for="quantity">Cantidad</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-input"> <!-- Aquí tendré que establecer el máximo de artículos permitidos consultando en la base de datos cuál es el máximo actual. (No implementado actualmente) -->
                </div>
            </div>

            <button type="submit" class="btn-add-cart" <?php echo empty($available_sizes) ? 'disabled' : ''; ?>>
                Añadir al Carrito
            </button>
        </form>

        <div class="product-meta">
            <p>Envío gratuito en pedidos superiores a 100€</p>
            <p>Devoluciones gratuitas en 30 días</p>
        </div>
    </div>
</main>

<?php require 'includes/footer.php'; ?>
<!-- Modal a mostrar al clicar la guia de tallas -->
<div id="sizeModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <h2>Guía de Tallas</h2>
        <p>Utiliza esta tabla para encontrar tu talla perfecta.</p>

        <table class="size-guide-table">
            <thead>
                <tr>
                    <th>EU</th>
                    <th>US (Hombre)</th>
                    <th>US (Mujer)</th>
                    <th>CM</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>36</td>
                    <td>4.5</td>
                    <td>6</td>
                    <td>23.5</td>
                </tr>
                <tr>
                    <td>37</td>
                    <td>5</td>
                    <td>6.5</td>
                    <td>23.8</td>
                </tr>
                <tr>
                    <td>38</td>
                    <td>6</td>
                    <td>7.5</td>
                    <td>24.5</td>
                </tr>
                <tr>
                    <td>39</td>
                    <td>7</td>
                    <td>8.5</td>
                    <td>25.1</td>
                </tr>
                <tr>
                    <td>40</td>
                    <td>7.5</td>
                    <td>9</td>
                    <td>25.4</td>
                </tr>
                <tr>
                    <td>41</td>
                    <td>8.5</td>
                    <td>10</td>
                    <td>26.0</td>
                </tr>
                <tr>
                    <td>42</td>
                    <td>9</td>
                    <td>11</td>
                    <td>26.7</td>
                </tr>
                <tr>
                    <td>43</td>
                    <td>10</td>
                    <td>12</td>
                    <td>27.3</td>
                </tr>
                <tr>
                    <td>44</td>
                    <td>10.5</td>
                    <td>-</td>
                    <td>27.9</td>
                </tr>
                <tr>
                    <td>45</td>
                    <td>11.5</td>
                    <td>-</td>
                    <td>28.6</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Script simple para controlar la visibilidad del modal -->
<script>
    function openModal() {
        document.getElementById("sizeModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("sizeModal").style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == document.getElementById("sizeModal")) {
            closeModal();
        }
    }
</script>