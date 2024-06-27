<?php
namespace Models;

require_once __DIR__ . '/../../config/DatabaseConfig.php';

use PDO;

class BusinessTrip {
    private $db;
    private $databaseConfig;
    private $config;

    public function __construct()
    {
        $this->databaseConfig = new \DatabaseConfig();
        $this->config = $this->databaseConfig->getConfig();
        $this->db = new PDO($this->config['dsn'], $this->config['username'], $this->config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => false, PDO::ATTR_EMULATE_PREPARES => false]);
    }

    public function createBusinessTrip(int $user_id): int
    {
        $stmt = $this->db->prepare('INSERT INTO business_trips (user_id, intrudaction_date, acceptance_date, status) VALUES (:user_id, :intrudaction_date, :acceptance_date, :status) RETURNING business_trip_id');
        $stmt->execute(["user_id" => $user_id, "intrudaction_date" => date("Y-m-d"), "acceptance_date" => NULL, "status" => 1]);
        return $stmt->fetchColumn();
    }

    public function getAllBusinessTrips(int $user_id): array
    {
        $query = $this->db->prepare('SELECT * FROM business_trips WHERE user_id = :user_id');
        $query->execute(["user_id" => $user_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBusinessTripsForManager(): array
    {
        $query = $this->db->prepare(
            'SELECT bt.*, u.firstname, u.surname
            FROM business_trips bt
            JOIN users u ON bt.user_id = u.user_id'
        );
        $query->execute();
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
        $stmt->execute(["name" => $fileName, "size" => $fileSize, "type" => $fileType, "content" => 1]); // file_get_contents($tmpName) może być użyty do odczytu zawartości
        return $stmt->fetchColumn();
    }

    public function saveBasicData(string $purpose, string $transport, int $businessTripID)
    {
        $stmt = $this->db->prepare('INSERT INTO business_trips_basic_data (purpose, transport, business_trip_id) VALUES (:purpose, :transport, :business_trip_id)');
        return $stmt->execute(["purpose" => $purpose, "transport" => $transport, "business_trip_id" => $businessTripID]);
    }

    public function deleteAllBusinessTripData(int $businessTripID): void
    {
        $param = ["business_trip_id" => $businessTripID];
        $queries = [
            "DELETE FROM business_trips_expenses_attachments
                 USING business_trips_expenses
                 WHERE business_trips_expenses_attachments.attachment_id = business_trips_expenses.attachment_id
                 AND business_trips_expenses.business_trip_id = :business_trip_id",
            "DELETE FROM business_trips_expenses WHERE business_trip_id = :business_trip_id",
            "DELETE FROM business_trips_basic_data WHERE business_trip_id = :business_trip_id",
            "DELETE FROM business_trips WHERE business_trip_id = :business_trip_id"
        ];

        foreach ($queries as $query) {
            $stmt = $this->db->prepare($query);
            $stmt->execute($param);
        }
    }

    public function updateStatus(int $businessTripID, int $status)
    {
        $stmt = $this->db->prepare("UPDATE business_trips SET status = :status, acceptance_date = :acceptance_date WHERE business_trip_id = :business_trip_id");
        $stmt->execute(["status" => $status, "acceptance_date" => date("Y-m-d"), "business_trip_id" => $businessTripID]);
    }

    public function getBusinessTripById(int $tripId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM business_trips WHERE business_trip_id = :tripId');
        $stmt->execute(['tripId' => $tripId]);
        $trip = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare('SELECT * FROM business_trips_expenses WHERE business_trip_id = :tripId');
        $stmt->execute(['tripId' => $tripId]);
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['trip' => $trip, 'expenses' => $expenses];
    }

}
?>
