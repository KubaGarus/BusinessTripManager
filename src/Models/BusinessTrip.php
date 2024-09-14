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

    /**
     * Create business trip
     *
     * @param integer $user_id
     * @return integer
     */
    public function createBusinessTrip(int $user_id): int
    {
        $stmt = $this->db->prepare('INSERT INTO business_trips (user_id, introduction_date, acceptance_date, status) VALUES (:user_id, :introduction_date, :acceptance_date, :status) RETURNING business_trip_id');
        $stmt->execute(["user_id" => $user_id, "introduction_date" => date("Y-m-d"), "acceptance_date" => NULL, "status" => 1]);
        return $stmt->fetchColumn();
    }

    /**
     * Get all of existing busienss trip user data
     *
     * @param integer $user_id
     * @return array
     */
    public function getAllBusinessTrips(int $user_id): array
    {
        $query = $this->db->prepare('
            SELECT * FROM  business_trips_informations_view WHERE user_id = :user_id
        ');
        $query->execute(["user_id" => $user_id]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC) ?? [];

        $businessTrips = [];
        foreach ($result as $row) {
            $tripId = $row['business_trip_id'];

            if (!isset($businessTrips[$tripId])) {
                $businessTrips[$tripId] = [
                    'business_trip_id' => $row['business_trip_id'],
                    'user_id' => $row['user_id'],
                    'introduction_date' => $row['introduction_date'],
                    'acceptance_date' => $row['acceptance_date'],
                    'status' => $row['status'],
                    'purpose' => $row['purpose'],
                    'transport' => $row['transport'],
                    'expenses' => []
                ];
            }

            if ($row['expense_id']) {
                $expense = [
                    'expense_id' => $row['expense_id'],
                    'expense_date' => $row['expense_date'],
                    'cost' => $row['cost'],
                    'note' => $row['note']
                ];

                $businessTrips[$tripId]['expenses'][] = $expense;
            }
        }

        return array_values($businessTrips);
    }

    /**
     * Get business trips for manager
     *
     * @return array
     */
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

    /**
     * save expenses
     *
     * @param string $date
     * @param float $cost
     * @param string $note
     * @param integer $businessTripID
     * @return void
     */
    public function saveExpenses(string $date, float $cost, string $note, int $businessTripID): void
    {
        $stmt = $this->db->prepare('INSERT INTO business_trips_expenses (expense_date, cost, note, business_trip_id) VALUES (:expense_date, :cost, :note, :business_trip_id)');
        $stmt->execute(["expense_date" => $date, "cost" => $cost, "note" => $note, "business_trip_id" => $businessTripID]);
    }

    /**
     * save basic data like purpose and transport
     *
     * @param string $purpose
     * @param string $transport
     * @param integer $businessTripID
     * @return void
     */
    public function saveBasicData(string $purpose, string $transport, int $businessTripID): void
    {
        $stmt = $this->db->prepare('INSERT INTO business_trips_basic_data (purpose, transport, business_trip_id) VALUES (:purpose, :transport, :business_trip_id)');
        $stmt->execute(["purpose" => $purpose, "transport" => $transport, "business_trip_id" => $businessTripID]);
    }

    /**
     * Delete all data
     *
     * @param integer $businessTripID
     * @return void
     */
    public function deleteAllBusinessTripData(int $businessTripID): void
    {
        $param = ["business_trip_id" => $businessTripID];
        $queries = [
            "DELETE FROM business_trips_expenses WHERE business_trip_id = :business_trip_id",
            "DELETE FROM business_trips_basic_data WHERE business_trip_id = :business_trip_id",
            "DELETE FROM business_trips WHERE business_trip_id = :business_trip_id"
        ];

        foreach ($queries as $query) {
            $stmt = $this->db->prepare($query);
            $stmt->execute($param);
        }
    }

    /**
     * update status of business trip
     *
     * @param integer $businessTripID
     * @param integer $status
     * @return void
     */
    public function updateStatus(int $businessTripID, int $status): void
    {
        $stmt = $this->db->prepare("UPDATE business_trips SET status = :status, acceptance_date = :acceptance_date WHERE business_trip_id = :business_trip_id");
        $stmt->execute(["status" => $status, "acceptance_date" => date("Y-m-d"), "business_trip_id" => $businessTripID]);
    }

    /**
     * Get businss trip by ID
     *
     * @param integer $tripId
     * @return array
     */
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