<?php
require('../models/User.php'); // Подключаем файл с классом User
require('../services/users.php'); // Подключаем файл с функциями для работы с пользователями

session_start();

// Проверяем метод запроса
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Проверяем тип операции
        switch ($_POST['operation']) {
            case 'create':
                // Создание нового пользователя
                $login = $_POST['login'];
                $password = $_POST['password'];

                $user = new User(null, $login, $password, null);
                try {
                    $createdUserId = createUser($user);

                    echo json_encode(['status' => 'success', 'user_id' => $createdUserId]);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid operation']);
                break;
        }
        break;

    case 'GET':
        // Проверяем тип операции
        switch ($_GET['operation']) {
            case 'get':
                // Получение пользователя по логину
                $login = $_GET['login'];

                try {
                    $user = getUserByLogin($login);

                    if ($user) {
                        echo json_encode(['status' => 'success', 'user' => $user]);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'User not found']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid operation']);
                break;
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Unsupported request method']);
        break;
}
?>
