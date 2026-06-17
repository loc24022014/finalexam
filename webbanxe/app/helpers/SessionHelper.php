<?php
// app/helpers/SessionHelper.php
if (!defined('BASE_URL')) {
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = str_replace('/index.php', '', $scriptName);
    define('BASE_URL', $basePath);
}
class SessionHelper {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn() {
        self::start();
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin() {
        self::start();
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/Auth/login');
            exit;
        }
    }

    public static function requireAdmin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/Auth/login');
            exit;
        }
        if (!self::isAdmin()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public static function setUser($user) {
        self::start();
        $_SESSION['user_id']       = $user->id;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_role']     = $user->role;
        $_SESSION['user_fullname'] = $user->full_name ?? $user->username;
    }

    public static function logout() {
        self::start();
        session_destroy();
        header('Location: ' . BASE_URL . '/Auth/login');
        exit;
    }

    public static function setFlash($key, $message) {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key) {
        self::start();
        $msg = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $msg;
    }

    public static function get($key) {
        self::start();
        return $_SESSION[$key] ?? null;
    }
}
