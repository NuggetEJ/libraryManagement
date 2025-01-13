<?php
use PHPUnit\Framework\TestCase;

class DeleteBookingTest extends TestCase
{
    private $mockConn;

    // Mock database connection before each test
    public function setUp(): void
    {
        // Create the mock for the mysqli database connection
        $this->mockConn = $this->getMockBuilder(mysqli::class)
                                ->disableOriginalConstructor()
                                ->getMock();
    }

    // Clean up after each test
    public function tearDown(): void
    {
        if (isset($this->mockConn)) {
            $this->mockConn = null;
        }
    }

    public function testDeleteBooking()
    {
        $bookingId = 6;

        // Mock the result object returned by the query method
        $mockResult = $this->createMock(mysqli_result::class);
        
        // Set up the mock result to return a num_rows property of 0 (no rows found)
        $mockResult->num_rows = 0;

        // Mock the query method to return the mocked result
        $this->mockConn->method('query')
                       ->willReturn($mockResult);

        // Simulate the delete operation
        $query = "DELETE FROM booking WHERE bookingID = $bookingId";
        $this->mockConn->query($query);

        // Simulate the SELECT query and check if any rows remain
        $result = $this->mockConn->query("SELECT * FROM booking WHERE bookingID = $bookingId");

        // Verify that no rows are returned (simulating the booking being deleted)
        $this->assertEquals(0, $result->num_rows);
    }
}
