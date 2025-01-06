<?php
include ("config.php");

//start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from the form
    $userEmail = $_POST['userEmail'];
    $userPass = $_POST['userPass'];

 // Use prepared statement to prevent SQL injection
 $stmt = $conn->prepare("SELECT * FROM users WHERE userEmail = ? AND userPass = ?");
 $stmt->bind_param("ss", $userEmail, $userPass);

 $stmt->execute();

 $result =$stmt->get_result();

 if ($result->num_rows === 1) {
    //Auhentication successful, set session variables and redirect
    $_SESSION['userEmail'] = $userEmail; // Set the user's email in session upon successful login
    header("location: profile_user.php");
    exit();
 } else {
    //Authentication failed, display error message
    echo '<script>';
    echo 'alert("Invalid User Email or Password. Please Try Again.");';
    echo 'window.location.href = "login_user.php";';
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
            <h1> Login as User </h1>
            <p>Please login to continue.  <p>
                <form action="login_user.php" method="post" onsubmit="return validation()">
                <div class="txt_field">
                    <label for = "userEmail">Email</label>
                    <input type="text" id="userEmail" name="userEmail" required>
                    </div>
                <div class="txt_field">
                    <label for="password">Password</label>
                    <input type="password" id="userPass" name="userPass" required>
                    </div>
            <div class="signup_link">
            <div class="pass">Don't have an account?<a href="register_user.php"> Sign up now.</a></div>
            </div>
            <div class="reset">Forgot password?<a href="reset_pass_user.php" > Reset now.</a>
</div>
<div class="back">
                <a href="index.php">Back</a>
            </div>
<div class="login-btn">
    <input type="submit" value="Login">
</form>
</div>




<script>
    function validation() {
        var em = document.getElementById("userEmail").value;
        var ps = document.getElementById("userPass").value;
        if (em.length == "" && ps.length == "") {
            alert ("User email and password are empty! ");
            return false;
        }
        else {
            if (em.length == "") {
                alert("User Email cannot be empty!");
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

