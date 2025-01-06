<?php
session_start();
include('config.php');

// Check if the ID parameter is set
if (isset($_GET['id']) && $_GET['id'] != "") {
    $room_descID = $_GET['id'];

    // Delete the room record from the database
    $sqlDelete = "DELETE FROM room_description WHERE room_descID = $room_descID";
    
    if (mysqli_query($conn, $sqlDelete)) {
        echo "Record deleted successfully<br>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "Invalid room ID<br>";
}

echo '<a href="staff_room.php">Back</a>';

mysqli_close($conn);
?>
