<?php
namespace Controllers;

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Admin.php';

use Models\Admin;
use Models\User;

class AdminController
{
    private Admin $admin;
    public function __construct()
    {
        $this->admin = new Admin();
    }

    public function getUsersList(): array
    {
        return $this->admin->getUsers();
    }

    public function deleteUserHandler(int $user_id): void
    {
        $this->admin->deleteUser($user_id);
    }

    public function updateUser(array $data)
    {
        $this->admin->update($data['firstname'], $data['surname'], $data['username'], $data['email'], $data['function'], $data['user_id']);

        if ($data['password'] !== "" && $data['confirm_password'] !== "" && $data['password'] === $data['confirm_password']) {
            $this->admin->updatePassword($data['password'], $_POST['user_id']);
        }
    }

    public function isAdmin()
    {
        return isset($_SESSION['function']) && $_SESSION['function'] === -9;
    }

    public function showAdminPanel()
    {
        require __DIR__ . '/../Views/admin.php';
    }

    public function showLoginForm()
    {
        require_once __DIR__ . '/../Views/login.php';
    }
}
?>
