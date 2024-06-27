<?php
namespace Controllers;

require_once __DIR__ . '/../Models/BusinessTrip.php';

use Models\BusinessTrip;

class BusinessTripController
{
    private $businessTrip;
    public function __construct()
    {
        $this->businessTrip = new BusinessTrip();
    }

    /**
     * Create new business trip handler
     *
     * @param integer $user_id
     * @param array $data
     * @param array $files
     * @return void
     */
    public function createBusinessTripHandler(int $user_id, array $data, array $files)
    {
        $business_trip_id = $this->businessTrip->createBusinessTrip($user_id);
        $this->businessTrip->saveBasicData($data["trip-purpose"], $data["transportation-mode"], $business_trip_id);
        $this->saveExpenseHandler($data, $files, $business_trip_id);
    }

    /**
     * Get business trips of one employee
     *
     * @param integer $user_id
     * @return array
     */
    public function getBusinessTrips(int $user_id): array
    {
        return $this->businessTrip->getAllBusinessTrips($user_id);
    }

    /**
     * Save expenses handler
     *
     * @param array $data
     * @param array $files
     * @param integer $businessTripID
     * @return void
     */
    private function saveExpenseHandler(array $data, array $files, int $businessTripID)
    {
        foreach ($data['expense-date'] as $key => $expenseDate) {
            if ($expenseDate !== "") {
                $attachment_id = 0;
                if(isset($files['expense-attachment']) && isset($files['expense-attachment']['name'][$key]) && $files['expense-attachment']['name'][$key] !== "") {
                    $attachment_id = $this->businessTrip->saveAttachment($files['expense-attachment']['name'][$key], $files['expense-attachment']['type'][$key], $files['expense-attachment']['size'][$key], $files['expense-attachment']['tmp_name'][$key]);
                }

                $this->businessTrip->saveExpenses($expenseDate, $data['expense-amount'][$key], $data['expense-description'][$key], $businessTripID, $attachment_id);
            }
        }
    }

    /**
     * Delete business trip handler
     *
     * @param integer $businessTripID
     * @return void
     */
    public function deleteBusinessTripHandler(int $businessTripID)
    {
        $this->businessTrip->deleteAllBusinessTripData($businessTripID);
    }

    /**
     * Get business trips by ID
     *
     * @param integer $tripId
     * @return array
     */
    public function getBusinessTripById(int $tripId): array
    {
        return $this->businessTrip->getBusinessTripById($tripId);
    }
}