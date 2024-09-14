<?php
use PHPUnit\Framework\TestCase;
use Controllers\AdminController;
use Models\Admin;

class AdminControllerTest extends TestCase
{
    private $adminController;
    private $admin;

    protected function setUp(): void
    {
        $this->admin = $this->createMock(Admin::class);
        $this->adminController = new AdminController();
    }

    public function testGetUsersList()
    {
        $this->admin->method('getUsers')
            ->willReturn([
                ['user_id' => 1, 'username' => 'admin1'],
                ['user_id' => 2, 'username' => 'user2']
            ]);

        $users = $this->adminController->getUsersList();

        $this->assertCount(2, $users);
        $this->assertSame('admin1', $users[0]['username']);
    }

    public function testDeleteUser()
    {
        $this->admin->expects($this->once())
            ->method('deleteUser')
            ->with($this->equalTo(1));

        $this->adminController->deleteUserHandler(1);
    }

    public function testUpdateUser()
    {
        $data = [
            'firstname' => 'John',
            'surname' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'function' => 1,
            'user_id' => 1
        ];

        $this->admin->expects($this->once())
            ->method('update')
            ->with(
                $this->equalTo('John'),
                $this->equalTo('Doe'),
                $this->equalTo('johndoe'),
                $this->equalTo('john@example.com'),
                $this->equalTo(1),
                $this->equalTo(1)
            );

        $this->adminController->updateUser($data);
    }
}
