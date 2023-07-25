<?php

namespace app\core;

use app\core\View;

/**
 * Класс Router отвечает за маршрутизацию запросов.
 */
class Router
{
    protected $routes = [];
    protected $params = [];

    /**
     * Загружает маршруты из файла и добавляет их.
     */
    public function __construct()
    {
        $arr = require 'app/config/routes.php';

        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    /**
     * Добавляет маршрут и его параметры в список маршрутов.
     * @param string $route Маршрут для добавления (шаблон).
     * @param array $params Параметры, связанные с маршрутом (controller и action).
     */
    public function add($route, $params)
    {
        $route = '#^' . $route . '$#';
        $this->routes[$route] = $params;
    }

    /**
     * Пытается найти соответствующий маршрут для текущего запроса.
     * @return bool Возвращает true, если найден подходящий маршрут, иначе - false.
     */
    public function match()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;

                return true;
            }
        }

        return false;
    }

    /**
     * Запускает контроллер и действие, соответствующее найденному маршруту.
     * Если маршрут не найден, отображает ошибку 404.
     */
    public function run()
    {
        if (!$this->match()) {
            View::errorCode(404);
            
            return;
        }

        $controllerNamespace = 'app\controllers\\';
        $controllerName = ucfirst($this->params['controller']) . 'Controller';
        $actionName = $this->params['action'] . 'Action';
        
        $controllerClass = $controllerNamespace . $controllerName;
        
        if (class_exists($controllerClass) && method_exists($controllerClass, $actionName)) {
            $controller = new $controllerClass($this->params);
            $controller->$actionName();
        } else {
            View::errorCode(404);
        }
    }
}
?>