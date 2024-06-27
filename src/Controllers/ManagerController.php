<?php
namespace Controllers;

require_once __DIR__ . '/../Models/BusinessTrip.php';

use Models\BusinessTrip;

class ManagerController
{
    private $businessTrip;

    public function __construct()
    {
        $this->businessTrip = new BusinessTrip();
    }

    /**
     * Show all business trips
     *
     * @return array
     */
    public function showAllBusinessTrips(): array
    {
        require_once __DIR__ . '/../Views/manager.php';
        return $this->businessTrip->getAllBusinessTripsForManager();
    }

    /**
     * Accept business trip handler
     *
     * @param integer $businessTripID
     * @return void
     */
    public function acceptBusinessTrip(int $businessTripID): void
    {   
        $this->businessTrip->updateStatus($businessTripID, 3);
    }

    /**
     * delete business trip handler
     *
     * @param integer $businessTripID
     * @return void
     */
    public function deleteBusinessTrip(int $businessTripID): void
    {
        $this->businessTrip->deleteAllBusinessTripData($businessTripID);
    }
}
?>
