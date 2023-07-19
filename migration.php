<?php
require('./db.php');

// Получаем список файлов для миграций
function getMigrationFiles($conn) {
    // Находим папку с миграциями
    $sqlFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/');
    // Получаем список всех sql-файлов
    $allFiles = glob($sqlFolder . '*.sql');

    // Проверяем, есть ли таблица versions
    // Так как versions создается первой, то это равносильно тому, что база не пустая
    $query = 'SHOW TABLES FROM `' . DB_NAME . '` LIKE :table_name';
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':table_name', DB_TABLE_VERSIONS, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $firstMigration = count($data) === 0;

    // Первая миграция, возвращаем все файлы из папки sql
    if ($firstMigration) {
        return $allFiles;
    }

    // Ищем уже существующие миграции
    $versionsFiles = array();
    // Выбираем из таблицы versions все названия файлов
    $query = 'SELECT `name` FROM `' . DB_TABLE_VERSIONS . '`';
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Загоняем названия в массив $versionsFiles
    // Не забываем добавлять полный путь к файлу
    foreach ($data as $row) {
        array_push($versionsFiles, $sqlFolder . $row['name']);
    }

    // Возвращаем файлы, которых еще нет в таблице versions
    return array_diff($allFiles, $versionsFiles);
}

// Накатываем миграцию файла
function migrate($conn, $file) {
    // Читаем содержимое SQL-файла
    $sql = file_get_contents($file);
    
    // Запускаем транзакцию
    $conn->beginTransaction();
    
    try {
        // Выполняем SQL-запросы из файла
        $conn->exec($sql);
        
        // Вытаскиваем имя файла, отбросив путь
        $baseName = basename($file);
        
        // Формируем запрос для добавления миграции в таблицу versions
        $query = 'INSERT INTO `' . DB_TABLE_VERSIONS . '` (`name`) VALUES (:baseName)';
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':baseName', $baseName, PDO::PARAM_STR);
        $stmt->execute();
        
        // Фиксируем изменения в базе данных
        $conn->commit();
    } catch (PDOException $e) {
        // Откатываем транзакцию в случае ошибки
        $conn->rollBack();
        
        // Обрабатываем ошибку или выбрасываем исключение
        // в зависимости от требований вашего приложения
        echo 'Error: ' . $e->getMessage();
        // throw $e;
    }
}

// Подключаемся к базе
$conn = connectDB();

// Получаем список файлов для миграций за исключением тех, которые уже есть в таблице versions
$files = getMigrationFiles($conn);
 
// Проверяем, есть ли новые миграции
if (empty($files)) {
    echo 'Ваша база данных в актуальном состоянии.';
} else {
    echo 'Начинаем миграцию...<br><br>';
 
    // Накатываем миграцию для каждого файла
    foreach ($files as $file) {
        migrate($conn, $file);
        // Выводим название выполненного файла
        echo basename($file) . '<br>';
    }
 
    echo '<br>Миграция завершена.';    
}
?>
