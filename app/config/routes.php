<?php
return [
    '' => [
        'controller' => 'task',
        'action' => 'index'
    ],
    'createTask' => [
        'controller' => 'task',
        'action' => 'createTask'
    ],
    'getAllTasks' => [
        'controller' => 'task',
        'action' => 'getAllTasks'
    ],
    'readyTask' => [
        'controller' => 'task',
        'action' => 'readyTask'
    ],
    'unreadyTask' => [
        'controller' => 'task',
        'action' => 'unreadyTask'
    ],
    'deleteTask' => [
        'controller' => 'task',
        'action' => 'deleteTask'
    ],
    'removeAllTasks' => [
        'controller' => 'task',
        'action' => 'removeAllTasks'
    ],
    'setAllTasksReady' => [
        'controller' => 'task',
        'action' => 'setAllTasksReady'
    ],
    'setAllTasksUnready' => [
        'controller' => 'task',
        'action' => 'setAllTasksUnready'
    ],
    'login' => [
        'controller' => 'auth',
        'action' => 'index'
    ],
    'auth' => [
        'controller' => 'auth',
        'action' => 'auth'
    ]
];
?>