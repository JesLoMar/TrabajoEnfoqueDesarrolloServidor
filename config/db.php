<?php
// Seteamos las variables para la base de datos.
$host = getenv('MYSQL_ADDON_HOST');
$dbname = getenv('MYSQL_ADDON_DB');
$user = getenv('MYSQL_ADDON_USER');
$password = getenv('MYSQL_ADDON_PASSWORD');
$port = getenv('MYSQL_ADDON_PORT');


try { //Intentamos establecer conexión a BD en un bloque de seguridad.
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4"; //Definimos la dirección de la BD.
    $pdo = new PDO($dsn, $user, $password); //Creamos la llave para la comunicación con la BD.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Obliga a lanzar errores si algo sale mal.
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //Definimos devolución de datos limpios de la BD.
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}