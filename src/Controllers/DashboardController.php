<?php

require_once __DIR__ . "/../Models/User.php";
require_once __DIR__ . "/../Utils/Response.php";

use Utils\Response;

class DashboardController {
    public function showDashboard() {
        if (isset($_SESSION['username'])) {
            $user = new User();
            $userInfo = $user->getUserInfo($_SESSION['username']);
            $_SESSION['function'] = $userInfo['function'];
            $_SESSION['user_id'] = $userInfo['user_id'];
            require '../src/Views/dashboard.php';
        } else {
            header('Location: index.php?action=login');
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php?action=login');
    }

    public function showAdminPanel()
    {
        require '../src/Views/admin.php';
    }
}
?>
