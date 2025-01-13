<?php
use PHPUnit\Framework\TestCase;

class DeleteBookingTest extends TestCase
{
    private $conn;

    // Set up the database connection before each test
    public function setUp(): void
    {
        $this->conn = new mysqli("localhost", "root", "", "library");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Tear down the connection after each test
    public function tearDown(): void
    {
        if (isset($this->conn)) {
            $this->conn->close();
        }
    }

    public function testDeleteBooking()
    {
        $bookingId = 6;

        // Delete the booking
        $query = "DELETE FROM booking WHERE bookingID = $bookingId"; 
        $this->conn->query($query);

        // Verify the booking was deleted
        $result = $this->conn->query("SELECT * FROM booking WHERE bookingID = $bookingId"); 
        $this->assertEquals(0, $result->num_rows);  // Expecting no rows left
    }
}
