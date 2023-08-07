<?php
require('../models/Task.php');
require('../services/tasks.php');

session_start();

// Получаем userId из сессии
$userId = $_SESSION['user_id'] ?? null;

// Проверяем метод запроса
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Проверяем тип операции
        switch ($_POST['operation']) {
            case 'create':
                // Создание новой задачи
                $description = $_POST['description'];

                try {
                    $createdTask = createTask($userId, $description, 'Unready');

                    echo json_encode(['status' => 'success', 'task' => $createdTask]);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'ready':
                try {
                    // Пометить задачу как готовую
                    $taskId = (int)$_POST['task_id'];
                    readyTask($taskId);
                    echo json_encode(['status' => 'success']);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'unready':
                try {
                    // Пометить задачу как неготовую
                    $taskId = (int)$_POST['task_id'];
                    unreadyTask($taskId);
                    echo json_encode(['status' => 'success']);
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                }
                break;

            case 'update':
                try {
                    // Обновление задачи
                    $taskId = (int)$_POST['task_id'];
                    $description = $_POST['description'];
                    $createdAt = $_POST['created_at'];
                    $statusId = (int)$_POST['status_id'];

                    $task = new Task($taskId, null, $description, $createdAt, $statusId);
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
                try {
                    // Удаление задачи
                    $taskId = (int)$_POST['task_id'];
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
