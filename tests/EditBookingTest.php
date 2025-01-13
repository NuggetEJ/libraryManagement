<?php

use PHPUnit\Framework\TestCase;

class EditBookingTest extends TestCase
{
  private $mockDatabase;

protected function setUp(): void
  {
    // Create a mock database connection
    $this->mockDatabase = $this->getMockBuilder(mysqli::class)
                                ->disableOriginalCOnstructor()
                                ->getMock();
  }

public function testEditBookingFetchDetails()
  {
    $mockData = [
        'room_name' => 'Conference Room A',
        'date' => '2025-01-15',
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'capacity' => 20,
        'purpose' => 'Team Meeting',
      ];

  // Mock query execution
  $this->mockDatabase->method('query')
                    ->willReturn($this->createMockResult($mockData));

  // Simulate fetching booking details
  $sql = "SELECT * FROM booking WHERE bookingID = '1'";
  $result = $this->mockDatabase->query($sql);

  // Fetch row
  $row = $result->fetch_assoc();

  $this->assertEquals('Conference Room A', $row['room_name']);
  $this->assertEquals('2025-01-15', $row['date']);
  $this->assertEquals('10:00:00', $row['start_time']);
  $this->assertEquals('12:00:00', $row['end_time']);
  $this->assertEquals(20, $row['capacity']);
  $this->assertEquals('Team Meeting', $row['purpose']);
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
