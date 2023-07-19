<?php
// Объявляем нужные константы
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'tasklist');
define('DB_TABLE_VERSIONS', 'versions');

/**
* Подключение к базе данных.
*
* @return PDO Соединение с базой данных
* @throws Exception в случае ошибки подключения
*/
function connectDB() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $conn = new PDO($dsn, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    } catch(PDOException $e) {
        echo $e->getMessage();
        throw new Exception("Connection failed: " . $e->getMessage());
    }
}
?>