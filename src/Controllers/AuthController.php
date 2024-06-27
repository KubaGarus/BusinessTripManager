<?php
namespace Controllers;

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Utils/Response.php';

use Models\User;
use Utils\Response;

class AuthController {
    private $user;
    private $response;

    public function __construct()
    {
        $this->user = new User();
        $this->response = new Response();
    }

    public function showLoginForm() {
        require_once __DIR__ . '/../Views/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($this->user->authenticate($username, $password)) {
                $_SESSION['username'] = $username;
                header("Location: index.php?action=dashboard");
                exit();
            } else {
                $this->response->error = true;
                $this->response->message = "Nieprawidłowa nazwa użytkownika lub hasło.";
                $this->response->cssClass = 'error';
            }
        }
        require_once __DIR__ . '/../Views/login.php';
    }

    public function showRegisterForm() {
        require_once __DIR__ . '/../Views/register.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $firstname = $_POST['firstname'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                $this->response->error = true;
                $this->response->message = "Hasła nie są zgodne.";
                $this->response->cssClass = 'error';
            } else {
                if ($this->user->register($username, $password, $firstname, $surname, $email)) {
                    $this->response->error = false;
                    $this->response->message = "Rejestracja zakończona sukcesem. Możesz się teraz zalogować.";
                    $this->response->cssClass = 'success';
                } else {
                    $this->response->error = true;
                    $this->response->message = "Wystąpił błąd podczas rejestracji.";
                    $this->response->cssClass = 'error';
                }
            }
        }
        require_once __DIR__ . '/../Views/register.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
}
?>
