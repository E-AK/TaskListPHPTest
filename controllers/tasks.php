<?php
require('../models/task.php');
require('../services/tasks.php');

session_start();

// Получаем userId из сессии
$userId = base64_decode($_SESSION['user_id']) ?? null;

// Проверяем метод запроса
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Проверяем тип операции
        switch ($_POST['operation']) {
            case 'create':
                // Создание новой задачи
                $description = htmlspecialchars($_POST['description']);

                try {
                    $createdTask = createTask($userId, $description, 'Unready');

                    echo json_encode(['status' => 'success', 'task' => $createdTask]);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'ready':
                // Пометить задачу как готовую
                $taskId = htmlspecialchars($_POST['task_id']);

                try {
                    readyTask($taskId);
                    echo json_encode(['status' => 'success']);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'unready':
                // Пометить задачу как неготовую
                $taskId = htmlspecialchars($_POST['task_id']);

                try {
                    unreadyTask($taskId);
                    echo json_encode(['status' => 'success']);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'update':
                // Обновление задачи
                $taskId = htmlspecialchars($_POST['task_id']);
                $description = htmlspecialchars($_POST['description']);
                $createdAt = htmlspecialchars($_POST['created_at']);
                $statusId = htmlspecialchars($_POST['status_id']);

                $task = new Task($taskId, null, $description, $createdAt, $statusId);
                try {
                    updateTask($task);
                    echo json_encode(['status' => 'success']);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'set_all_tasks_ready':
                // Установить статус "Ready" для всех задач
                try {
                    setAllTasksReady($userId);
                    echo json_encode(['status' => 'success']);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'set_all_tasks_unready':
                // Установить статус "Unready" для всех задач
                try {
                    setAllTasksUnready($userId);
                    echo json_encode(['status' => 'success']);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'remove_all':
                // Удаление всех задач
                try {
                    removeAllTasks($userId);
                    echo json_encode(['status' => 'success']);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'delete':
                // Удаление задачи
                $taskId = htmlspecialchars($_POST['task_id']);

                try {
                    deleteTask($taskId);
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
        // Получение всех задач пользователя
        try {
            $tasks = getAllTasks($userId);

            echo json_encode(['status' => 'success', 'tasks' => $tasks]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Unsupported request method']);
        break;
}
?>
