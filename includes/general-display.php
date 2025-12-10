<?php
// includes/main-shop.php

require_once 'config/db.php'; // Aseguramos conexión

$products = [];

// 1. Detectamos si hay un filtro de marca en la URL
$brand_filter = isset($_GET['brand']) ? $_GET['brand'] : null;

// Textos por defecto
$title_text = "Colección Exclusiva";
$subtitle_text = "Descubre las últimas tendencias en calzado premium.";

try {
    // Consulta base: Productos + Marca
    $sql = "SELECT i.item_id, i.name, i.price, i.image_url, b.brand_name 
            FROM items i 
            JOIN brands b ON i.brand_id = b.brand_id";
    
    // 2. Si hay filtro, modificamos la SQL y los textos
    if ($brand_filter) {
        $sql .= " WHERE b.brand_name = :brand";
        $title_text = "Colección " . htmlspecialchars($brand_filter);
        $subtitle_text = "Explora nuestros modelos de " . htmlspecialchars($brand_filter);
    }

    // Ordenamos siempre por precio descendente
    $sql .= " ORDER BY i.price DESC";
    
    $stmt = $pdo->prepare($sql);

    // 3. Ejecutamos pasando el parámetro si existe
    if ($brand_filter) {
        $stmt->execute([':brand' => $brand_filter]);
    } else {
        $stmt->execute();
    }

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<div class='alert-error'>Error al cargar el catálogo.</div>";
}
?>

<style>
    /* Estilos específicos para la tienda principal */
    .shop-hero {
        text-align: center;
        padding: 40px 20px;
        background-color: #f8f9fa;
        margin-bottom: 30px;
        border-bottom: 1px solid #e9ecef;
    }

    .shop-hero h1 {
        margin: 0;
        font-size: 2.5em;
        color: #333;
    }

    .shop-hero p {
        color: #666;
        margin-top: 10px;
        font-size: 1.1em;
    }

    .shop-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 50px 20px;
    }

    .product-grid {
        display: grid;
        /* Grid responsivo: crea tantas columnas como quepan de mín 250px */
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 30px;
    }

    .product-card {
        background: white;
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        text-decoration: none; /* Quitar subrayado del enlace */
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%; /* Para igualar alturas */
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #ddd;
    }

    .card-image-container {
        width: 100%;
        height: 250px; /* Altura fija para la imagen */
        background-color: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .card-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Recorta la imagen para llenar el hueco sin deformar */
        transition: transform 0.3s ease;
    }

    .product-card:hover .card-image-container img {
        transform: scale(1.05);
    }

    .card-details {
        padding: 15px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
    }

    .card-brand {
        font-size: 0.85em;
        text-transform: uppercase;
        color: #888;
        letter-spacing: 1px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .card-title {
        font-size: 1.1em;
        font-weight: bold;
        color: #333;
        margin: 0 0 10px 0;
        line-height: 1.3;
    }

    .card-price {
        font-size: 1.25em;
        color: #000000e1;
        font-weight: bold;
        margin-top: auto;
    }

    .empty-shop {
        text-align: center;
        padding: 50px;
        color: #666;
        grid-column: 1 / -1;
    }
    
    /* Estilo botón limpiar filtros */
    .btn-clear-filter {
        display: inline-block;
        margin-top: 15px;
        text-decoration: none;
        color: #007bff;
        border: 1px solid #007bff;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9em;
        transition: background 0.3s, color 0.3s;
    }
    .btn-clear-filter:hover {
        background-color: #007bff;
        color: white;
    }
</style>

<section class="shop-hero">
    <h1><?php echo $title_text; ?></h1>
    <p><?php echo $subtitle_text; ?></p>
    
    <?php if ($brand_filter): ?>
        <a href="index.php" class="btn-clear-filter">
            &times; Ver todas las marcas
        </a>
    <?php endif; ?>
</section>

<div class="shop-container">
    
    <div class="product-grid">
        
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $item): ?>
                
                <a href="product.php?id=<?php echo $item['item_id']; ?>" class="product-card">
                    
                    <div class="card-image-container">
                        <?php if (!empty($item['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <?php else: ?>
                            <img src="assets/img/no-image.png" alt="Sin imagen" style="object-fit: contain; padding: 20px;">
                        <?php endif; ?>
                    </div>

                    <div class="card-details">
                        <div>
                            <div class="card-brand"><?php echo htmlspecialchars($item['brand_name']); ?></div>
                            <h3 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                        </div>
                        <div class="card-price">
                            <?php echo number_format($item['price'], 2); ?> €
                        </div>
                    </div>

                </a>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-shop">
                <h3>No hay productos disponibles para esta selección.</h3>
                <p>Intenta con otra marca o vuelve más tarde.</p>
                <?php if ($brand_filter): ?>
                    <a href="index.php" class="btn-clear-filter" style="margin-top: 20px;">Ver todo el catálogo</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>