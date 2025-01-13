<?php
use PHPUnit\Framework\TestCase;

class CreateBookingTest extends TestCase
{
    private $conn;

    public function setUp(): void
    {
        $this->conn = new mysqli(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );

        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }

        // Begin transaction for cleanup
        $this->conn->begin_transaction();
    }

    public function tearDown(): void
    {
        if (isset($this->conn)) {
            $this->conn->rollback(); // Rollback changes
            $this->conn->close();
        }
    }

    public function testCreateBooking()
    {
        $roomDescId = 10;
        $userId = 1;
        $date = "2025-01-15";
        $startTime = "10:00:00";
        $endTime = "11:00:00";
        $capacity = 10;
        $purpose = "Meeting";
        $roomName = "Conference Room";

        // Check if the room_descID exists
        $stmt = $this->conn->prepare("SELECT * FROM room_description WHERE room_descID = ?");
        $stmt->bind_param("i", $roomDescId);
        $stmt->execute();
        $roomCheck = $stmt->get_result();

        if ($roomCheck->num_rows == 0) {
            $this->fail('The room_descID does not exist in the room_description table.');
        }

        // Check if the booking already exists
        $stmt = $this->conn->prepare("SELECT * FROM booking WHERE room_descID = ? AND userID = ? AND date = ?");
        $stmt->bind_param("iis", $roomDescId, $userId, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $this->fail('Booking already exists for this room on this date.');
        } else {
            // Insert booking
            $stmt = $this->conn->prepare(
                "INSERT INTO booking (room_descID, userID, date, start_time, end_time, capacity, purpose, room_name) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("iisssiss", $roomDescId, $userId, $date, $startTime, $endTime, $capacity, $purpose, $roomName);
            $stmt->execute();

            // Verify the booking was created
            $stmt = $this->conn->prepare("SELECT * FROM booking WHERE room_descID = ? AND userID = ? AND date = ?");
            $stmt->bind_param("iis", $roomDescId, $userId, $date);
            $stmt->execute();
            $result = $stmt->get_result();

            $this->assertEquals(1, $result->num_rows);
        }
    }
}
