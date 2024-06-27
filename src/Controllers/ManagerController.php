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

    public function showAllBusinessTrips()
    {
        require_once __DIR__ . '/../Views/manager.php';
        return $businessTrips = $this->businessTrip->getAllBusinessTripsForManager();
    }

    public function acceptBusinessTrip(int $businessTripID)
    {   
        $this->businessTrip->updateStatus($businessTripID, 3);
    }

    public function deleteBusinessTrip(int $businessTripID)
    {
        $this->businessTrip->deleteAllBusinessTripData($businessTripID);
    }
}
?>
