<?php
namespace Controllers;

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Utils/Response.php';

use Models\User;
use Utils\Response;

class DashboardController {
    public function showDashboard() {
        if (isset($_SESSION['username'])) {
            $user = new User();
            $userInfo = $user->getUserInfo($_SESSION['username']);
            $_SESSION['function'] = $userInfo['function'];
            $_SESSION['user_id'] = $userInfo['user_id'];
            require_once __DIR__ . '/../Views/dashboard.php';
        } else {
            header('Location: index.php?action=login');
        }
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
    }

    public function showAdminPanel()
    {
        require_once __DIR__ . '/../Views/admin.php';
    }
}
?>
