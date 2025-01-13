<?php
use PHPUnit\Framework\TestCase;

class RoomDescriptionTest extends TestCase
{
    private $mockDatabase;
    private $conn;

    protected function setUp(): void
    {
        // Create a mock database connection
        $this->mockDatabase = $this->getMockBuilder(mysqli::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();

        // Mock the connection
        $this->conn = $this->mockDatabase;
    }

    public function testInsertRoomDescription()
    {
        // Mock the POST data for inserting a new room description
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['room_name'] = 'Conference Room A';
        $_POST['description'] = 'A large conference room';
        $_POST['capacity'] = 50;
        $_POST['availability'] = 'Available';

        // Mock the query execution to return true for successful insertion
        $this->mockDatabase->method('query')
                           ->willReturn(true);

        // Call the function to insert the room description
        $sql = "INSERT INTO room_description (room_name, description, capacity, availability)
                VALUES ('Conference Room A', 'A large conference room', 50, 'Available')";
        $status = $this->updateDbTable($this->conn, $sql);

        // Assert that the insertion was successful
        $this->assertTrue($status);
    }

    public function testUpdateRoomDescription()
    {
        // Mock the POST data for updating an existing room description
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['room_name'] = 'Conference Room A';
        $_POST['description'] = 'Updated description';
        $_POST['capacity'] = 60;
        $_POST['availability'] = 'Unavailable';
        $_POST['room_descID'] = 1; // Simulate an existing room_descID

        // Mock the query execution to return true for successful update
        $this->mockDatabase->method('query')
                           ->willReturn(true);

        // Call the function to update the room description
        $sql = "UPDATE room_description SET 
                room_name = 'Conference Room A',
                description = 'Updated description',
                capacity = 60,
                availability = 'Unavailable'
                WHERE room_descID = '1'";
        $status = $this->updateDbTable($this->conn, $sql);

        // Assert that the update was successful
        $this->assertTrue($status);
    }

    private function updateDbTable($conn, $sql)
    {
        // Simulate the database update operation
        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
            return false;
        }
    }
}