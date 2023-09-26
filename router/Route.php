<?php

class Route
{
    private $controller;
    private $action;
    private $params;
    private $uri;

    // magic method getter
    public function __get($var) {
        if (isset($this->{$var})) {
            return $this->{$var};
        }

        return null;
    }
    //khởi tạo uri dựa trên uri lấy được
    function __construct($uri) {
        $this->uri = $uri;
        if ($this->uri == '/') {
            $this->controller = 'bill';
            $this->action = 'index';
            $this->params = [];

            return 0;
        }

        return $this->parse();
    }
    //tách và trả về controller' name, action, param
    public function parse() {
        $uriArray = explode('/', $this->uri);
        $this->controller = $uriArray['1'];
        $this->action = $uriArray['2'];
        $this->params = array_slice($uriArray, 3);
    }
}