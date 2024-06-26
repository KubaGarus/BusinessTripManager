<?php
require_once __DIR__ . "/../Models/BusinessTrip.php";

class BusinessTripController
{
    private BusinessTrip $businessTrip;
    public function __construct()
    {
        $this->businessTrip = new BusinessTrip;
    }

    public function createBusinessTripHandler(int $user_id)
    {
        $x = $this->businessTrip->createBusinessTrip($user_id);
    }

    public function getBusinessTrips(int $user_id): array
    {
        return $this->businessTrip->getAllBusinessTrips($user_id);
    }
}