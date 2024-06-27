<?php
require_once __DIR__ . '/init.php';

use Controllers\AuthController;
use Controllers\DashboardController;
use Controllers\AdminController;
use Controllers\ManagerController;

$action = $_GET['action'] ?? 'login';
$method = $_SERVER['REQUEST_METHOD'];

switch ($action) {
    case 'login':
        $controller = new AuthController();
        if ($method === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;
    case 'register':
        $controller = new AuthController();
        if ($method === 'POST') {
            $controller->register();
        } else {
            $controller->showRegisterForm();
        }
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'dashboard':
        $controller = new DashboardController();
        $controller->showDashboard();
        break;
    case 'admin':
        $controller = new AdminController();
        if ($controller->isAdmin()) {
            $controller->showAdminPanel();
        } else {
            $controller = new AuthController();
            $controller->showLoginForm();
        }
        break;
    case 'manager':
        $controller = new ManagerController();
        $controller->showAllBusinessTrips();
        break;
    default:
        $controller = new AuthController();
        $controller->showLoginForm();
        break;
}