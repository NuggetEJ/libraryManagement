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

        // First, mock the DELETE query execution
        $this->mockDatabase->method('query')
                          ->with("DELETE FROM booking WHERE bookingID = $bookingId")
                          ->willReturn(true);  // Simulate successful deletion

        // Simulate the deletion of a booking
        $sqlDelete = "DELETE FROM booking WHERE bookingID = $bookingId";
        $this->mockDatabase->query($sqlDelete);

        // Mock the query method to return the mocked result
        $this->mockConn->method('query')
                       ->willReturn($mockResult);

        // Simulate fetching the deleted booking (expect no result)
        $sqlFetch = "SELECT * FROM booking WHERE bookingID = $bookingId";
        $resultFetch = $this->mockDatabase->query($sqlFetch);
        $row = $resultFetch->fetch_assoc();

        $this->assertNull($row);  // Expecting no rows since booking is deleted
    }

    private function createMockResult(array $data)
    {
        // Mock the mysqli_result class
        $mockResult = $this->getMockBuilder(mysqli_result::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        // Verify that no rows are returned (simulating the booking being deleted)
        $this->assertEquals(0, $result->num_rows);
    }
}
