<?php
namespace Models;

require_once __DIR__ . '/../../config/DatabaseConfig.php';

use PDO;

class User {
    private $db;
    private $databaseConfig;
    private $config;

    public function __construct()
    {
        $this->databaseConfig = new \DatabaseConfig();
        $this->config = $this->databaseConfig->getConfig();
        $this->db = new PDO($this->config['dsn'], $this->config['username'], $this->config['password']);
    }

    public function authenticate(string $username, string $password): array|bool
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
        $stmt->execute(['username' => $username, 'password' => md5($password)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register(string $username, string $password, string $firstname, string $surname, string $email): bool
    {
        $stmt = $this->db->prepare('INSERT INTO users (username, password, function, firstname, surname, mail_address) VALUES (:username, :password, :function, :firstname, :surname, :mail_address)');
        return $stmt->execute(['username' => $username, 'password' => md5($password), 'function' => 1, 'firstname' => $firstname, 'surname' => $surname, 'mail_address' => $email]);
    }

    public function getUserInfo($username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
