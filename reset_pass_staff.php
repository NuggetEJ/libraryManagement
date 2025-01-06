<?php
include("config.php");

// Start or resume the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['resetPassword'])) {
        // Process the form submission for password recovery
        $staffEmail = $_POST['staffEmail'];
        $newPassword = $_POST['newPassword'];

        // Update the password for the given staff Email
        $stmt = $conn->prepare("UPDATE staff SET staffPass = ? WHERE staffEmail = ?");
        $stmt->bind_param("ss", $newPassword, $staffEmail);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Password updated successfully
            echo '<script>';
            echo 'alert("Password reset successful.");';
            echo 'window.location.href = "login_staff.php";'; // Redirect to login page
            echo '</script>';
            exit();
        } else {
            // staff email not found or password update failed
            echo '<script>';
            echo 'alert("Password reset failed. Please enter a valid staff email and try again.");';
            echo '</script>';
        }

        $stmt->close();
    } else {
        // Handle other form submissions or include the form HTML here
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Staff Password Reset</h1>
        <p>Enter your staff email and a new password to reset your password.</p>
        <form action="reset_pass_staff.php" method="post">
            <div class="txt_field">
                <label for="staffEmail">Email</label>
                <input type="text" id="staffEmail" name="staffEmail" required>
            </div>
            <div class="txt_field">
                <label for="new_password">New Password</label>
                <input type="password" id="newPassword" name="newPassword" required>
            </div>
            <div class="login-btn">
                <input type="submit" name="resetPassword" value="Reset Password">
            </div>
            <div class="back">
                <a href="login_staff.php">Back</a>        
            </div>
        </form>
    </div>
</body>
</html>