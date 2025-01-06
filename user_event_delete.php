<?php
session_start();
include("config.php");
if(!isset($_SESSION["userEmail"])){
    header("location:logout.php");
    exit;
}

// This action is called when the Delete link is clicked
if(isset($_GET["id"]) && $_GET["id"] != ""){
    $id = $_GET["id"];

    // Fetch the image filename from the database
    $sql_select = "SELECT eventPhoto FROM events WHERE eventID = $id";
    $result = mysqli_query($conn, $sql_select);

    // Delete the record from the database
    $sql = "DELETE FROM events WHERE eventID = $id AND userID = " . $_SESSION["UID"];

    if (mysqli_query($conn, $sql)) {
        echo '<script>';
        echo 'if(confirm("Record deleted successfully!")){';
        echo 'window.location.href = "user_event_view.php";';
        echo '}';
        echo '</script>';
    } else {
        echo "Error deleting record: " . mysqli_error($conn) . "<br>";
        echo '<a href="user_event_view.php">Back</a>';
    }

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $uploadfileName = $row['eventPhoto'];

        // Delete the image file from the uploads folder if it exists
        $uploadsDirectory = 'uploads/'; 
        $imagePath = $uploadsDirectory . $uploadfileName;
    }
}

mysqli_close($conn);
?>
