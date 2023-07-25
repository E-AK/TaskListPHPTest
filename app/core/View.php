<?php

namespace app\core;

/**
 * Класс View отвечает за отображение страниц.
 */
class View
{
    public $path;
    public $route;
    public $layout = 'default';

    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    /**
     * Рендерит представление с заданными переменными и используемым шаблоном.
     * @param string $title Заголовок страницы.
     * @param array $vars Ассоциативный массив с переменными, используемыми в представлении.
     */
    public function render($title, $vars = [])
    {
        extract($vars);
        $path = 'app/views/' . $this->path . '.php';

        if (file_exists($path)) {
            ob_start();
            require $path;
            $content = ob_get_clean();
            require 'app/views/layouts/' . $this->layout . '.php';
        }
    }

    /**
     * Выполняет перенаправление на указанный URL.
     * @param string $url URL для перенаправления.
     */
    public function redirect($url)
    {
        header('location: ' . $url);

        exit;
    }

    /**
     * Отображает страницу с ошибкой с заданным HTTP-кодом.
     * @param int $code Код HTTP-ошибки.
     */
    public static function errorCode($code)
    {
        http_response_code($code);
        $path = 'app/views/errors/' . $code . '.php';
		
        if (file_exists($path)) {
            require $path;
        }

        exit;
    }

    /**
     * Выводит сообщение в формате JSON и завершает выполнение скрипта.
     * @param string $status Статус сообщения.
     * @param string $message Текст сообщения.
     */
    public function message($status, $message)
    {
        exit(json_encode(['status' => $status, 'message' => $message]));
    }

    /**
     * Выводит URL в формате JSON и завершает выполнение скрипта.
     * @param string $url URL для перенаправления.
     */
    public function location($url)
    {
        exit(json_encode(['url' => $url]));
    }
}
?>