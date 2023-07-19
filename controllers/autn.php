<?php
require('../models/user.php');
require('../services/users.php');

session_start();

// Проверяем метод запроса
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Проверяем тип операции
        switch ($_POST['operation']) {
            case 'auth':
                $login = htmlspecialchars($_POST['login']);
                $password = htmlspecialchars($_POST['password']);

                $user = new User(null, $login, $password, null);

                if (isset($_SESSION['user_id'])) {
                    echo json_encode(['status' => 'error', 'message' => 'The user is already logged in']);
                    break;
                }

                try {
                    $authentication = authentication($login, $password);

                    if ($authentication) {
                        // Записываем userId в сессию в зашифрованном виде base64
                        $_SESSION['user_id'] = base64_encode($authentication);
                    } elseif ($authentication === null) {
                        try {
                            $createdUserId = createUser($user);

                            // Записываем userId в сессию в зашифрованном виде base64
                            $_SESSION['user_id'] = base64_encode($createdUserId);

                            echo json_encode(['status' => 'success', 'user_id' => $createdUserId]);
                        } catch (Exception $e) {
                            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                        }
                    } else {
                        echo json_encode(['status' => 'success', 'message' => 'Invalid username or password']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                    break;
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
