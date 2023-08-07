<?php
namespace app\controllers;

use app\core\Controller;

class TaskController extends Controller {
    /**
     * Отображение страницы с задачами.
     */
    public function indexAction() {
		$this->view->render('Task List', []);
	}

    /**
     * Получить все задачи пользователя
     */
    public function getAllTasksAction() {
        $userId = $_SESSION['user_id'];

        $result = $this->model->getAllTasks($userId);

        if ($result) {
            $this->view->message('success', htmlspecialchars($result));
        } else {
            $this->view->message('error', 'Ошибка при получении задач');
        }
    }

    /**
     * Создать новую задачу
     */
    public function createTaskAction() {
        $userId = $_SESSION['user_id'];

        $description = $_POST['description'];

        $result = $this->model->createTask($userId, $description);

        if ($result) {
            $this->view->message('success', 'Задача создана');
        } else {
            $this->view->message('error', 'Ошибка при создании задачи');
        }
    }
    /**
     * Установить статус "Ready" для задачи
     */
    public function readyTaskAction() {
        try{
            $taskId = (int)$_POST['task_id'];
            $userId = $_SESSION['user_id'];
            $result = $this->model->readyTask($taskId, $userId);
            $this->view->message('success', 'Задача отмечена как выполнена');
        } catch (PDOException $e) {
            $this->view->message('error', 'Ошибка при отметке задачи: ' . $e);
        }
    }

    /**
     * Установить статус "Unready" для задачи
     */
    public function unreadyTaskAction() {
        try {
            $taskId = (int)$_POST['task_id'];
            $userId = $_SESSION['user_id'];
            $result = $this->model->unreadyTask($taskId, $userId);
        } catch (PDOException $e) {
            $this->view->message('error', 'Ошибка при отметке задачи: ' . $e);
        }
    }

    /**
     * Установить статус "Ready" для всех задач пользователя
     */
    public function setAllTasksReadyAction() {
        $userId = $_SESSION['user_id'];

        $result = $this->model->setAllTasksReady($userId);

        if ($result) {
            $this->view->message('success', 'Все задачи отмечены как выполнены');
        } else {
            $this->view->message('error', 'Ошибка при отметке задачи');
        }
    }

    /**
     * Установить статус "Unready" для всех задач пользователя
     */
    public function setAllTasksUnreadyAction() {
        $userId = $_SESSION['user_id'];

        $result = $this->model->setAllTasksUnready($userId);

        if ($result) {
            $this->view->message('success', 'Все задачи отмечены как не выполнены');
        } else {
            $this->view->message('error', 'Ошибка при отметке задачи');
        }
    }

    /**
     * Удалить все задачи пользователя
     */
    public function removeAllTasksAction() {
        $userId = $_SESSION['user_id'];

        $result = $this->model->removeAllTasks($userId);

        if ($result) {
            $this->view->message('success', 'Все задачи удалены');
        } else {
            $this->view->message('error', 'Ошибка при отметке задачи');
        }
    }

    /**
     * Удалить задачу
     */
    public function deleteTaskAction() {
        try {
            $taskId = (int)$_POST['task_id'];
            $userId = $_SESSION['user_id'];
            $result = $this->model->deleteTask($taskId, $userId);

            $this->view->message('success', 'Задача удалена');
        } catch (PDOException $e) {
            $this->view->message('error', 'Ошибка при удалении задачи: ' . $e);
        }
    }
}
?>
