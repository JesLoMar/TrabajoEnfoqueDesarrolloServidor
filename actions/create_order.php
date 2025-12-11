<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: ../index.php");
    exit;
}
//Traemos datos de envío de la sesión del usuario.
$address  = trim($_SESSION['address'] ?? '');
$city     = trim($_SESSION['city'] ?? '');
$zip_code = trim($_SESSION['zip_code'] ?? '');
//Si algo está vacio mostramos error.
if (empty($address) || empty($city) || empty($zip_code)) {
    header("Location: ../index.php?var=user_profile&view=my_data&status=missing_address");
    exit;
}
try {
    $pdo->beginTransaction(); //Iniciamos transacción para que si algo falla se cancele todo.
    $user_id = $_SESSION['user_id'];
    $cart = $_SESSION['cart'];
    $total_order_price = 0;
    $shipping_address = $_SESSION['address'] . ", " . $_SESSION['city'] . ", " . $_SESSION['zip_code'];
    $verified_items = [];
    foreach ($cart as $key => $quantity) {
        list($item_id, $size_id) = explode('_', $key);
        //Comprobamos que hay suficientes artículos en el stock antes de comenzar el pedido como tal.
        $sql_check = "SELECT i.price, s.size_name, inv.stock_quantity 
                    FROM items i
                    JOIN sizes s ON s.size_id = :size_id
                    JOIN inventory inv ON inv.item_id = i.item_id AND inv.size_id = s.size_id
                    WHERE i.item_id = :item_id";
        $stmt = $pdo->prepare($sql_check);
        $stmt->execute([':item_id' => $item_id, ':size_id' => $size_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        //Si algo falla muestra excepciones relacionadas.
        if (!$data) {
            throw new Exception("Uno de los productos ya no existe.");
        }
        if ($data['stock_quantity'] < $quantity) {
            throw new Exception("No hay suficiente stock para el producto seleccionado.");
        }
        //Calculamos precio y confirmamos que los articulos estan disponibles.
        $unit_price = $data['price'];
        $total_order_price += ($unit_price * $quantity);
        $verified_items[] = [
            'item_id'    => $item_id,
            'size_id'    => $size_id,
            'size_name'  => $data['size_name'],
            'quantity'   => $quantity,
            'unit_price' => $unit_price
        ];
    }
    //Añadimos una nueva orden con los valores calculados por el paso anterior.
    $sql_order = "INSERT INTO orders (user_id, order_price, order_shipping_address, order_state, order_time) 
                VALUES (:uid, :price, :address, 1, NOW())";
    $stmt_order = $pdo->prepare($sql_order);
    $stmt_order->execute([
        ':uid'     => $user_id,
        ':price'   => $total_order_price,
        ':address' => $shipping_address
    ]);

    $order_id = $pdo->lastInsertId();
    $sql_item_insert = "INSERT INTO order_items (order_id, item_id, quantity, unit_price, size) 
                        VALUES (:oid, :iid, :qty, :price, :size)";
    //Modificamos el inventario según los objetos comprados.
    $sql_inventory_update = "UPDATE inventory SET stock_quantity = stock_quantity - :qty 
                            WHERE item_id = :iid AND size_id = :sid";
    $stmt_item = $pdo->prepare($sql_item_insert);
    $stmt_inv  = $pdo->prepare($sql_inventory_update);
    foreach ($verified_items as $item) {
        $stmt_item->execute([
            ':oid'   => $order_id,
            ':iid'   => $item['item_id'],
            ':qty'   => $item['quantity'],
            ':price' => $item['unit_price'],
            ':size'  => $item['size_id']
        ]);
        $stmt_inv->execute([
            ':qty' => $item['quantity'],
            ':iid' => $item['item_id'],
            ':sid' => $item['size_id']
        ]);
    }
    $pdo->commit();//Si para entonces nada ha fallado lo enviamos a la BD.
    unset($_SESSION['cart']);//Limpiamos el carrito.
    header("Location: ../index.php?var=user_profile&view=orders&status=success_order");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("ERROR DETECTADO: " . $e->getMessage());
}
