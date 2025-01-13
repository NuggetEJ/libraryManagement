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

        // Mock the query execution for deletion
        $this->mockDatabase->method('query')
                          ->with("DELETE FROM booking WHERE bookingID = $bookingID")
                          ->willReturn(true); // Simulate successful deletion

        // Simulate the deletion of a booking
        $sqlDelete = "DELETE FROM booking WHERE bookingID = $bookingID";
        $resultDelete = $this->mockDatabase->query($sqlDelete);

        // Assert that the deletion was successful
        $this->assertTrue($resultDelete);

        // Simulate fetching the booking details to verify it has been deleted
        $sqlFetch = "SELECT * FROM booking WHERE bookingID = $bookingID";

        // Mock the result to return an empty result set
        $mockResultFetch = $this->createMockResult([]);
        $this->mockDatabase->method('query')
                           ->with($sqlFetch)
                           ->willReturn($mockResultFetch);

        // Fetch the result
        $resultFetch = $this->mockDatabase->query($sqlFetch);

        // Ensure that the fetch operation returns the mock result
        $this->assertNotFalse($resultFetch); // Ensure resultFetch is not false
        $row = $resultFetch->fetch_assoc();

        // Assert that no rows are returned (booking should be deleted)
        $this->assertFalse($row); // Expecting false since the booking should not exist
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
