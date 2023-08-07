<?php
namespace app\models;

use app\core\Model;

class Task extends Model {
    public $id;
    public $userId;
    public $description;
    public $createdAt;
    public $status;

    /**
     * Создать новую задачу
     *
     * @param int $userId Идентификатор пользователя
     * @param string $description Описание задачи
     * @return bool Успешность выполнения операции
     */
    public function createTask(int $userId, string $description) {
        $result = $this->db->row("INSERT INTO tasks (user_id, description, status_id) VALUES (" . 
            $userId . ", \"" . $description . "\", " . "(SELECT id FROM task_statuses WHERE status = 'Unready'))");

        if ($result !== null) {
            return true;
        }

        return false;
    }

    /**
     * Получить все задачи пользователя
     *
     * @param int $userId Идентификатор пользователя
     * @return array Массив объектов Task
     */
    public function getAllTasks(int $userId) {
        $result = $this->db->row("SELECT tasks.*, task_statuses.status
            FROM tasks
            INNER JOIN task_statuses ON tasks.status_id = task_statuses.id
            WHERE tasks.user_id = " . $userId);

            if ($result) {
                $tasks = [];

                foreach ($result as $row) {
                    $task = new Task;
                    $task->id = $row['id'];
                    $task->userId = $row['user_id'];
                    $task->description = $row['description'];
                    $task->createdAt = $row['created_at']; // Corrected the property name
                    $task->status = $row['status'];

                    $tasks[] = $task;
                }

            return $tasks;
        }

        return false;
    }

    /**
     * Установить статус "Ready" для задачи
     *
     * @param int $taskId Идентификатор задачи
     * @return bool Успешность выполнения операции
     */
    public function readyTask(int $taskId, int $userId) {
        $result = $this->db->row("UPDATE tasks SET status_id = (SELECT id FROM task_statuses WHERE status = 'Ready') 
            WHERE id = " . $taskId . " AND user_id = " . $userId);

        if ($result !== null) {
            return true;
        }

        return false;
    }

    /**
     * Установить статус "Unready" для задачи
     *
     * @param int $taskId Идентификатор задачи
     * @return bool Успешность выполнения операции
     */
    public function unreadyTask(int $taskId, $userId) {
        $result = $this->db->row("UPDATE tasks SET status_id = (SELECT id FROM task_statuses WHERE status = 'Unready') 
            WHERE id = " . $taskId . " AND user_id = " . $userId);

        if ($result !== null) {
            return true;
        }

        return false;
    }

    /**
     * Удалить задачу
     *
     * @param int $taskId Идентификатор задачи
     * @return bool Успешность выполнения операции
     */
    public function deleteTask(int $taskId, $userId) {
        $result = $this->db->row("DELETE FROM tasks WHERE id = " . $taskId . " AND user_id = " . $userId);

        if ($result !== null) {
            return true;
        }

        return false;
    }

    /**
     * Удалить все задачи пользователя
     *
     * @param int $userId Идентификатор пользователя
     * @return bool Успешность выполнения операции
     */
    public function removeAllTasks(int $userId) {
        $result = $this->db->row("DELETE FROM tasks WHERE user_id = " . $userId);

        if ($result !== null) {
            return true;
        }

        return false;
    }

    /**
     * Установить статус "Ready" для всех задач пользователя
     *
     * @param int $userId Идентификатор пользователя
     * @return bool Успешность выполнения операции
     */
    public function setAllTasksReady(int $userId) {
        $result = $this->db->row("UPDATE tasks SET status_id = (SELECT id FROM task_statuses WHERE status = 'Ready') WHERE user_id = " . $userId);

        if ($result !== null) {
            return true;
        }

        return false;
    }

    /**
     * Установить статус "Unready" для всех задач пользователя
     *
     * @param int $userId Идентификатор пользователя
     * @return bool Успешность выполнения операции
     */
    public function setAllTasksUnready(int $userId) {
        $result = $this->db->row("UPDATE tasks SET status_id = (SELECT id FROM task_statuses WHERE status = 'Unready') WHERE user_id = " . $userId);

        if ($result !== null) {
            return true;
        }

        return false;
    }
}
?>
