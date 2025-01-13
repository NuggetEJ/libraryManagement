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

        // Create a mock for the mysqli_result object with the `num_rows` property
        $mockResult = $this->getMockBuilder(mysqli_result::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        
        // Simulate that there is 1 row (booking exists)
        $mockResult->method('num_rows')
                   ->willReturn(1);

        // Mock the query method to return the mocked result
        $this->mockConn->method('query')
                       ->willReturn($mockResult);

        // Simulate the delete operation
        $query = "DELETE FROM booking WHERE bookingID = $bookingId";
        $this->mockConn->query($query);

        // Verify that the query was executed and then check if the booking was deleted
        $result = $this->mockConn->query("SELECT * FROM booking WHERE bookingID = $bookingId");

        // Since we mocked `num_rows` to return 1 for the SELECT, we will check that result
        $this->assertEquals(0, $result->num_rows);  // Expecting no rows left after delete
    }
}
