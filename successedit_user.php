<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEmail = $_POST['userEmail'];
    $userName = $_POST['userName'];
    $userPhone = $_POST['userPhone'];
    $userState = $_POST['userState'];
    $userAdd = $_POST['userAdd'];
    $userDob = $_POST['userDob'];
    $userGender = $_POST['userGender'];
    $userIC = $_POST['userIC'];

    $update_sql = "UPDATE users SET userName=?, userPhone=?, userAdd=?, userDob=?, userGender=?, userState=?, userIC=?";
    $params = [$userName, $userPhone, $userAdd, $userDob, $userGender, $userState, $userIC];

    $update_sql .= " WHERE userEmail=?";
    $params[] = $userEmail;

    $stmt = $conn->prepare($update_sql);
    if ($stmt) {
        $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        if ($stmt->execute()) {
            echo '<script>alert("Changes updated successfully."); window.location.href = "profile_user.php";</script>';
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
