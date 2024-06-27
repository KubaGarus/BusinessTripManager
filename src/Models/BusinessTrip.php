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
        $query = $this->db->prepare('
            SELECT 
                bt.business_trip_id,
                bt.user_id,
                bt.intrudaction_date,
                bt.acceptance_date,
                bt.status,
                btb.purpose,
                btb.transport,
                exp.expense_id,
                exp.expense_date,
                exp.cost,
                exp.note,
                exp.attachment_id,
                att.name AS attachment_name,
                att.size AS attachment_size,
                att.type AS attachment_type,
                att.content AS attachment_content
            FROM business_trips bt
            LEFT JOIN business_trips_basic_data btb ON bt.business_trip_id = btb.business_trip_id
            LEFT JOIN business_trips_expenses exp ON bt.business_trip_id = exp.business_trip_id
            LEFT JOIN business_trips_expenses_attachments att ON exp.attachment_id = att.attachment_id
            WHERE bt.user_id = :user_id
        ');
        $query->execute(["user_id" => $user_id]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Procesowanie wyników, aby zgrupować wydatki i załączniki według delegacji
        $businessTrips = [];
        foreach ($result as $row) {
            $tripId = $row['business_trip_id'];

            // Sprawdź, czy delegacja już istnieje w wyniku
            if (!isset($businessTrips[$tripId])) {
                $businessTrips[$tripId] = [
                    'business_trip_id' => $row['business_trip_id'],
                    'user_id' => $row['user_id'],
                    'intrudaction_date' => $row['intrudaction_date'],
                    'acceptance_date' => $row['acceptance_date'],
                    'status' => $row['status'],
                    'purpose' => $row['purpose'],
                    'transport' => $row['transport'],
                    'expenses' => []
                ];
            }

            // Dodaj wydatek, jeśli istnieje
            if ($row['expense_id']) {
                $expense = [
                    'expense_id' => $row['expense_id'],
                    'expense_date' => $row['expense_date'],
                    'cost' => $row['cost'],
                    'note' => $row['note'],
                    'attachment' => null
                ];

                // Dodaj załącznik, jeśli istnieje
                if ($row['attachment_id']) {
                    $expense['attachment'] = [
                        'attachment_id' => $row['attachment_id'],
                        'name' => $row['attachment_name'],
                        'size' => $row['attachment_size'],
                        'type' => $row['attachment_type'],
                        'content' => $row['attachment_content']
                    ];
                }

                $businessTrips[$tripId]['expenses'][] = $expense;
            }
        }

        return array_values($businessTrips);
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