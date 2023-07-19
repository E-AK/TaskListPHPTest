<?php
require('../db.php');

/**
 * Создать новую задачу
 *
 * @param int $userId Идентификатор пользователя
 * @param string $description Описание задачи
 * @return bool Успешность выполнения операции
 * @throws Exception В случае ошибки при создании задачи
 */
function createTask(int $userId, string $description): bool {
    $conn = connectDB();

    try {
        $query = "INSERT INTO tasks (user_id, description, status_id) VALUES (:userId, :description, (SELECT id FROM task_statuses WHERE status = 'Unready'))";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception("Ошибка при создании задачи: " . $e->getMessage());
    }
}

/**
 * Получить все задачи пользователя
 *
 * @param int $userId Идентификатор пользователя
 * @return array Массив объектов Task
 * @throws Exception В случае ошибки при получении задач
 */
function getAllTasks(int $userId): array {
    $conn = connectDB();

    try {
        $query = "SELECT tasks.*, task_statuses.status
          FROM tasks
          INNER JOIN task_statuses ON tasks.status_id = task_statuses.id
          WHERE tasks.user_id = :userId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $tasks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $task = new Task(
                $row['id'],
                $row['user_id'],
                $row['description'],
                $row['created_at'],
                $row['status']
            );
            $tasks[] = $task;
        }
        return $tasks;
    } catch (PDOException $e) {
        throw new Exception("Ошибка при получении задач: " . $e->getMessage());
    }
}

/**
 * Обновить задачу
 *
 * @param Task $task Объект задачи
 * @return bool Успешность выполнения операции
 * @throws Exception В случае ошибки при обновлении задачи
 */
function updateTask(Task $task): bool {
    $conn = connectDB();

    try {
        $query = "UPDATE tasks SET description = :description, created_at = :createdAt, status_id = :statusId WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':description', $task->getDescription(), PDO::PARAM_STR);
        $stmt->bindParam(':createdAt', $task->getCreatedAt(), PDO::PARAM_STR);
        $stmt->bindParam(':statusId', $task->getStatusId(), PDO::PARAM_INT);
        $stmt->bindParam(':id', $task->getId(), PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception("Ошибка при обновлении задачи: " . $e->getMessage());
    }
}

/**
 * Установить статус "Ready" для задачи
 *
 * @param int $taskId Идентификатор задачи
 * @return bool Успешность выполнения операции
 * @throws Exception В случае ошибки при обновлении задачи
 */
function readyTask(int $taskId): bool {
    $conn = connectDB();

    try {
        $query = "UPDATE tasks SET status_id = (SELECT id FROM task_statuses WHERE status = 'Ready') WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception("Ошибка при обновлении задачи: " . $e->getMessage());
    }
}

/**
 * Установить статус "Unready" для задачи
 *
 * @param int $taskId Идентификатор задачи
 * @return bool Успешность выполнения операции
 * @throws Exception В случае ошибки при обновлении задачи
 */
function unreadyTask(int $taskId): bool {
    $conn = connectDB();

    try {
        $query = "UPDATE tasks SET status_id = (SELECT id FROM task_statuses WHERE status = 'Unready') WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception("Ошибка при обновлении задачи: " . $e->getMessage());
    }
}

/**
 * Удалить задачу
 *
 * @param int $taskId Идентификатор задачи
 * @return bool Успешность выполнения операции
 * @throws Exception В случае ошибки при удалении задачи
 */
function deleteTask(int $taskId): bool {
    $conn = connectDB();

    try {
        $query = "DELETE FROM tasks WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception("Ошибка при удалении задачи: " . $e->getMessage());
    }
}

/**
 * Удалить все задачи пользователя
 *
 * @param int $userId Идентификатор пользователя
 * @return bool Успешность выполнения операции
 * @throws Exception В случае ошибки при удалении задач
 */
function removeAllTasks(int $userId): bool {
    $conn = connectDB();

    try {
        $query = "DELETE FROM tasks WHERE user_id = :userId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception("Ошибка при удалении всех задач: " . $e->getMessage());
    }
}

/**
 * Установить статус "Ready" для всех задач пользователя
 *
 * @param int $userId Идентификатор пользователя
 * @return bool Успешность выполнения операции
 * @throws Exception В случае ошибки при обновлении задач
 */
function setAllTasksReady(int $userId): bool {
    $conn = connectDB();

    try {
        $query = "UPDATE tasks SET status_id = (SELECT id FROM task_statuses WHERE status = 'Ready') WHERE user_id = :userId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception("Ошибка при установке статуса 'Ready' для всех задач: " . $e->getMessage());
    }
}

/**
 * Установить статус "Unready" для всех задач пользователя
 *
 * @param int $userId Идентификатор пользователя
 * @return bool Успешность выполнения операции
 * @throws Exception В случае ошибки при обновлении задач
 */
function setAllTasksUnready(int $userId): bool {
    $conn = connectDB();

    try {
        $query = "UPDATE tasks SET status_id = (SELECT id FROM task_statuses WHERE status = 'Unready') WHERE user_id = :userId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        throw new Exception("Ошибка при установке статуса 'Unready' для всех задач: " . $e->getMessage());
    }
}
?>
