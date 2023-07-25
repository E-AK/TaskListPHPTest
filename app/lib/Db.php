<?php

namespace app\lib;

use PDO;

/**
 * Класс Db представляет класс для работы с базой данных.
 */
class Db
{
    protected $db;

    /**
     * Создает соединение с базой данных, используя параметры из файла.
     */
    public function __construct()
    {
        $config = require 'app/config/db.php';
        $this->db = new PDO(
            'mysql:host=' . $config['host'] . ';dbname=' . $config['name'],
            $config['user'],
            $config['password']
        );
    }

    /**
     * Выполняет запрос к базе данных.
     * @param string $sql SQL-запрос.
     * @param array $params Параметры запроса.
     * @return PDOStatement Возвращает объект PDOStatement с результатами запроса.
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            foreach ($params as $key => $val) {
                $stmt->bindValue(':' . $key, $val);
            }
        }
        
        $stmt->execute();

        return $stmt;
    }

    /**
     * Возвращает все строки результирующего набора запроса.
     * @param string $sql SQL-запрос.
     * @param array $params Параметры запроса.
     * @return array Возвращает ассоциативный массив с результатами запроса.
     */
    public function row($sql, $params = [])
    {
        $result = $this->query($sql, $params);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Возвращает значение первой колонки первой строки результирующего набора запроса.
     * @param string $sql SQL-запрос.
     * @param array $params Параметры запроса.
     * @return mixed Возвращает значение первой колонки первой строки результата запроса.
     */
    public function column($sql, $params = [])
    {
        $result = $this->query($sql, $params);

        return $result->fetchColumn();
    }
}
?>