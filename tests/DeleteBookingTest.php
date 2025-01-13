<?php
use PHPUnit\Framework\TestCase;

class DeleteBookingTest extends TestCase
{
    private $mockConn;

    public function setUp(): void
    {
        // Mock the database connection
        $this->mockConn = $this->getMockBuilder(mysqli::class)
                                ->disableOriginalConstructor()
                                ->getMock();
    }

    public function tearDown(): void
    {
        if (isset($this->mockConn)) {
            // Clean up after the test
            $this->mockConn = null;
        }
    }

    public function testDeleteBooking()
    {
        $bookingId = 6;

        // Mock the result of the booking check
        $mockResult = $this->createMockResult(true);
        $this->mockConn->method('query')
                       ->willReturn($mockResult);

        // Simulate the delete operation
        $query = "DELETE FROM booking WHERE bookingID = $bookingId";
        $this->mockConn->query($query);

        // Verify the booking was deleted (mock result)
        $result = $this->mockConn->query("SELECT * FROM booking WHERE bookingID = $bookingId");
        $this->assertEquals(0, $result->num_rows);  // Expecting no rows left
    }

    // Helper to create mock result
    private function createMockResult($hasRows)
    {
        $mockResult = $this->getMockBuilder(mysqli_result::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        $mockResult->method('num_rows')
                   ->willReturn($hasRows ? 1 : 0);
        return $mockResult;
    }
}
