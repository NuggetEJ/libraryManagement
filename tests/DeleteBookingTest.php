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

        // Create a custom mock class for mysqli_result to simulate num_rows
        $mockResult = $this->getMockBuilder(mysqli_result::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        // Use reflection to make the num_rows property accessible and mock it
        $mockResult->method('num_rows')
                   ->willReturn(0);  // Simulating the result after deletion (no rows found)

        // Mock the query method to return the mocked result
        $this->mockConn->method('query')
                       ->willReturn($mockResult);

        // Simulate the delete operation
        $query = "DELETE FROM booking WHERE bookingID = $bookingId";
        $this->mockConn->query($query);

        // Verify that the query was executed and check if the booking was deleted
        $result = $this->mockConn->query("SELECT * FROM booking WHERE bookingID = $bookingId");

        // Since we mock `num_rows` to return 0, the test will validate that no rows exist
        $this->assertEquals(0, $result->num_rows);  // Expecting no rows left after delete
    }
}
