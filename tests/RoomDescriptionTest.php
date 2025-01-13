<?php
use PHPUnit\Framework\TestCase;

class RoomDescriptionTest extends TestCase
{
    private $mockDatabase;

    protected function setUp(): void
    {
        // Create a new mock database connection for each test
        $this->mockDatabase = $this->getMockBuilder(mysqli::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();
    }

    public function testInsertRoomDescription()
    {
        // Mock the query execution to return true for successful insertion
        $this->mockDatabase->method('prepare')->willReturn($this->mockDatabase);
        $this->mockDatabase->method('bind_param')->willReturn(true);
        $this->mockDatabase->method('execute')->willReturn(true);
        $this->mockDatabase->method('get_result')->willReturn($this->createMockResult(['userID' => 1]));

        // Simulate the SQL execution for complaint insertion
        $sql = "INSERT INTO room_description (room_name, description, capacity, availability)
                VALUES ('Conference Room A', 'A large conference room', 50, 'Available')";
        $status = $this->updateDbTable($this->mockDatabase, $sql);

        // Assert that the insertion was successful
        $this->assertTrue($status);
    }

    public function testUpdateRoomDescription()
    {
        // Mock the query execution to return true for successful update
        $this->mockDatabase->method('prepare')->willReturn($this->mockDatabase);
        $this->mockDatabase->method('bind_param')->willReturn(true);
        $this->mockDatabase->method('execute')->willReturn(true);
        $this->mockDatabase->method('get_result')->willReturn($this->createMockResult(['userID' => 1]));

        // Simulate the SQL execution for complaint update
        $sql = "UPDATE room_description SET 
                room_name = 'Conference Room A',
                description = 'Updated description',
                capacity = 60,
                availability = 'Unavailable'
                WHERE room_descID = '1'";
        $status = $this->updateDbTable($this->mockDatabase, $sql);

        // Assert that the update was successful
        $this->assertTrue($status);
    }

    private function updateDbTable($conn, $sql)
    {
        // Simulate the database update operation
        if ($conn->query($sql)) {
            return true;
        } else {
            echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
            return false;
        }
    }

    private function createMockResult(array $data)
    {
        // Mock the mysqli_result class
        $mockResult = $this->getMockBuilder(mysqli_result::class)
                          ->disableOriginalConstructor()
                          ->getMock();

        // Mock the fetch_assoc method to return the data
        $mockResult->method('fetch_assoc')->willReturn($data);

        return $mockResult;
    }
}