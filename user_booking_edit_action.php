<?php
session_start();
include('config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the bookingID from the form
    $bookingID = $_POST['bookingID'];

    // Fetch existing Booking details from the database
    $sql_select = "SELECT * FROM booking WHERE bookingID = '$bookingID'";
    $result_select = mysqli_query($conn, $sql_select);

    if ($result_select) {
        $row = mysqli_fetch_assoc($result_select);

        // Retrieve form data
        $room_name = $_POST['room_name'];
        $date = $_POST['date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $capacity = $_POST['capacity'];
        $purpose = $_POST['purpose'];

        // Update the Booking details in the database
        $sql_update = "UPDATE booking SET 
            room_name = '$room_name',
            date = '$date',
            start_time = '$start_time',
            end_time = '$end_time',
            capacity = '$capacity',
            purpose = '$purpose'
            WHERE bookingID = '$bookingID'";

        $result_update = mysqli_query($conn, $sql_update);

        if ($result_update) {
            echo "Booking details updated successfully!<br>";
            echo '<a href="user_booking.php">Back</a>';
        } else {
            echo "Error updating Booking details: " . mysqli_error($conn);
        }
    } else {
        echo "Error fetching Booking details: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    // Redirect to the Booking list if the form is not submitted
    header("Location: user_booking.php");
    exit();
}
?>
