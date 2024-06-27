<?php
require_once __DIR__ . "/../Models/BusinessTrip.php";

class BusinessTripController
{
    private BusinessTrip $businessTrip;
    public function __construct()
    {
        $this->businessTrip = new BusinessTrip;
    }

    public function createBusinessTripHandler(int $user_id, array $data, array $files)
    {
        $business_trip_id = $this->businessTrip->createBusinessTrip($user_id);
        $this->businessTrip->saveBasicData($data["trip-purpose"], $data["transportation-mode"], $business_trip_id);
        $this->seveExpenseHandler($data, $files, $business_trip_id);
    }

    public function getBusinessTrips(int $user_id): array
    {
        return $this->businessTrip->getAllBusinessTrips($user_id);
    }

    private function seveExpenseHandler(array $data, array $files, int $businessTripID)
    {
        $params = [];
        foreach ($data['expense-date'] as $key => $expenseDate) {
            if ($expenseDate !== "") {
                if(isset($files['expense-attachment']) && isset($files['expense-attachment']['name'][$key]) && $files['expense-attachment']['name'][$key] !== "") {
                    $attachment_id = $this->businessTrip->saveAttachment($files['expense-attachment']['name'][$key], $files['expense-attachment']['type'][$key], $files['expense-attachment']['size'][$key], $files['expense-attachment']['tmp_name'][$key]);
                }

                $this->businessTrip->saveExpenses($expenseDate, $data['expense-amount'][$key], $data['expense-description'][$key], $businessTripID, $attachment_id);
            }
        }
    }
    
    public function deleteBusinessTripHandler(int $businessTripID)
    {
        $this->businessTrip->deleteAllBusinessTripData($businessTripID);
    }
}