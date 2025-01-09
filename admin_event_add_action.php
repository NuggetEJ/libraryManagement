<?php
session_start();
include "config.php";
if(!isset($_SESSION["staffEmail"])){
    header("location:logout.php");
    exit;
}
$sql = "SELECT staffID FROM staff WHERE staffEmail = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $staffEmail);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $staffID);

if (mysqli_stmt_fetch($stmt)) {
    $_SESSION["UID"] = $staffID;
}

mysqli_stmt_close($stmt);

//variables
$action="";
$id="";
$eventName = "";
$eventDesc = "";
$eventDateStart =" ";
$eventTimeStart = "";
$eventDateEnd = "";
$eventTimeEnd = "";
$eventLocation =" ";
$eventCategory = "";

//for upload
$target_dir = "uploads/";
$target_file = "";
$uploadOk = 0;
$imageFileType = "";
$uploadfileName = "";

//this block is called when button Submit is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //values for add or edit
    $eventName = $_POST["eventName"];
    $eventDesc = $_POST["eventDesc"];
    $eventDateStart = trim($_POST["eventDateStart"]);
    $eventTimeStart = trim($_POST["eventTimeStart"]);
    $eventDateEnd = trim($_POST["eventDateEnd"]);
    $eventTimeEnd = $_POST["eventTimeEnd"];
    $eventLocation = trim($_POST["eventLocation"]);
    $eventTimeStart = trim($_POST["eventTimeStart"]);
    $eventCategory = trim($_POST["eventCategory"]);
    $filetmp = $_FILES["fileToUpload"];
    $staffID = $_SESSION["UID"];

    //file of the image/photo file
    $uploadfileName = $filetmp["name"];

    //Check if there is an image to be uploaded
    // If no image is uploaded
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"] == "") {
        $sql = "INSERT INTO events (eventName, eventDesc, eventDateStart, eventTimeStart, eventDateEnd, eventTimeEnd, eventLocation, eventCategory, staffID)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssi", $eventName, $eventDesc, $eventDateStart, $eventTimeStart, $eventDateEnd, $eventTimeEnd, $eventLocation, $eventCategory, $staffID);

        $status = insertTo_DBTable($conn, $stmt);

        mysqli_stmt_close($stmt);

        if ($status) {
            echo '<script>';
            echo 'if(confirm("Form data saved successfully!")){';
            echo 'window.location.href = "admin_event_view.php";';
            echo '}';
            echo '</script>';
        } else {
            echo '<a href="admin_event_view.php">Back</a>';
        }
    }

    //If there is an image
    else if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
        //Variable to determine if image upload is OK
        $uploadOk = 1;
        $filetmp = $_FILES["fileToUpload"];

        //file of the image/photo file
        $uploadfileName = $filetmp["name"];
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "ERROR: Sorry, image file $uploadfileName already exists.<br>";
            $uploadOk = 0;
        }

        // Check file size <= 2MB
        if ($_FILES["fileToUpload"]["size"] > 2000000) {
            echo "ERROR: Sorry, your file is too large. Try resizing your image.<br>";
            $uploadOk = 0;
        }

        // Allow only these file formats
        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            echo "ERROR: Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            $uploadOk = 0;
        }

        // If uploadOk, then try to add to the database first
        if ($uploadOk) {
            $sql = "INSERT INTO events (eventName, eventDesc, eventDateStart, eventTimeStart, eventDateEnd, eventTimeEnd, eventLocation, eventCategory, eventPhoto, staffID)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssssssssi", $eventName, $eventDesc, $eventDateStart, $eventTimeStart, $eventDateEnd, $eventTimeEnd, $eventLocation, $eventCategory, $uploadfileName, $staffID);

            $status = insertTo_DBTable($conn, $stmt);

            mysqli_stmt_close($stmt);

            if ($status && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo '<script>';
                echo 'if(confirm("Form data saved successfully!")){';
                echo 'window.location.href = "admin_event_view.php";';
                echo '}';
                echo '</script>';
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
                echo '<a href="javascript:history.back()">Back</a>';
            }
        } else {
            echo '<a href="javascript:history.back()">Back</a>';
        }
    }
}

//close the database connection
mysqli_close($conn);

function insertTo_DBTable($conn, $stmt) {
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo "Error: " . mysqli_stmt_error($stmt) . "<br>";
        return false;
    }
}

?>
