<?php
require_once 'config/db.php';
$products = [];

//Detecta filtros por URL 
$brand_filter = isset($_GET['brand']) ? $_GET['brand'] : null;
$title_text = "Colección Exclusiva";
$subtitle_text = "Descubre las últimas tendencias en calzado premium.";
// Consulta base de todos los productos y si hay filtro de url modifica los textos y la consulta
try {
    $sql = "SELECT i.item_id, i.name, i.price, i.image_url, b.brand_name 
            FROM items i 
            JOIN brands b ON i.brand_id = b.brand_id";
    if ($brand_filter) {
        $sql .= " WHERE b.brand_name = :brand";
        $title_text = "Colección " . htmlspecialchars($brand_filter);
        $subtitle_text = "Explora nuestros modelos de " . htmlspecialchars($brand_filter);
    }
    $sql .= " ORDER BY i.price DESC";
    $stmt = $pdo->prepare($sql);
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