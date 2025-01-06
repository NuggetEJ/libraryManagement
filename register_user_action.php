<?php
include("config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

function insertTo_DBTable($conn, $sql)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}
//Variables 
$userName="";
$userEmail="";
$userPass="";
$userIC="";
$userPhone="";
$userDob="";
$userGender="";
$userAdd="";
$userState="";

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $userName=$_POST['userName'];
    $userEmail=$_POST['userEmail'];
    $userPass=$_POST['userPass'];
    $userIC=$_POST['userIC'];
    $userPhone=$_POST['userPhone'];
    $userDob=$_POST['userDob'];
    $userGender=$_POST['userGender'];
    $userAdd=$_POST['userAdd'];
    $userState=$_POST['userState'];

// sql query for data insertion
$sql = "INSERT INTO users (userName, userEmail, userPass, userIC, userPhone, userDob, userGender, userAdd, userState)
VALUES ('$userName', '$userEmail', '$userPass', '$userIC', '$userPhone', '$userDob', '$userGender', '$userAdd', '$userState')";

$status = insertTo_DBTable($conn, $sql);

if ($status) {
    echo "Registration successful. Please proceed to login. ";
    header("Location: login_user.php");
    exit();
} else {
    if (mysqli_errno($conn)==1062) {
        echo "Sorry. This user already exists. Please login. ";
    } else {
        echo "Error inserting data into database. ";
    }
}

mysqli_close($conn);
}
?>