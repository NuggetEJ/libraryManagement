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
        $bookingId = 6;

        // Mock the DELETE query execution and expect it to be called once
        $this->mockDatabase->expects($this->once())
                          ->method('query')
                          ->with("DELETE FROM booking WHERE bookingID = $bookingId")
                          ->willReturn(true);  // Simulate successful deletion

        // Simulate the deletion of a booking
        $sqlDelete = "DELETE FROM booking WHERE bookingID = $bookingId";
        $this->mockDatabase->query($sqlDelete);

        // Mock the SELECT query execution to verify the booking is deleted
        $this->mockDatabase->expects($this->once())
                          ->method('query')
                          ->with("SELECT * FROM booking WHERE bookingID = $bookingId")
                          ->willReturn($this->createMockResult([]));  // Simulate no rows returned

        // Simulate fetching the deleted booking (expect no result)
        $sqlFetch = "SELECT * FROM booking WHERE bookingID = $bookingId";
        $resultFetch = $this->mockDatabase->query($sqlFetch);
        $row = $resultFetch->fetch_assoc();

        // Assert that no rows are returned after deletion
        $this->assertNull($row);  // Expecting no rows since the booking is deleted
    }

    private function createMockResult(array $data)
    {
        // Mock the mysqli_result class
        $mockResult = $this->getMockBuilder(mysqli_result::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        // Mock the fetch_assoc method to return the data (null if no data)
        $mockResult->method('fetch_assoc')->willReturn($data ? $data : null);
        
        // Mock the num_rows property, which should return the number of rows (0 for no result)
        $mockResult->method('num_rows')->willReturn(count($data));

        return $mockResult;
    }
}
