<?php
session_start();
use Config; 

// Variables
$room_name = "";
$description = "";
$capacity = "";
$availability = "";

// This block is called when the "Submit" button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Values for add or edit
    $room_name = $_POST["room_name"];
    $description = $_POST["description"];
    $capacity = $_POST["capacity"];
    $availability = $_POST["availability"];
    $room_descID = isset($_POST["room_descID"]) ? $_POST["room_descID"] : null;

    if ($room_descID) {
        $sql = "UPDATE room_description SET 
            room_name = '$room_name',
            description = '$description',
            capacity = '$capacity',
            availability = '$availability'
            WHERE room_descID = '$room_descID'";
    } else {
        $sql = "INSERT INTO room_description (room_name, description, capacity, availability)
            VALUES ('$room_name', '$description', '$capacity', '$availability')";
    }

    $status = updateDbTable($conn, $sql);

    if ($status) {
        echo $room_descID ? "Room data updated successfully!<br>" : "Room data saved successfully!<br>";
        echo '<a href="staff_room.php">Back</a>';
    } else {
        echo '<a href="staff_room.php">Back</a>';
    }
}

// Close db connection
mysqli_close($conn);

// Function to update data in the database table
function updateDbTable($conn, $sql)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}
