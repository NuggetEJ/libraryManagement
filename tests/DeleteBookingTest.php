<?php
use PHPUnit\Framework\TestCase;

class DeleteBookingTest extends TestCase
{
    private $mockDatabase;

    protected function setUp(): void
    {
        // Create a mock database connection
        $this->mockDatabase = $this->getMockBuilder(mysqli::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();
    }

    public function testDeleteBooking()
    {
        // Mock the booking ID to be deleted
        $bookingID = 1;

        // Simulate the deletion query
        $this->mockDatabase->method('query')
                           ->will($this->returnCallback(function ($query) use ($bookingID) {
                               if ($query === "DELETE FROM booking WHERE bookingID = $bookingID") {
                                   return true; // Simulate successful deletion
                               }
                               if ($query === "SELECT * FROM booking WHERE bookingID = $bookingID") {
                                   return $this->createMockResult([]); // Simulate empty result for deleted booking
                               }
                               return false; // Default behavior
                           }));

        // Execute and test the DELETE query
        $sqlDelete = "DELETE FROM booking WHERE bookingID = $bookingID";
        $resultDelete = $this->mockDatabase->query($sqlDelete);
        $this->assertTrue($resultDelete);

        // Execute and test the SELECT query
        $sqlFetch = "SELECT * FROM booking WHERE bookingID = $bookingID";
        $resultFetch = $this->mockDatabase->query($sqlFetch);
        $this->assertNotFalse($resultFetch);

        // Assert that the booking was deleted (no rows returned)
        $row = $resultFetch->fetch_assoc();
        $this->assertFalse($row); // Expect no data for the deleted booking
    }

    private function createMockResult(array $data)
    {
        // Mock the mysqli_result class
        $mockResult = $this->getMockBuilder(mysqli_result::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        // Mock the fetch_assoc method to return the data
        $mockResult->method('fetch_assoc')->willReturn($data ? array_shift($data) : false);

        return $mockResult;
    }
}
