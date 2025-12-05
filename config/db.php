<?php
$host = 'localhost';        // El servidor donde está la base de datos
$dbname = 'mydb';    // El nombre que le pondremos a tu base de datos (luego la creamos)
$user = 'root';             // El usuario dueño de la base de datos
$password = '';             // La contraseña (en XAMPP suele estar vacía)

try {
    // 2. Intentamos crear el "Puente" (La conexión PDO)
    // DSN (Data Source Name): Le decimos qué tipo de base de datos es (mysql), dónde está y el idioma (utf8)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    
    // Creamos la instancia PDO
    $pdo = new PDO($dsn, $user, $password);

    // 3. Configuración de seguridad y errores
    // ATTR_ERRMODE: Le decimos que si algo falla, lance una "Excepción" (un error fatal controlado)
    // para que no sigamos ejecutando código con una conexión rota.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configuración para que los datos que traigamos sean fáciles de leer (Array asociativo)
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // (Opcional) Si quieres ver si conecta, descomenta la siguiente línea:
    // echo "Conexión exitosa";

} catch (PDOException $e) {
    // 4. Si algo falla (ej. contraseña mal), caemos aquí (Catch)
    // Terminamos el script y mostramos el error técnico.
    die("Error de conexión: " . $e->getMessage());
}