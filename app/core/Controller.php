<?php

namespace app\core;

use app\core\View;

/**
 * Базовый контроллер приложения.
 */
abstract class Controller
{
    public $route;
    public $view;
    public $model;
    public $acl;

    public function __construct($route)
    {
        $this->route = $route;

        // Проверяем права доступа для данного маршрута
        if (!$this->checkAcl()) {
            View::errorCode(403); // Ошибка 403 - Доступ запрещен
        }

        $this->view = new View($route);

        $this->model = $this->loadModel($route['controller']);
    }

    /**
     * Загружает модель по её имени.
     * @param string $name Имя модели.
     * @return object|null Возвращает объект модели или null, если модель не найдена.
     */
    public function loadModel(string $name)
    {
        $path = 'app\models\\' . ucfirst($name);

        if (class_exists($path)) {
            return new $path;
        }
		
        return null;
    }

    /**
     * Проверяет права доступа для текущего маршрута.
     * @return bool Возвращает true, если у пользователя есть права доступа, иначе - false.
     */
    public function checkAcl()
    {
        $this->acl = require 'app/acl/' . $this->route['controller'] . '.php';

        if (isset($_SESSION['user_id']) && $this->isAcl('authorize')) {
            return true;
        } elseif (!isset($_SESSION['user_id']) && $this->isAcl('guest')) {
            return true;
        }

        return false;
    }

    /**
     * Проверяет, есть ли в массиве доступных действий маршрута текущее действие.
     * @param string $key Ключ.
     * @return bool Возвращает true, если текущее действие доступно, иначе - false.
     */
    public function isAcl(string $key)
    {
        return in_array($this->route['action'], $this->acl[$key]);
    }
}
