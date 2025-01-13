<?php
use PHPUnit\Framework\TestCase;

class CreateBookingTest extends TestCase
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

    public function testCreateBooking()
    {
        $roomDescId = 10; // Use a valid room_descID from your database
        $userId = 1;     // Use a valid userID from your database
        $date = "2025-01-15";
        $startTime = "10:00:00";
        $endTime = "11:00:00";
        $capacity = 10;
        $purpose = "Meeting";
        $roomName = "Conference Room";

        // Check if the room_descID exists in the room_description table
        $roomCheck = $this->conn->query("SELECT * FROM room_description WHERE room_descID = $roomDescId");

        if ($roomCheck->num_rows == 0) {
          $this->fail('The room_descID does not exist in the room_description table.');
        }
      
        // Check if the booking already exists
        $result = $this->conn->query("SELECT * FROM booking WHERE room_descID = $roomDescId AND userID = $userId AND date = '$date'");
        
        if ($result->num_rows > 0) {
            // Skip the test or fail gracefully if a duplicate exists
            $this->markTestSkipped('Booking already exists for this room on this date.');
        } else {
            // Insert a new booking if no duplicate exists
            $query = "INSERT INTO booking (room_descID, userID, date, start_time, end_time, capacity, purpose, room_name) 
                      VALUES ($roomDescId, $userId, '$date', '$startTime', '$endTime', $capacity, '$purpose', '$roomName')";
            $this->conn->query($query);

            // Verify the booking was created
            $result = $this->conn->query("SELECT * FROM booking WHERE room_descID = $roomDescId AND userID = $userId AND date = '$date'");
            $this->assertEquals(1, $result->num_rows); // Expecting one row to be found
        }
    }
}
