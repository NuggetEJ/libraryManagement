<?php
session_start();
include "config.php";
if (!isset($_SESSION["staffEmail"])) {
    header("location:logout.php");
    exit;
}
$sql = "SELECT staffID FROM staff WHERE staffEmail = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $staffEmail);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $staffID);

if (mysqli_stmt_fetch($stmt)) {
    $_SESSION["UID"] = $staffID; // Store staffID in the session
}

mysqli_stmt_close($stmt);

// Check if the event ID is set
if (!isset($_POST['eventID'])) {
    echo "Event ID not set.";
    exit;
}

// Fetch the event details from the database based on the event ID
$id = $_POST['eventID'];
$sql_fetch_event = "SELECT * FROM events WHERE eventID = $id";
$result_fetch_event = mysqli_query($conn, $sql_fetch_event);

// Check if the query was successful and at least one row is returned
if ($result_fetch_event && mysqli_num_rows($result_fetch_event) > 0) {
    // Fetch the event details into $row
    $row = mysqli_fetch_assoc($result_fetch_event);
} else {
    echo "Error fetching event details.";
    exit;
}

//variables
$action = "";
$id = "";
$eventName = "";
$eventDesc = "";
$eventDateStart = " ";
$eventTimeStart = "";
$eventDateEnd = "";
$eventTimeEnd = "";
$eventLocation = " ";
$eventCategory = "";
$eventStatus = " ";

//for upload
$target_dir = "uploads/";
$target_file = "";
$uploadOk = 0;
$imageFileType = "";
$uploadfileName = "";

//this block is called when button Submit is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['eventID'];
    $eventName = $_POST['eventName'];
    $eventDesc = $_POST['eventDesc'];
    $eventDateStart = $_POST['eventDateStart'];
    $eventTimeStart = $_POST['eventTimeStart'];
    $eventDateEnd = $_POST['eventDateEnd'];
    $eventTimeEnd = $_POST['eventTimeEnd'];
    $eventLocation = $_POST['eventLocation'];
    $eventCategory = $_POST['eventCategory'];
    $eventStatus = $_POST['eventStatus'];

    $filetmp = $_FILES["fileToUpload"];
    //file of the image/photo file
    $uploadfileName = $filetmp["name"];

    // Get the current image path from the database before updating with the new image
    $sql_select_img = "SELECT eventPhoto FROM events WHERE eventID = $id";
    $result_img = mysqli_query($conn, $sql_select_img);
    $old_image_path = mysqli_fetch_assoc($result_img)['eventPhoto'];

    // Check if an image file is uploaded
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["size"] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


        // Check if file already exists
        if (file_exists($target_file)) {
            echo "ERROR: Sorry, image file $uploadfileName already exists.<br>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            echo "ERROR: Sorry, your file is too large. Try resizing your image.<br>";
            $uploadOk = 0;
        }

        // Allow only these file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "ERROR: Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            $uploadOk = 0;
        }

        // Move the uploaded file if conditions are met
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $uploadfileName = basename($_FILES["fileToUpload"]["name"]);

                // Delete the old image if it exists
                if (!empty($old_image_path) && file_exists("uploads/" . $old_image_path)) {
                    unlink("uploads/" . $old_image_path);
                }

                $sql = "UPDATE events SET eventName = '$eventName', eventDesc = '$eventDesc',
                eventDateStart = '$eventDateStart', eventTimeStart = '$eventTimeStart', eventDateEnd = '$eventDateEnd',
                eventTimeEnd = '$eventTimeEnd', eventLocation = '$eventLocation', eventCategory = '$eventCategory',
                eventStatus = '$eventStatus', eventPhoto = '$uploadfileName'
                WHERE eventID = $id";

                $status = update_DBTable($conn, $sql);

                if ($status) {
                    echo '<script>';
                    echo 'if(confirm("Form data updated successfully!")){';
                    echo 'window.location.href = "admin_event_view.php";';
                    echo '}';
                    echo '</script>';
                    exit;
                } else {
                    echo '<a href="javascript:history.back()">Back</a>';
                    exit;
                }
            } else {
                echo "ERROR: There was an error uploading your file.<br>";
                echo '<a href="javascript:history.back()">Back</a>';
                exit;
            }
        } else {
            echo '<a href="javascript:history.back()">Back</a>';
            exit;
        }
    } else {
        // No image uploaded, update other details without changing the image path
        $sql = "UPDATE events SET eventName = '$eventName', eventDesc = '$eventDesc',
        eventDateStart = '$eventDateStart', eventTimeStart = '$eventTimeStart', eventDateEnd = '$eventDateEnd',
        eventTimeEnd = '$eventTimeEnd', eventLocation = '$eventLocation', eventCategory = '$eventCategory',
        eventStatus = '$eventStatus'
        WHERE eventID = $id";

        $status = update_DBTable($conn, $sql);

        if ($status) {
            echo '<script>';
            echo 'if(confirm("Form data updated successfully!")){';
            echo 'window.location.href = "admin_event_view.php";';
            echo '}';
            echo '</script>';
            exit;
        } else {
            echo '<a href="javascript:history.back()">Back</a>';
            exit;
        }
    }
} else {
    echo "ERROR: Invalid request method.<br>";
    echo '<a href="javascript:history.back()">Back</a>';
    exit;
}

// Function to insert data to the database table
function update_DBTable($conn, $sql)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}
?>