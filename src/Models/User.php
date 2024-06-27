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

    /**
     * Authenticate user
     *
     * @param string $username
     * @param string $password
     * @return array|boolean
     */
    public function authenticate(string $username, string $password): array|bool
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
        $stmt->execute(['username' => $username, 'password' => md5($password)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Register new user
     *
     * @param string $username
     * @param string $password
     * @param string $firstname
     * @param string $surname
     * @param string $email
     * @return boolean
     */
    public function register(string $username, string $password, string $firstname, string $surname, string $email): bool
    {
        $stmt = $this->db->prepare('INSERT INTO users (username, password, function, firstname, surname, mail_address) VALUES (:username, :password, :function, :firstname, :surname, :mail_address)');
        return $stmt->execute(['username' => $username, 'password' => md5($password), 'function' => 1, 'firstname' => $firstname, 'surname' => $surname, 'mail_address' => $email]);
    }

    /**
     * Get user info
     *
     * @param string $username
     * @return void
     */
    public function getUserInfo(string $username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
