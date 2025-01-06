<?php
session_start();
include('config.php');

// Check if logged-in
if (!isset($_SESSION["staffEmail"])) {
    header("location:index.php");
}

// This block is called when the "Update" button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $room_descID = $_POST["room_descID"];
    $room_name = $_POST["room_name"];
    $description = $_POST["description"];
    $capacity = $_POST["capacity"];
    $availability = $_POST["availability"];

    // Validate form fields (Add more validation as needed)
    if (empty($room_name) || empty($description) || empty($capacity) || empty($availability)) {
        echo "Please fill out all required fields.";
        // Redirect or handle the error as needed
        exit();
    }

    // SQL to update the room_description table
    $sql = "UPDATE room_description 
            SET room_name = '$room_name', 
                description = '$description', 
                capacity = '$capacity', 
                availability = '$availability' 
            WHERE room_descID = $room_descID";

    if (mysqli_query($conn, $sql)) {
        echo "Form data updated successfully!<br>";
        echo '<a href="staff_room.php">Back</a>';
    } else {
        echo "Error updating form data: " . mysqli_error($conn) . "<br>";
        echo '<a href="javascript:history.back()">Back</a>';
    }
}

// Close db connection
mysqli_close($conn);
?>
