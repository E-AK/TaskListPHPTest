// Функция для отправки Ajax запросов
function sendAjaxRequest(url, method, data) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function (response) {
                resolve(response);
            },
            error: function (error) {
                reject(error);
            },
        });
    });
}

// Задачи

// Создание задачи
function createTask(description) {
    var data = {
        operation: "create",
        description: description,
    };
    return sendAjaxRequest("controllers/tasks.php", "POST", data);
}

// Обновление задачи
function updateTask(taskId, description, createdAt, statusId) {
    var data = {
        operation: "update",
        task_id: taskId,
        description: description,
        created_at: createdAt,
        status_id: statusId,
    };
    return sendAjaxRequest("controllers/tasks.php", "POST", data);
}

// Пометить задачу как выполненную
function markTaskAsReady(taskId) {
    var data = {
        operation: "ready",
        task_id: taskId,
    };
    return sendAjaxRequest("controllers/tasks.php", "POST", data);
}

// Пометить задачу как невыполненную
function markTaskAsUnready(taskId) {
    var data = {
        operation: "unready",
        task_id: taskId,
    };
    return sendAjaxRequest("controllers/tasks.php", "POST", data);
}

// Установить все задачи как выполненные
function setAllTasksReady() {
    var data = {
        operation: "set_all_tasks_ready",
    };
    return sendAjaxRequest("controllers/tasks.php", "POST", data);
}

// Установить все задачи как невыполненные
function setAllTasksUnready() {
    var data = {
        operation: "set_all_tasks_unready",
    };
    return sendAjaxRequest("controllers/tasks.php", "POST", data);
}

// Удаление задачи
function deleteTask(taskId) {
    var data = {
        operation: "delete",
        task_id: taskId,
    };
    return sendAjaxRequest("controllers/tasks.php", "POST", data);
}

// Удаление всех задач
function removeAllTasks() {
    var data = {
        operation: "remove_all",
    };
    return sendAjaxRequest("controllers/tasks.php", "POST", data);
}

// Получение всех задач
function getAllTasks() {
    var data = {
        operation: "get",
    };
    return sendAjaxRequest("controllers/tasks.php", "GET", data);
}

// Пользователи

// Создание пользователя
function createUser(login, password) {
    var data = {
        operation: "create",
        login: login,
        password: password,
    };
    return sendAjaxRequest("controllers/users.php", "POST", data);
}

// Получение пользователя
function getUser(login) {
    var data = {
        operation: "get",
        login: login,
    };
    return sendAjaxRequest("controllers/users.php", "GET", data);
}

// Авторизация пользователя
function auth(login, password) {
    var data = {
        operation: "auth",
        login: login,
        password: password,
    };
    return sendAjaxRequest("controllers/auth.php", "POST", data);
}
