<?php
use PHPUnit\Framework\TestCase;
use Controllers\AuthController;
use Models\User;
use Utils\Response;

class AuthControllerTest extends TestCase
{
    private $authController;
    private $user;
    private $response;

    protected function setUp(): void
    {
        $this->authController = new AuthController();
        $this->user = $this->createMock(User::class);
        $this->response = $this->createMock(Response::class);
    }

    public function testShowLoginForm()
    {
        $this->expectOutputRegex('/<form id="login-form"/');
        $this->authController->showLoginForm();
    }

    public function testLoginSuccess()
    {
        $_POST['username'] = 'testuser';
        $_POST['password'] = 'password';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->user->method('authenticate')
            ->willReturn(['username' => 'testuser']);

        $this->authController->login();

        $this->assertSame('testuser', $_SESSION['username']);
    }

    public function testLoginFailure()
    {
        $_POST['username'] = 'invaliduser';
        $_POST['password'] = 'wrongpassword';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->user->method('authenticate')
            ->willReturn(false);

        $this->authController->login();

        $this->assertTrue($this->authController->response->error);
        $this->assertSame('Nieprawidłowa nazwa użytkownika lub hasło.', $this->authController->response->message);
    }
}
