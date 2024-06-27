<?php
require_once __DIR__ . "/../Models/User.php";
require_once __DIR__ . "/../Models/Admin.php";

class AdminController
{
    private Admin $admin;
    public function __construct()
    {
        $this->admin = new Admin;
    }

    /**
     * Get all users within admin
     *
     * @return array
     */
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
}