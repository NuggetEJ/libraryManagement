<?php
include ("config.php");

//start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from the form
    $staffEmail = $_POST['staffEmail'];
    $staffPass = $_POST['staffPass'];

 // Use prepared statement to prevent SQL injection
 $stmt = $conn->prepare("SELECT * FROM staff WHERE staffEmail = ? AND staffPass = ?");
 $stmt->bind_param("ss", $staffEmail, $staffPass);

 $stmt->execute();

 $result =$stmt->get_result();

 if ($result->num_rows === 1) {
    //Auhentication successful, set session variables and redirect
    $_SESSION['staffEmail']=$staffEmail;
    header("location: profile_staff.php");
    exit();
 } else {
    //Authentication failed, display error message
    echo '<script>';
    echo 'alert("Invalid staff Email or Password. Please Try Again.");';
    echo 'window.location.href = "login_staff.php";';
    echo '</script>'; 
 }

 $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login Form</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body class="body-login">
        <div class="container">
            <h1> Login as Staff </h1>
            <p> Please login to continue. <p>
                <form action="login_staff.php" method="post" onsubmit="return validation()">
                <div class="txt_field">
                    <label for = "staffEmail">Email</label>
                    <input type="text" id="staffEmail" name="staffEmail" required>
                    </div>
                <div class="txt_field">
                    <label for="password">Password</label>
                    <input type="password" id="staffPass" name="staffPass" required>
                    </div>
            <div class="signup_link">
                <div class="pass">Don't have an account?<a href="register_staff.php"> Sign up now.</a></div>
            </div>
            <div class="reset">Forgot password?<a href="reset_pass_staff.php"> Reset now.</a>
</div>
<div class="back">
            <a href="profile_staff.php">Back</a>
            </div>
<div class="login-btn">
    <input type="submit" value="Login">
</form>
</div>

<script>
    function validation() {
        var em = document.getElementById("staffEmail").value;
        var ps = document.getElementById("staffPass").value;
        if (em.length == "" && ps.length == "") {
            alert ("Staff email and password are empty! ");
            return false;
        }
        else {
            if (em.length == "") {
                alert("Staff Email cannot be empty!");
                return false;
            }
            if (ps.length == "") {
                alert("Password cannot be empty!");
                return false;
            }
        }
    }
    </script>
    </body>
    </html>

