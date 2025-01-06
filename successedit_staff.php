<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffEmail = $_POST['staffEmail'];
    $staffName = $_POST['staffName'];
    $staffPhone = $_POST['staffPhone'];
    $staffState = $_POST['staffState'];
    $staffAdd = $_POST['staffAdd'];
    $staffDob = $_POST['staffDob'];
    $staffGender = $_POST['staffGender'];
    $staffIC = $_POST['staffIC'];

    if ($_FILES['staffPhoto']['size'] > 0) {
        $target_dir = "img/";
        $filename = basename($_FILES["staffPhoto"]["name"]);
        $imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
        // Generate a unique filename using timestamp
        $uniqueFilename = time() . '_' . $filename;
        $target_file = $target_dir . $uniqueFilename;
    
        $uploadOk = 1;
    
        $check = getimagesize($_FILES["staffPhoto"]["tmp_name"]);
        if ($check === false) {
            die("File is not an image.");
        }
    
        if (file_exists($target_file)) {
            die("Sorry, a file with that name already exists.");
        }

        if ($_FILES["staffPhoto"]["size"] > 5000000) {
            die("Sorry, your file is too large.");
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if (move_uploaded_file($_FILES["staffPhoto"]["tmp_name"], $target_file)) {
            $staffPhotoPath = $target_file;
            echo "Image uploaded successfully! Path: " . $staffPhotoPath; // Debugging line
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    $update_sql = "UPDATE staff SET staffName=?, staffPhone=?, staffAdd=?, staffDob=?, staffGender=?, staffState=?, staffIC=?";
$params = [$staffName, $staffPhone, $staffAdd, $staffDob, $staffGender, $staffState, $staffIC];

if (isset($staffPhotoPath)) {
    $update_sql .= ", staffPhoto=?";
    $params[] = $staffPhotoPath;
}

$update_sql .= " WHERE staffEmail=?";
$params[] = $staffEmail;

$stmt = $conn->prepare($update_sql);
if ($stmt) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
    if ($stmt->execute()) {
        echo '<script>alert("Changes updated successfully."); window.location.href = "profile_staff.php";</script>';
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}
} else {
    echo "Invalid request.";
}
?>
