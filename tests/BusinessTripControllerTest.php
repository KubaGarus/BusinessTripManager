<?php
use PHPUnit\Framework\TestCase;
use Controllers\BusinessTripController;
use Models\BusinessTrip;

class BusinessTripControllerTest extends TestCase
{
    private $businessTripController;
    private $businessTrip;

    protected function setUp(): void
    {
        $this->businessTrip = $this->createMock(BusinessTrip::class);
        $this->businessTripController = new BusinessTripController();
    }

    public function testCreateBusinessTripHandler()
    {
        $this->businessTrip->expects($this->once())
            ->method('createBusinessTrip')
            ->willReturn(1);

        $data = [
            'trip-purpose' => 'Business Meeting',
            'transportation-mode' => 'Plane'
        ];

        $files = [];

        $this->businessTripController->createBusinessTripHandler(1, $data, $files);

        $this->businessTrip->expects($this->once())
            ->method('saveBasicData')
            ->with($this->equalTo('Business Meeting'), $this->equalTo('Plane'), $this->equalTo(1));
    }

    public function testGetBusinessTrips()
    {
        $this->businessTrip->method('getAllBusinessTrips')
            ->willReturn([
                ['business_trip_id' => 1, 'purpose' => 'Meeting']
            ]);

        $trips = $this->businessTripController->getBusinessTrips(1);

        $this->assertCount(1, $trips);
        $this->assertSame('Meeting', $trips[0]['purpose']);
    }

    public function testDeleteBusinessTripHandler()
    {
        $this->businessTrip->expects($this->once())
            ->method('deleteAllBusinessTripData')
            ->with($this->equalTo(1));

        $this->businessTripController->deleteBusinessTripHandler(1);
    }
}
