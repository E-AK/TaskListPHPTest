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
        $login = $_POST['login'];
        $password = $_POST['password'];

        // Выполняем аутентификацию
        $result = $this->model->authentication($login, $password);

        if ($result) {
            $result = $this->model->getUserByLogin($login);
            // Записываем userId в сессию
            $_SESSION['user_id'] = $result->id;

            $this->view->message('success', "Авторизация успешна");
            return;
        } 
        
        try {
            // Пытаемся создать нового пользователя
            $createdUser = $this->model->createUser($login, $password);

            $_SESSION['user_id'] = $createdUser->id;

            $this->view->message('success', "Регистрация успешна");
        } catch (PDOException $e) {
            $this->view->message('error', "Ошибка при регистрации пользователя");
        }
    }
}
?>
