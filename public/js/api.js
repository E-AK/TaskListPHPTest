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
        description: description,
    };
    return sendAjaxRequest("/createTask", "POST", data);
}

// Пометить задачу как выполненную
function markTaskAsReady(taskId) {
    var data = {
        task_id: taskId,
    };
    return sendAjaxRequest("readyTask", "POST", data);
}

// Пометить задачу как невыполненную
function markTaskAsUnready(taskId) {
    var data = {
        task_id: taskId,
    };
    return sendAjaxRequest("unreadyTask", "POST", data);
}

// Установить все задачи как выполненные
function setAllTasksReady() {
    return sendAjaxRequest("setAllTasksReady", "POST", null);
}

// Установить все задачи как невыполненные
function setAllTasksUnready() {
    return sendAjaxRequest("setAllTasksUnready", "POST", null);
}

// Удаление задачи
function deleteTask(taskId) {
    var data = {
        operation: "delete",
        task_id: taskId,
    };
    return sendAjaxRequest("deleteTask", "POST", data);
}

// Удаление всех задач
function removeAllTasks() {
    return sendAjaxRequest("removeAllTasks", "POST", null);
}

// Получение всех задач
function getAllTasks() {
    return sendAjaxRequest("getAllTasks", "GET", null);
}

// Пользователи

// Авторизация пользователя
function auth(login, password) {
    var data = {
        login: login,
        password: password,
    };
    return sendAjaxRequest("auth", "POST", data);
}
