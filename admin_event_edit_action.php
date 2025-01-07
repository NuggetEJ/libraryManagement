<?php
session_start();
include("config.php");

if (!isset($_SESSION["staffEmail"])) {
    header("location:logout.php");
    exit;
}

define("UPLOAD_DIR", "uploads/"); // Define reusable constant for "uploads/"
define("BACK_LINK", '<a href="javascript:history.back()">Back</a>'); // Define reusable constant for back link

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
    $row = mysqli_fetch_assoc($result_fetch_event);
} else {
    echo "Error fetching event details.";
    exit;
}

// Variables
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

// For upload
$target_file = "";
$uploadOk = 0;
$imageFileType = "";
$uploadfileName = "";

// This block is called when button Submit is clicked
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
    $uploadfileName = $filetmp["name"];

    $sql_select_img = "SELECT eventPhoto FROM events WHERE eventID = $id";
    $result_img = mysqli_query($conn, $sql_select_img);
    $old_image_path = mysqli_fetch_assoc($result_img)['eventPhoto'];

    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["size"] > 0) {
        $target_file = UPLOAD_DIR . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (file_exists($target_file)) {
            echo "ERROR: Sorry, image file $uploadfileName already exists.<br>";
            $uploadOk = 0;
        }

        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            echo "ERROR: Sorry, your file is too large. Try resizing your image.<br>";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "ERROR: Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            $uploadOk = 0;
        }

        if ($uploadOk) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $uploadfileName = basename($_FILES["fileToUpload"]["name"]);

                if (!empty($old_image_path) && file_exists(UPLOAD_DIR . $old_image_path)) {
                    unlink(UPLOAD_DIR . $old_image_path);
                }

                $sql = "UPDATE events SET eventName = '$eventName', eventDesc = '$eventDesc',
                    eventDateStart = '$eventDateStart', eventTimeStart = '$eventTimeStart', eventDateEnd = '$eventDateEnd',
                    eventTimeEnd = '$eventTimeEnd', eventLocation = '$eventLocation', eventCategory = '$eventCategory',
                    eventStatus = '$eventStatus', eventPhoto = '$uploadfileName'
                    WHERE eventID = $id";

                $status = updateDbTable($conn, $sql);

                if ($status) {
                    echo '<script>';
                    echo 'if(confirm("Form data updated successfully!")){';
                    echo 'window.location.href = "admin_event_view.php";';
                    echo '}';
                    echo '</script>';
                    exit;
                } else {
                    echo BACK_LINK;
                    exit;
                }
            } else {
                echo "ERROR: There was an error uploading your file.<br>";
                echo BACK_LINK;
                exit;
            }
        } else {
            echo BACK_LINK;
            exit;
        }
    } else {
        $sql = "UPDATE events SET eventName = '$eventName', eventDesc = '$eventDesc',
            eventDateStart = '$eventDateStart', eventTimeStart = '$eventTimeStart', eventDateEnd = '$eventDateEnd',
            eventTimeEnd = '$eventTimeEnd', eventLocation = '$eventLocation', eventCategory = '$eventCategory',
            eventStatus = '$eventStatus'
            WHERE eventID = $id";

        $status = updateDbTable($conn, $sql);

        if ($status) {
            echo '<script>';
            echo 'if(confirm("Form data updated successfully!")){';
            echo 'window.location.href = "admin_event_view.php";';
            echo '}';
            echo '</script>';
            exit;
        } else {
            echo BACK_LINK;
            exit;
        }
    }
} else {
    echo "ERROR: Invalid request method.<br>";
    echo BACK_LINK;
    exit;
}

// Function to insert data to the database table
function updateDbTable($conn, $sql) {
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}
?>