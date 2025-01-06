<?php
session_start();
include('config.php');

// Variables
$room_name = "";
$date = "";
$start_time = "";
$end_time = "";
$capacity = "";
$purpose = "";

// This block is called when the "Submit" button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Values for add or edit
    $room_name = $_POST["room_name"];
    $date = $_POST["date"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $capacity = $_POST["capacity"];
    $purpose = $_POST["purpose"];

    // Check if bookingID is provided in the form data
    if (isset($_POST["bookingID"])) {
        $bookingID = $_POST["bookingID"];
        $sql = "UPDATE booking SET room_name = '$room_name', date = '$date', start_time = '$start_time', end_time = '$end_time', capacity = '$capacity', purpose = '$purpose' WHERE bookingID = '$bookingID'";
    } else {
        // If no bookingID is provided, perform an INSERT
        $sql = "INSERT INTO booking (room_name, date, start_time, end_time, capacity, purpose) 
                VALUES ('$room_name', '$date', '$start_time', '$end_time', '$capacity', '$purpose')";
    }

    $status = insertTo_DBTable($conn, $sql);

    if ($status) {
        echo "Room data saved successfully!<br>";
        echo '<a href="user_booking.php">Back</a>';
    } else {
        echo '<a href="user_booking.php">Back</a>';
    }
}

// Close db connection
mysqli_close($conn);

// Function to insert data into the database table
function insertTo_DBTable($conn, $sql)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}
?>
