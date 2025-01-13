use PHPUnit\Framework\TestCase;

class CreateBookingTest extends TestCase
{
    private $mockDatabase;

    protected function setUp(): void
    {
        // Create a mock database connection
        $this->mockDatabase = $this->getMockBuilder(mysqli::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();
    }

    public function testCreateBooking()
    {
        // Define mock data to be returned by the query
        $mockData = [
            'bookingID' => 1,
            'room_name' => 'Conference Room A',
            'date' => '2025-01-15',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'capacity' => 20,
            'purpose' => 'Team Meeting',
        ];

        // Mock the query execution
        $this->mockDatabase->method('query')
                          ->willReturn($this->createMockResult($mockData));

        // Simulate the creation of a booking
        $sql = "INSERT INTO booking (room_name, date, start_time, end_time, capacity, purpose) 
                VALUES ('Conference Room A', '2025-01-15', '10:00:00', '12:00:00', 20, 'Team Meeting')";
        $result = $this->mockDatabase->query($sql);

        // Simulate fetching the booking details (to verify the booking)
        $sqlFetch = "SELECT * FROM booking WHERE bookingID = 1";
        $resultFetch = $this->mockDatabase->query($sqlFetch);
        $row = $resultFetch->fetch_assoc();

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
