<?php
require_once 'app/config/database.php';
require_once 'app/models/UserModel.php';
require_once 'app/helpers/SessionHelper.php';

class AuthController {
    private $userModel;
    private $db;

    public function __construct() {
        $this->db        = (new Database())->getConnection();
        $this->userModel = new UserModel($this->db);
        SessionHelper::start();
    }

    public function login() {
        if (SessionHelper::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        $error = SessionHelper::getFlash('login_error');
        include 'app/views/auth/login.php';
    }

    public function doLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/Auth/login');
            exit;
        }
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            SessionHelper::setFlash('login_error', 'Vui lòng nhập đầy đủ thông tin.');
            header('Location: ' . BASE_URL . '/Auth/login');
            exit;
        }

        $user = $this->userModel->login($username, $password);
        if ($user) {
            SessionHelper::setUser($user);
            header('Location: ' . BASE_URL . '/');
            exit;
        } else {
            SessionHelper::setFlash('login_error', 'Tên đăng nhập hoặc mật khẩu không đúng.');
            header('Location: ' . BASE_URL . '/Auth/login');
            exit;
        }
    }

    public function register() {
        if (SessionHelper::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        $errors = SessionHelper::getFlash('register_errors') ?? [];
        $old    = SessionHelper::getFlash('register_old') ?? [];
        include 'app/views/auth/register.php';
    }

    public function doRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/Auth/register');
            exit;
        }
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $fullName = trim($_POST['full_name'] ?? '');
        $role     = trim($_POST['role'] ?? 'user');

        $result = $this->userModel->register($username, $email, $password, $fullName, $role);
        if ($result === true) {
            SessionHelper::setFlash('login_error', null);
            SessionHelper::setFlash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
            header('Location: ' . BASE_URL . '/Auth/login');
            exit;
        } else {
            SessionHelper::setFlash('register_errors', $result);
            SessionHelper::setFlash('register_old', ['username'=>$username,'email'=>$email,'full_name'=>$fullName]);
            header('Location: ' . BASE_URL . '/Auth/register');
            exit;
        }
    }

    public function logout() {
        SessionHelper::logout();
    }
}
