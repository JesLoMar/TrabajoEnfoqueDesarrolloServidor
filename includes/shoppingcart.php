<?php
require_once 'config/db.php';

$cart_items = $_SESSION['cart'] ?? [];
$products_details = [];
$total_price = 0;

if (!empty($cart_items)) {
    foreach ($cart_items as $key => $qty) {
        list($item_id, $size_id) = explode('_', $key);

        try {
            $sql = "SELECT i.item_id, i.name, i.price, i.image_url, b.brand_name, s.size_name 
                    FROM items i
                    JOIN brands b ON i.brand_id = b.brand_id
                    JOIN sizes s ON s.size_id = :size_id
                    WHERE i.item_id = :item_id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':item_id' => $item_id, ':size_id' => $size_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $subtotal = $product['price'] * $qty;
                $total_price += $subtotal;
                $products_details[] = [
                    'key'       => $key,
                    'name'      => $product['name'],
                    'brand'     => $product['brand_name'],
                    'price'     => $product['price'],
                    'image'     => $product['image_url'],
                    'size'      => $product['size_name'],
                    'qty'       => $qty,
                    'subtotal'  => $subtotal
                ];
            }
        } catch (PDOException $e) {
        }
    }
}
?>

<link rel="stylesheet" href="assets/css/cart.css">

<main class="cart-container">
    <h1>Tu Carrito de Compra</h1>
<?php if (isset($_GET['error'])): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 5px; text-align: center;">
        <?php 
            if ($_GET['error'] === 'checkout_failed') {
                echo "❌ Error al procesar el pedido. Puede que algún producto se haya quedado sin stock.";
            } else {
                echo "❌ Ha ocurrido un error desconocido.";
            }
        ?>
    </div>
<?php endif; ?>
    <?php if (empty($products_details)): ?>
        <div class="empty-cart">
            <p>Tu carrito está vacío.</p>
            <a href="index.php" class="btn-continue">Volver a la tienda</a>
        </div>
    <?php else: ?>

        <div class="cart-layout">
            <div class="cart-items">
                
                <div class="cart-header">
                    <span>Producto</span>
                    <span>Precio</span>
                    <span>Cantidad</span>
                    <span>Total</span>
                    <span></span> </div>

                <?php foreach ($products_details as $item): ?>
                    <div class="cart-row">
                        <div class="cart-product-info">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Foto">
                            <div>
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="meta"><?php echo htmlspecialchars($item['brand']); ?> | Talla: <?php echo htmlspecialchars($item['size']); ?></p>
                            </div>
                        </div>

                        <div class="cart-price">
                            <?php echo number_format($item['price'], 2); ?> €
                        </div>

                        <div class="cart-qty">
                            <input type="number" value="<?php echo $item['qty']; ?>" readonly class="qty-input">
                        </div>

                        <div class="cart-subtotal">
                            <?php echo number_format($item['subtotal'], 2); ?> €
                        </div>

                        <div class="cart-action">
                            <a href="actions/remove_from_cart.php?key=<?php echo $item['key']; ?>" class="btn-remove" title="Eliminar">&times;</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h2>Resumen</h2>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span><?php echo number_format($total_price, 2); ?> €</span>
                </div>
                <div class="summary-row">
                    <span>Envío</span>
                    <span>Gratis</span>
                </div>
                <hr>
                <div class="summary-row total">
                    <span>Total</span>
                    <span><?php echo number_format($total_price, 2); ?> €</span>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="actions/create_order.php" class="btn-checkout">Finalizar Compra</a>
                <?php else: ?>
                    <a href="index.php?var=login" class="btn-checkout">Inicia sesión para comprar</a>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>
</main>