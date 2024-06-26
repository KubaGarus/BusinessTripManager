<?php
require_once __DIR__ . "/../../config/DatabaseConfig.php";

class BusinessTrip {
    private PDO $db;
    private DatabaseConfig $databaseConfig;
    private array $config;

    public function __construct()
    {
        $this->databaseConfig = new DatabaseConfig;
        $this->config = $this->databaseConfig->getConfig();
        $this->db = new PDO($this->config['dsn'], $this->config['username'], $this->config['password']);
    }

    public function createBusinessTrip(int $user_id): bool
    {
        $stmt = $this->db->prepare('INSERT INTO business_trips (user_id, intrudaction_date, acceptance_date,status) VALUES (:user_id, :intrudaction_date, :acceptance_date, :status)');
        return $stmt->execute(["user_id" => $user_id, "intrudaction_date" => date("Y-m-d"), "acceptance_date" => NULL, "status" => 1]);
    }

    public function getAllBusinessTrips(int $user_id): array
    {
        $query = $this->db->prepare('SELECT * FROM business_trips WHERE user_id = :user_id');
        $query->execute(["user_id" => $user_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}