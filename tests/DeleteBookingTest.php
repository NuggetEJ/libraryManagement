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

        // Create a mock for mysqli_result class
        $mockResult = $this->getMockBuilder(mysqli_result::class)
                           ->onlyMethods(['num_rows'])  // Mock only the num_rows method
                           ->getMock();

        // Mock num_rows to return 0 (no rows after deletion)
        $mockResult->method('num_rows')->willReturn(0);

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
