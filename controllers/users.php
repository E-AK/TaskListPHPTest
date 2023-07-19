<?php
require('../models/user.php'); // Подключаем файл с классом User
require('../services/users.php'); // Подключаем файл с функциями для работы с пользователями

session_start();

// Проверяем метод запроса
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Проверяем тип операции
        switch ($_POST['operation']) {
            case 'create':
                // Создание нового пользователя
                $login = htmlspecialchars($_POST['login']);
                $password = htmlspecialchars($_POST['password']);

                $user = new User(null, $login, $password, null);
                try {
                    $createdUserId = createUser($user);

                    echo json_encode(['status' => 'success', 'user_id' => $createdUserId]);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'delete':
                // Удаление пользователя
                $userId = htmlspecialchars($_POST['user_id']);

                try {
                    deleteUser($userId);
                    echo json_encode(['status' => 'success']);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'update':
                // Обновление информации о пользователе
                $userId = htmlspecialchars($_POST['user_id']);
                $login = htmlspecialchars($_POST['login']);
                $password = htmlspecialchars($_POST['password']);

                $user = new User($userId, $login, $password, null);
                try {
                    updateUser($user);
                    echo json_encode(['status' => 'success']);
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
                $login = htmlspecialchars($_GET['login']);

                try {
                    $user = getUser($login);
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
