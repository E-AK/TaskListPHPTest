<?php
// Класс для работы с задачами
class TaskManager {
    // Получение списка задач
    public static function getTasks() {
        session_start();
        if (isset($_SESSION['tasks'])) {
            return $_SESSION['tasks'];
        } else {
            return [];
        }
    }

    // Сохранение списка задач
    public static function saveTasks($tasks) {
        session_start();
        $_SESSION['tasks'] = $tasks;
    }

    // Добавление задачи
    public static function addTask($description) {
        $tasks = self::getTasks();
        $newTaskId = uniqid(); // Генерация уникального идентификатора для задачи
        $newTask = [
            'taskId' => $newTaskId,
            'description' => $description,
            'status' => 'Not ready'
        ];
        $tasks[] = $newTask;
        self::saveTasks($tasks);
        return $newTaskId;
    }

    // Удаление задачи
    public static function removeTask($taskId) {
        $tasks = self::getTasks();
        $tasks = array_filter($tasks, function ($task) use ($taskId) {
            return $task['taskId'] !== $taskId;
        });
        self::saveTasks($tasks);
    }

    // Обновление статуса задачи
    public static function updateTaskStatus($taskId, $status) {
        $tasks = self::getTasks();
        foreach ($tasks as &$task) {
            if ($task['taskId'] === $taskId) {
                $task['status'] = $status;
                break;
            }
        }
        self::saveTasks($tasks);
    }

    // Удаление всех задач
    public static function removeAllTasks() {
        session_start();
        $_SESSION['tasks'] = [];
    }

    // Установка статуса "Ready" для всех задач
    public static function markAllTasksAsReady() {
        $tasks = self::getTasks();
        foreach ($tasks as &$task) {
            $task['status'] = 'Ready';
        }
        self::saveTasks($tasks);
    }

    // Установка статуса "Not ready" для всех задач
    public static function markAllTasksAsUnready() {
        $tasks = self::getTasks();
        foreach ($tasks as &$task) {
            $task['status'] = 'Not ready';
        }
        self::saveTasks($tasks);
    }
}

// Получение действия из параметра запроса
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Обработка запросов
if ($action === 'getTasks') {
    $tasks = TaskManager::getTasks();
    echo json_encode($tasks);
} elseif ($action === 'addTask') {
    $task = $_POST['task'];
    $taskId = TaskManager::addTask($task);
    echo json_encode(['taskId' => $taskId]);
} elseif ($action === 'removeTask') {
    $taskId = $_POST['taskId'];
    TaskManager::removeTask($taskId);
    echo json_encode(['success' => true]);
} elseif ($action === 'updateStatus') {
    $taskId = $_POST['taskId'];
    $status = $_POST['status'];
    TaskManager::updateTaskStatus($taskId, $status);
    echo json_encode(['success' => true]);
} elseif ($action === 'removeAll') {
    TaskManager::removeAllTasks();
    echo json_encode(['success' => true]);
} elseif ($action === 'readyAll') {
    TaskManager::markAllTasksAsReady();
    echo json_encode(['success' => true]);
} elseif ($action === 'unreadyAll') {
    TaskManager::markAllTasksAsUnready();
    echo json_encode(['success' => true]);
}
?>
