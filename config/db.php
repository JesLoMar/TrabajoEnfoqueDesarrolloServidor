<?php
// Seteamos las variables para la base de datos.
$host = 'localhost';
$dbname = 'mydb';
$user = 'root';
$password = '';


try { //Intentamos establecer conexión a BD en un bloque de seguridad.
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4"; //Definimos la dirección de la BD.
    $pdo = new PDO($dsn, $user, $password); //Creamos la llave para la comunicación con la BD.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Obliga a lanzar errores si algo sale mal.
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //Definimos devolución de datos limpios de la BD.
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}