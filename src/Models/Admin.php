<?php
require_once __DIR__ . "/../../config/DatabaseConfig.php";

class Admin
{
    private PDO $db;
    private DatabaseConfig $databaseConfig;
    private array $config;

    public function __construct()
    {
        $this->databaseConfig = new DatabaseConfig;
        $this->config = $this->databaseConfig->getConfig();
        $this->db = new PDO($this->config['dsn'], $this->config['username'], $this->config['password']);
    }

    public function deleteUser(int $user_id): void
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE user_id = :user_id');
        $stmt->execute(["user_id" => $user_id]);
    }
    
    public function getUsers()
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE function > 0');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}