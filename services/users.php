<?php
require('../db.php');

/**
 * Создание нового пользователя
 *
 * @param User $user Объект пользователя
 * @return int Идентификатор нового пользователя
 * @throws Exception В случае ошибки при выполнении запроса
 */
function createUser(User $user): int
{
    $conn = connectDB();

    // Хеширование пароля
    $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);

    try {
        $query = "INSERT INTO persons (login, password) VALUES (:login, :password)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':login', $user->getLogin(), PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->execute();

        $userId = $conn->lastInsertId();

        return $userId;
    } catch (PDOException $e) {
        throw new Exception("Ошибка при создании пользователя: " . $e->getMessage());
    }
}

/**
 * Получить пользователя по логину
 *
 * @param string $login Логин пользователя
 * @return User|false Объект пользователя или false, если пользователь не найден
 * @throws Exception В случае ошибки при выполнении запроса
 */
function getUserByLogin(string $login)
{
    $conn = connectDB();

    try {
        $query = "SELECT * FROM persons WHERE login = :login";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();

        // Получение результата и создание объекта User
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $user = new User($result['id'], htmlspecialchars($result['login']), null, $result['created_at']);

            return $user;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        throw new Exception("Ошибка при получении пользователя: " . $e->getMessage());
    }
}

/**
 * Аутентификация пользователя
 *
 * @param string $login Логин пользователя
 * @param string $password Пароль пользователя
 * @return int|false Идентификатор пользователя или false, если аутентификация не удалась
 * @throws Exception В случае ошибки при выполнении запроса
 */
function authentication(string $login, string $password)
{
    $conn = connectDB();

    try {
        $query = "SELECT * FROM persons WHERE login = :login";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();

        // Получение результата и проверка пароля
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (password_verify($password, $result['password'])) {
                return $result['id'];
            } else {
                return false;
            }
        } else {
            return null;
        }
    } catch (PDOException $e) {
        throw new Exception("Ошибка при получении пользователя: " . $e->getMessage());
    }
}
?>
