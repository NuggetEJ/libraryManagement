<?php
include("config.php");

//Variables 
$staffName="";
$staffEmail="";
$staffPass="";
$staffIC="";
$staffPhone="";
$staffDob="";
$staffGender="";
$staffAdd="";
$staffState="";
$staffPhoto="";

$target_dir = "libuploads/" ;

// Check if the directory doesn't exist, then create it
if (!file_exists('libuploads') && !is_dir('libuploads')) {
    mkdir('libuploads', 0755, true);
}

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $staffName=$_POST['staffName'];
    $staffEmail=$_POST['staffEmail'];
    $staffPass=$_POST['staffPass'];
    $staffIC=$_POST['staffIC'];
    $staffPhone=$_POST['staffPhone'];
    $staffDob=$_POST['staffDob'];
    $staffGender=$_POST['staffGender'];
    $staffAdd=$_POST['staffAdd'];
    $staffState=$_POST['staffState'];
    $staffPhoto=$_POST['staffPhoto'];

if (isset($_FILES['staffPhoto']) && $_FILES['staffPhoto']['error'] == UPLOAD_ERR_OK) {
    // Generate a unique filename for the uploaded file based on student ID
    $staffPhotoName = $staffName . ".jpg"; 
    $target_file = $target_dir . $staffPhotoName;

    // Move the uploaded file to the target directory
if (move_uploaded_file($_FILES["staffPhoto"]["tmp_name"], $target_file)) {
    // Save the filename to be stored in the database with the target directory
    $staffPhoto = $target_file; 
}
// sql query for data insertion
$sql = "INSERT INTO staff (staffName, staffEmail, staffPass, staffIC, staffPhone, staffDob, staffGender, staffAdd, staffState, staffPhoto)
VALUES ('$staffName', '$staffEmail', '$staffPass', '$staffIC', '$staffPhone', '$staffDob', '$staffGender', '$staffAdd', '$staffState', '$staffPhoto')";

$status = insertTo_DBTable($conn, $sql);

if ($status) {
    echo "Registration successful. Please proceed to login. ";
    header("Location: login_staff.php");
    exit();
} else {
    if (mysqli_errno($conn)==1062) {
        echo "Sorry. This staff already exists. Please login. ";
    } else {
        echo "Error inserting data into database. ";
    }
}
} else {
    echo "Sorry, there was an error uploading your file.";
}
} else {
echo "File upload failed or no file was uploaded."; // Provide appropriate feedback for file upload issues
}

mysqli_close($conn);

function insertTo_DBTable($conn, $sql)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}
?>