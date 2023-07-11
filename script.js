$(document).ready(function() {
  // Загрузка списка задач при загрузке страницы
  loadTasks();

  // Обработчик отправки формы для добавления задания
  $('#addTaskForm').submit(function(event) {
    event.preventDefault();

    var taskInput = $('#taskInput');
    var task = taskInput.val().trim();
    if (task !== '') {
      addTask(task);
      taskInput.val('');
    }
  });

  // Обработчик клика на кнопку удаления задания
  $('#taskList').on('click', '.deleteBtn', function() {
    var taskElement = $(this).closest('.task');
    var taskId = taskElement.data('taskId');
    removeTask(taskId);
  });

  // Обработчик клика на кнопку READY/UNREADY
  $('#taskList').on('click', '.readyBtn, .unreadyBtn', function() {
    var taskElement = $(this).closest('.task');
    var taskId = taskElement.data('taskId');
    var isReady = $(this).hasClass('readyBtn');
    updateTaskStatus(taskId, isReady);
  });

  // Обработчик клика на кнопку Remove All
  $('#removeAllBtn').click(function() {
    removeAllTasks();
  });

  // Обработчик клика на кнопку Ready All / Unready All
  $('#readyAllBtn').click(function() {
    if ($(this).hasClass('readyAllBtn')) {
      markAllTasksAsReady();
    } else {
      markAllTasksAsUnready();
    }
  });

  // Функция для загрузки списка задач
  function loadTasks() {
    $.ajax({
      url: 'ajax.php?action=getTasks',
      type: 'GET',
      dataType: 'json',
      success: function(tasks) {
        if (tasks.length > 0) {
          var taskListHtml = '';
          var allTasksReady = true;
          for (var i = 0; i < tasks.length; i++) {
            var task = tasks[i];
            var taskStatusClass = task.status === 'Ready' ? 'ready' : 'not-ready';
            var readyUnreadyBtn = task.status === 'Not ready' ? '<button class="readyBtn">READY</button>' : '<button class="unreadyBtn">UNREADY</button>';
            taskListHtml += '<div class="task" data-task-id="' + task.taskId + '">' +
              '<div class="task-content">' +
              '<p>' + task.description + '</p>' +
              '<div class="actions">' +
              readyUnreadyBtn +
              '<button class="deleteBtn">DELETE</button>' +
              '</div>' +
              '</div>' +
              '<div class="status">' +
              '<div class="circle ' + taskStatusClass + '"></div>' +
              '</div>' +
              '</div>';

            if (task.status !== 'Ready') {
              allTasksReady = false;
            }
          }
          $('#taskList').html(taskListHtml);

          // Проверяем статус всех задач и обновляем кнопку Ready All / Unready All
          if (allTasksReady) {
            $('#readyAllBtn').removeClass('readyAllBtn').addClass('unreadyAllBtn').text('Unready All');
          }
        }
      },
      error: function() {
        $('#taskList').html('<p>Failed to load tasks.</p>');
      }
    });
  }

  // Функция для добавления задачи
  function addTask(task) {
    $.ajax({
      url: 'ajax.php?action=addTask',
      type: 'POST',
      data: { task: task },
      dataType: 'json',
      success: function(response) {
        var taskId = response.taskId;
        var taskStatusClass = 'not-ready';
        var readyUnreadyBtn = '<button class="readyBtn">READY</button>';
        var taskHtml = '<div class="task" data-task-id="' + taskId + '">' +
          '<div class="task-content">' +
          '<p>' + task + '</p>' +
          '<div class="actions">' +
          readyUnreadyBtn +
          '<button class="deleteBtn">DELETE</button>' +
          '</div>' +
          '</div>' +
          '<div class="status">' +
          '<div class="circle ' + taskStatusClass + '"></div>' +
          '</div>' +
          '</div>';

        $('#taskList').append(taskHtml);

        // Обновляем кнопку Ready All / Unready All, если все задачи выполнены
        if ($('#readyAllBtn').hasClass('unreadyAllBtn')) {
          $('#readyAllBtn').removeClass('unreadyAllBtn').addClass('readyAllBtn').text('Ready All');
        }
      },
      error: function(e) {
        console.log(e);
      }
    });
  }

  // Функция для удаления задачи
  function removeTask(taskId) {
    $.ajax({
      url: 'ajax.php?action=removeTask',
      type: 'POST',
      data: { taskId: taskId },
      dataType: 'json',
      success: function(response) {
        $('.task[data-task-id="' + taskId + '"]').remove();

        // Обновляем кнопку Ready All / Unready All, если все задачи удалены
        if ($('.task').length === 0 && $('#readyAllBtn').hasClass('unreadyAllBtn')) {
          $('#readyAllBtn').removeClass('unreadyAllBtn').addClass('readyAllBtn').text('Ready All');
        }
      },
      error: function(e) {
        console.log(e);
      }
    });
  }

  // Функция для обновления статуса задачи
  function updateTaskStatus(taskId, isReady) {
    var taskElement = $('.task[data-task-id="' + taskId + '"]');
    var button = taskElement.find('.readyBtn, .unreadyBtn');
    button.removeClass('readyBtn unreadyBtn');

    if (isReady) {
      button.addClass('unreadyBtn').text('UNREADY');
      taskElement.find('.circle').removeClass('not-ready').addClass('ready');
    } else {
      button.addClass('readyBtn').text('READY');
      taskElement.find('.circle').removeClass('ready').addClass('not-ready');
    }

    // Отправляем запрос на обновление статуса задачи на сервер
    $.ajax({
      url: 'ajax.php?action=updateStatus',
      type: 'POST',
      data: { taskId: taskId, status: isReady ? 'Ready' : 'Not ready' },
      dataType: 'json',
      success: function(response) {
        checkAllTasksStatus(); // Проверяем статус всех задач после обновления
      },
      error: function(e) {
        console.log(e);
      }
    });

    // Проверяем статус всех задач и обновляем кнопку Ready All / Unready All
    var allTasksReady = true;
    $('.task').each(function() {
      if (!$(this).find('.circle').hasClass('ready')) {
        allTasksReady = false;
        return false; // выход из цикла, если найдена невыполненная задача
      }
    });

    if (allTasksReady) {
      $('#readyAllBtn').removeClass('readyAllBtn').addClass('unreadyAllBtn').text('Unready All');
    } else {
      $('#readyAllBtn').removeClass('unreadyAllBtn').addClass('readyAllBtn').text('Ready All');
    }
  }

  // Функция для удаления всех задач
  function removeAllTasks() {
    $.ajax({
      url: 'ajax.php?action=removeAll',
      type: 'POST',
      dataType: 'json',
      success: function(response) {
        $('#taskList').empty();
        $('#readyAllBtn').removeClass('unreadyAllBtn').addClass('readyAllBtn').text('Ready All');
      },
      error: function(e) {
        console.log(e);
      }
    });
  }

  // Функция для установки статуса "Ready" для всех задач
  function markAllTasksAsReady() {
    $.ajax({
      url: 'ajax.php?action=readyAll',
      type: 'POST',
      dataType: 'json',
      success: function(response) {
        $('.task').find('.readyBtn, .unreadyBtn').removeClass('readyBtn').addClass('unreadyBtn').text('UNREADY');
        $('.circle').removeClass('not-ready').addClass('ready');
        $('#readyAllBtn').removeClass('readyAllBtn').addClass('unreadyAllBtn').text('Unready All');
      },
      error: function(e) {
        console.log(e);
      }
    });
  }

  // Функция для установки статуса "Not ready" для всех задач
  function markAllTasksAsUnready() {
    $.ajax({
      url: 'ajax.php?action=unreadyAll',
      type: 'POST',
      dataType: 'json',
      success: function(response) {
        $('.task').find('.readyBtn, .unreadyBtn').removeClass('unreadyBtn').addClass('readyBtn').text('READY');
        $('.circle').removeClass('ready').addClass('not-ready');
        $('#readyAllBtn').removeClass('unreadyAllBtn').addClass('readyAllBtn').text('Ready All');
      },
      error: function(e) {
        console.log(e);
      }
    });
  }
});
