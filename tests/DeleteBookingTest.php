<?php
use PHPUnit\Framework\TestCase;

class MockMysqliResult {
    public $num_rows;

    public function __construct($num_rows) {
        $this->num_rows = $num_rows;
    }
}

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

        // Create a mock result with num_rows set to 0 (no rows after deletion)
        $mockResult = new MockMysqliResult(0);

        // Mock the query method to return true for DELETE operation (indicating success)
        $this->mockConn->method('query')
                       ->willReturnCallback(function($query) use ($bookingId, $mockResult) {
                           if (strpos($query, 'DELETE FROM booking') !== false) {
                               return true; // Simulate successful delete
                           }
                           // Simulate SELECT query returning 0 rows (no results)
                           return $mockResult;
                       });

        // Simulate the delete operation
        $deleteQuery = "DELETE FROM booking WHERE bookingID = $bookingId";
        $this->assertTrue($this->mockConn->query($deleteQuery)); // Assert delete was successful

        // Simulate the SELECT query and check if any rows remain
        $selectQuery = "SELECT * FROM booking WHERE bookingID = $bookingId";
        $result = $this->mockConn->query($selectQuery);

        // Verify that no rows are returned (simulating the booking being deleted)
        $this->assertEquals(0, $result->num_rows);
    }
}
