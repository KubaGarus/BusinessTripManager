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
        $this->db = new PDO($this->config['dsn'], $this->config['username'], $this->config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_EMULATE_PREPARES => false]);
    }

    public function createBusinessTrip(int $user_id): int
    {
        $stmt = $this->db->prepare('INSERT INTO business_trips (user_id, intrudaction_date, acceptance_date,status) VALUES (:user_id, :intrudaction_date, :acceptance_date, :status) RETURNING business_trip_id');
        $stmt->execute(["user_id" => $user_id, "intrudaction_date" => date("Y-m-d"), "acceptance_date" => NULL, "status" => 1]);
        return $stmt->fetchColumn();
    }

    public function getAllBusinessTrips(int $user_id): array
    {
        $query = $this->db->prepare('SELECT * FROM business_trips WHERE user_id = :user_id');
        $query->execute(["user_id" => $user_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveExpenses(string $date, float $cost, string $note, int $businessTripID, int $attachmentID)
    {
        $stmt = $this->db->prepare('INSERT INTO business_trips_expenses (expense_date, cost, note, business_trip_id, attachment_id) VALUES (:expense_date, :cost, :note, :business_trip_id, :attachment_id)');
        return $stmt->execute(["expense_date" => $date, "cost" => $cost, "note" => $note, "business_trip_id" => $businessTripID, "attachment_id" => $attachmentID]);
    }

    public function saveAttachment(string $fileName, string $fileType, int $fileSize, string $tmpName): int
    {
        $stmt = $this->db->prepare('INSERT INTO business_trips_expenses_attachments (name, size, type, content) VALUES (:name, :size, :type, :content) RETURNING attachment_id');
        $stmt->execute(["name" => $fileName, "size" => $fileSize, "type" => $fileType, "content" => 1]);
        //file_get_contents($tmpName)
        return $stmt->fetchColumn();
    }

    public function saveBasicData(string $purpose, string $transport, int $businessTripID)
    {
        $stmt = $this->db->prepare('INSERT INTO business_trips_basic_data (purpose, transport, business_trip_id) VALUES (:purpose, :transport, :business_trip_id)');
        return $stmt->execute(["purpose" => $purpose, "transport" => $transport, "business_trip_id" => $businessTripID]);
    }
}