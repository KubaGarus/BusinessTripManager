<?php
session_start();

require_once __DIR__ . "/../src/Controllers/AuthController.php";
require_once __DIR__ . "/../src/Controllers/DashboardController.php";
$controller = new AuthController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';
} else {
    $action = $_GET['action'] ?? 'login';
}

switch ($action) {
    case 'login':
        $controller->login();
        break;
    case 'register':
        $controller->register();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'dashboard':
        $controller = new DashboardController();
        $controller->showDashboard();
        break;
    case 'admin':
        if ($controller->isAdmin()) {
            $controller = new DashboardController();
            $controller->showAdminPanel();
        } else {
            $controller->login();
        }
        break;
    default:
        $controller->login();
        break;
}