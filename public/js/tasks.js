$(document).ready(function () {
    // Загрузка списка задач при загрузке страницы
    loadTasks();

    // Обработчик отправки формы для добавления задания
    $("#addTaskForm").submit(function (event) {
        event.preventDefault();

        var taskInput = $("#taskInput");
        var task = taskInput.val().trim();
        if (task !== "") {
            createTask(task)
                .then(function () {
                    taskInput.val("");
                    loadTasks(); // Перезагрузка списка задач после добавления новой
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    });

    // Обработчик клика на кнопку удаления задания
    $("#taskList").on("click", ".deleteBtn", function () {
        var taskElement = $(this).closest(".task");
        var taskId = taskElement.data("taskId");
        deleteTask(taskId)
            .then(function () {
                taskElement.remove();
                checkAllTasksStatus(); // Проверка статуса всех задач после удаления
            })
            .catch(function (error) {
                console.log(error);
            });
    });

    // Обработчик клика на кнопку READY/UNREADY
    $("#taskList").on("click", ".readyBtn, .unreadyBtn", function () {
        var taskElement = $(this).closest(".task");
        var taskId = taskElement.data("taskId");
        var isReady = $(this).hasClass("readyBtn");
        var button = $(this); // Сохраняем ссылку на кнопку

        if (isReady) {
            markTaskAsReady(taskId)
                .then(function () {
                    button
                        .removeClass("readyBtn")
                        .removeClass("btn-primary")
                        .addClass("unreadyBtn")
                        .addClass("btn-secondary")
                        .text("UNREADY");
                    taskElement
                        .find(".circle")
                        .removeClass("not-ready")
                        .addClass("ready");

                    checkAllTasksStatus(); // Проверяем статус всех задач после изменения
                })
                .catch(function () {
                    console.log("Failed to mark task as ready.");
                });
        } else {
            markTaskAsUnready(taskId)
                .then(function () {
                    button
                        .removeClass("unreadyBtn")
                        .removeClass("btn-secondary")
                        .addClass("readyBtn")
                        .addClass("btn-primary")
                        .text("READY");
                    taskElement
                        .find(".circle")
                        .removeClass("ready")
                        .addClass("not-ready");

                    checkAllTasksStatus(); // Проверяем статус всех задач после изменения
                })
                .catch(function () {
                    console.log("Failed to mark task as unready.");
                });
        }
    });

    // Обработчик клика на кнопку Remove All
    $("#removeAllBtn").click(function () {
        removeAllTasks()
            .then(function () {
                $("#taskList").empty();
            })
            .catch(function (error) {
                console.log(error);
            });
    });

    // Обработчик клика на кнопку Ready All / Unready All
    $("#taskControlsForm").on(
        "click",
        "#readyAllBtn, #unreadyAllBtn",
        function () {
            var isReadyAll = $(this).attr("id") === "readyAllBtn";

            if (isReadyAll) {
                setAllTasksReady()
                    .then(function () {
                        $("#taskList").empty();
                        loadTasks();
                    })
                    .catch(function () {
                        console.log("Failed to set all tasks as ready.");
                    });
            } else {
                setAllTasksUnready()
                    .then(function () {
                        $("#taskList").empty();
                        loadTasks();
                    })
                    .catch(function () {
                        console.log("Failed to set all tasks as unready.");
                    });
            }
        }
    );

    // Функция для загрузки списка задач
    function loadTasks() {
        getAllTasks()
            .then(function (tasksResponse) {
                let tasks = JSON.parse(tasksResponse).message;
                let status = JSON.parse(tasksResponse).status;

                if (status == "success" && tasks.length > 0) {
                    var taskListHtml = "";
                    var allTasksReady = true;
                    for (var i = 0; i < tasks.length; i++) {
                        var task = tasks[i];

                        var taskStatusClass =
                            task.status === "Ready" ? "ready" : "not-ready";
                        var readyUnreadyBtn =
                            task.status === "Ready"
                                ? '<button class="btn btn-secondary unreadyBtn">UNREADY</button> '
                                : '<button class="btn btn-primary readyBtn">READY</button> ';
                        taskListHtml +=
                            '<div class="task" data-task-id="' +
                            task.id +
                            '">' +
                            '<div class="task-content">' +
                            '<p class="mb-0">' +
                            task.description +
                            "</p>" +
                            '<div class="actions">' +
                            readyUnreadyBtn +
                            '<button class="btn btn-danger deleteBtn">DELETE</button>' +
                            "</div>" +
                            "</div>" +
                            '<div class="status">' +
                            '<div class="circle ' +
                            taskStatusClass +
                            '"></div>' +
                            "</div>" +
                            "</div>";

                        if (task.status !== "Ready") {
                            allTasksReady = false;
                        }
                    }
                    $("#taskList").html(taskListHtml);

                    // Проверяем статус всех задач и обновляем кнопку Ready All / Unready All
                    if (allTasksReady) {
                        $("#readyAllBtn")
                            .attr("id", "unreadyAllBtn")
                            .text("Unready All");
                    } else {
                        $("#unreadyAllBtn")
                            .attr("id", "readyAllBtn")
                            .text("Ready All");
                    }
                }
            })
            .catch(function (error) {
                console.log(error);
                $("#taskList").html("<p>Failed to load tasks.</p>");
            });
    }

    // Функция для проверки статуса всех задач
    function checkAllTasksStatus() {
        var allTasksReady = true;
        $(".task").each(function () {
            if (!$(this).find(".circle").hasClass("ready")) {
                allTasksReady = false;
                return false; // выход из цикла, если найдена невыполненная задача
            }
        });

        if (allTasksReady) {
            $("#readyAllBtn")
                .removeClass("readyAllBtn")
                .addClass("unreadyAllBtn")
                .text("Unready All");
        } else {
            $("#readyAllBtn")
                .removeClass("unreadyAllBtn")
                .addClass("readyAllBtn")
                .text("Ready All");
        }
    }
});
