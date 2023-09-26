<?php

class Controller
{
    // hàm thực hiện trả về view
    public function render($view, $params = []) // tên view và các biến gửi kèm
    {
        extract($params);
        $controller = lcfirst(str_replace('Controller', '', get_class($this)));
        ob_start();
        require_once '../view/' . $controller . '/' . $view . '.php';
        $screen = ob_get_clean();
        require_once '../view/layout/layout.php';
    }

    public function check()
    {
        if (!isset($_SESSION['user']['_id'])) {
            return header('Location: /auth/login');
        } else {
            if ($_SESSION['user']['role'] == 'admin') {
                $param = [];
            } else {
                $param = ['user_id' => $_SESSION['user']['_id']];
            }
        }

        return $param;
    }
}
