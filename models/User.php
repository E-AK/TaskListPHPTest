<?php
class User {
    private $id;
    private $login;
    private $password;
    private $createdAt;

    /**
     * Конструктор класса User
     * @param int $id Идентификатор пользователя
     * @param string $login Логин пользователя
     * @param string $password Пароль пользователя
     * @param string $createdAt Дата и время создания пользователя
     */
    public function __construct($id, $login, $password, $createdAt) {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }

    /**
     * Получить идентификатор пользователя
     * @return int Идентификатор пользователя
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Получить логин пользователя
     * @return string Логин пользователя
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * Получить пароль пользователя
     * @return string Пароль пользователя
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Установить пароль пользователя
     * @param string $password Пароль пользователя
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Получить дату и время создания пользователя
     * @return string Дата и время создания пользователя
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }
}
?>
