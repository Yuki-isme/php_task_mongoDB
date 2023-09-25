<?php

class Dispatcher
{
    private $route;

    private function route() {
        $uri = $_SERVER['REQUEST_URI']; //lấy uri

        return $this->route = new Route($uri);//khởi tạo dựa trên uri
    }

    public function dispatch() {
        $this->route();// lấy ra controller, action, param
        //use controller and call
        $controllerName = ucfirst($this->route->controller) . 'Controller';
        require_once '../Controllers/'. $controllerName . '.php';
        
        $controller = new $controllerName();
        $action = $this->route->action;
        $params = $this->route->params;

        return call_user_func_array([$controller, $action], $params);
    }
}