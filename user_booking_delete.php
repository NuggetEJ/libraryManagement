<?php
session_start();
include('config.php');

// Check if the ID parameter is set
if (isset($_GET['id']) && $_GET['id'] != "") {
    $bookingID = $_GET['id'];

        // Delete the booking record from the database
        $sqlDelete = "DELETE FROM booking WHERE bookingID = $bookingID";
        if (mysqli_query($conn, $sqlDelete)) {
            echo "Record deleted successfully<br>";
        } else {
            echo "Error deleting record: " . mysqli_error($conn) . "<br>";
        }
}
else {
    echo "Invalid booking ID<br>";
}

echo '<a href="user_booking.php">Back</a>';

mysqli_close($conn);
?>
