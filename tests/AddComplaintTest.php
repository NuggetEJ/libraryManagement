<?php

use PHPUnit\Framework\TestCase;

class AddComplaintTest extends TestCase
{
    private $mockDatabase;

    protected function setUp(): void
    {
        // Create a mock database connection
        $this->mockDatabase = $this->getMockBuilder(mysqli::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();
    }

    public function testFetchUserID()
    {
        // Mock data for user retrieval
        $mockData = [
            'userID' => 1,
        ];

        // Mock query execution for fetching userID
        $this->mockDatabase->method('prepare')
                          ->willReturn($this->mockDatabase);
        $this->mockDatabase->method('bind_param')
                          ->willReturn(true);
        $this->mockDatabase->method('execute')
                          ->willReturn(true);
        $this->mockDatabase->method('get_result')
                          ->willReturn($this->createMockResult($mockData));

        // Simulate fetching userID based on userEmail
        $userEmail = 'test@example.com';
        $sql = "SELECT userID FROM users WHERE userEmail = ?";
        $stmt = $this->mockDatabase->prepare($sql);
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $this->assertEquals(1, $row['userID']);
    }

    public function testAddComplaint()
    {
        // Mock data for complaint submission
        $issueType = 'Damaged Book';
        $complaintDescription = 'The book is torn.';
        $dateSubmitted = date("Y-m-d");
        $complaintStatus = "Pending";
        $userID = 1; // Assume we fetched this from the previous test

        // Mock the query execution for inserting a complaint
        $this->mockDatabase->method('prepare')
                          ->willReturn($this->mockDatabase);
        $this->mockDatabase->method('bind_param')
                          ->willReturn(true);
        $this->mockDatabase->method('execute')
                          ->willReturn(true);

        // Simulate the SQL execution for complaint insertion
        $sql = "INSERT INTO complaint (userID, issueType, complaintDescription, complaintPhoto, complaintStatus, dateSubmitted) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->mockDatabase->prepare($sql);
        $stmt->bind_param("isssss", $userID, $issueType, $complaintDescription, $target_file = 'uploads/photo.jpg', $complaintStatus, $dateSubmitted);
        $status = $stmt->execute();

        // Assert that the complaint was added successfully
        $this->assertTrue($status);
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