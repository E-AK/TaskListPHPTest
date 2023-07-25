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
            $this->view->message('success', $result);
        } else {
            $this->view->message('error', 'Ошибка при получении задач');
        }
    }

    /**
     * Создать новую задачу
     */
    public function createTaskAction() {
        $userId = $_SESSION['user_id'];

        $description = htmlspecialchars($_POST['description']);

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
        $taskId = htmlspecialchars($_POST['task_id']);

        $result = $this->model->readyTask($taskId);

        if ($result) {
            $this->view->message('success', 'Задача отмечена как выполнена');
        } else {
            $this->view->message('error', 'Ошибка при отметке задачи');
        }
    }

    /**
     * Установить статус "Unready" для задачи
     */
    public function unreadyTaskAction() {
        $taskId = htmlspecialchars($_POST['task_id']);

        $result = $this->model->unreadyTask($taskId);

        if ($result) {
            $this->view->message('success', 'Задача отмечена как не выполнена');
        } else {
            $this->view->message('error', 'Ошибка при отметке задачи');
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
        $taskId = htmlspecialchars($_POST['task_id']);

        $result = $this->model->deleteTask($taskId);

        if ($result) {
            $this->view->message('success', 'Задача удалена');
        } else {
            $this->view->message('error', 'Ошибка при удалении задачи');
        }
    }
}
?>
