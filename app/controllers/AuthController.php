<?php
namespace app\controllers;

use app\core\Controller;

class AuthController extends Controller {
    /**
     * Отображение страницы с формой авторизации.
     */
    public function indexAction() {
        $this->view->render('Login', []);
    }

    /**
     * Обработка действия авторизации.
     */
    public function authAction() {
        // Проверяем, авторизован ли пользователь
        if (isset($_SESSION['user_id'])) {
            $this->view->message('success', "Пользователь уже авторизован");
            return;
        }

        // Получаем логин и пароль
        $login = htmlspecialchars($_POST['login']);
        $password = htmlspecialchars($_POST['password']);

        // Выполняем аутентификацию
        $result = $this->model->authentication($login, $password);

        if ($result) {
            $result = $this->model->getUserByLogin($login);
            // Записываем userId в сессию
            $_SESSION['user_id'] = $result->id;

            $this->view->message('success', "Авторизация успешна");
        } elseif ($result === null) {
            // Создаем нового пользователя
            $createdUser = $this->model->createUser($login, $password);

            if ($createdUser) {
                // Записываем userId в сессию в зашифрованном виде base64
                $_SESSION['user_id'] = $createdUser->id;

                $this->view->message('success', "Регистрация успешна");
            } else {
                $this->view->message('error', "Ошибка при регистрации пользователя");
            }
        } else {
            $this->view->message('error', "Неверные логин или пароль");
        }
    }
}
?>
