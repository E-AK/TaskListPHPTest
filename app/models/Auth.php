<?php
namespace app\models;

use app\core\Model;
use Exception;

class Auth extends Model {
    public $id;
    public $login;
    public $password;
    public $createdAt;

    /**
     * Получить пользователя по логину.
     *
     * @param string $login Логин пользователя.
     * @return Auth|false Объект пользователя или false, если пользователь не найден.
     * @throws Exception В случае ошибки при выполнении запроса.
     */
    public function getUserByLogin(string $login) {
        $result = $this->db->row("SELECT * FROM persons WHERE login =\"" . $login . "\"");

        if ($result) {
            // Создаем объект Auth и заполняем его данными из результата запроса.
            $auth = new Auth;
            $auth->id = $result[0]['id'];
            $auth->login = $result[0]['login'];
            $auth->password = $result[0]['password'];
            $auth->createdAt = $result[0]['created_at'];

            return $auth;
        }

        return false;
    }

    /**
     * Создание нового пользователя.
     *
     * @param string $login Логин пользователя.
     * @param string $password Пароль пользователя.
     * @return int|false Идентификатор нового пользователя или false в случае ошибки.
     */
    public function createUser(string $login, string $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);



        $result = $this->db->query("INSERT INTO persons (login, password) VALUES (\"" . $login . "\", \"" . $password . "\")");

        if ($result) {
            // Возвращаем идентификатор нового пользователя.
            return $this->getUserByLogin($login);
        }

        return false;
    }

    /**
     * Аутентификация пользователя по логину и паролю.
     * @param string $login Логин пользователя.
     * @param string $password Пароль пользователя.
     * @return Auth|false Возвращает объект пользователя в случае успешной аутентификации, иначе - false.
     */
    public function authentication(string $login, string $password) {
        $auth = $this->getUserByLogin($login);

        if ($auth) {
            return password_verify($password, $auth->password);
        }

        return null;
    }
}
?>
