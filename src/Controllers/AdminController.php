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

    /**
     * Get all of users list
     *
     * @return array
     */
    public function getUsersList(): array
    {
        return $this->admin->getUsers();
    }

    /**
     * Delete user handler
     *
     * @param integer $user_id
     * @return void
     */
    public function deleteUserHandler(int $user_id): void
    {
        $this->admin->deleteUser($user_id);
    }

    /**
     * Update existing user handler
     *
     * @param array $data
     * @return void
     */
    public function updateUser(array $data): void
    {
        $this->admin->update($data['firstname'], $data['surname'], $data['username'], $data['email'], $data['function'], $data['user_id']);

        if ($data['password'] !== "" && $data['confirm_password'] !== "" && $data['password'] === $data['confirm_password']) {
            $this->admin->updatePassword($data['password'], $_POST['user_id']);
        }
    }

    /**
     * Check if user have permission of admin
     *
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return isset($_SESSION['function']) && $_SESSION['function'] === -9;
    }

    /**
     * Show admin form
     *
     * @return void
     */
    public function showAdminPanel(): void
    {
        require __DIR__ . '/../Views/admin.php';
    }

    /**
     * Show login form
     *
     * @return void
     */
    public function showLoginForm(): void
    {
        require_once __DIR__ . '/../Views/login.php';
    }
}