<?php

require_once '../Models/User.php';

class AuthController extends Controller
{
    // các biến cho model
    private $model;

    //khởi tạo cho các biển gọi tới model
    function __construct()
    {
        $this->model = new User();
    }

    // lấy ra danh sach bill và trả về view
    public function login()
    {
        return $this->render('login', []);
    }

    public function auth()
    {
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $user = $this->model->check(['username' => $username, 'password' => $password]);

        if(isset($user)){
            session_destroy();
            session_start();
            $_SESSION['user'] = [
                '_id' => $user->_id,
                'username' => $user->username,
                'role' => $user->role
            ];
            
            $response = [
                'success' => true,
                'message' => 'Đăng nhập thành công!',
            ];
            echo json_encode($response);
        }else{
            $response = [
                'success' => false,
                'message' => 'Tài khoản hoặc mật khẩu không đúng',
            ];
            echo json_encode($response);
        }
    }

    public function logout()
    {
        session_destroy();

        return header('Location: /auth/login');
    }
}