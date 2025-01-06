<?php
session_start(); // Start the session
include("config.php");

$userEmail = $_SESSION['userEmail'];

// Fetch userID based on userEmail
$sql = "SELECT userID FROM users WHERE userEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userID = $row['userID'];
} else {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Library Management System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,  initial-scale=1.0">

	<!-- import css style -->
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<!-- sidenav -->
<nav class="sidenav" id="mySidenav">
        <!-- logo lib -->
        <img src="img/logo.png" alt="logo" class="logo-img">

		<!-- link nav + its own icon -->
        <a href="profile_user.php"><i class="fa fa-user sidenav-icon"></i> <b> User Profile</a>
		<a href="userbooks.php"><i class="fa fa-book sidenav-icon"></i> Book Management</a>
		<a href="user_booking.php"><i class="fa fa-users sidenav-icon"></i> Room Booking Management</a>
		<a href="user_event_page.php"><i class="fa fa-calendar sidenav-icon"></i> Library Event Management</a>
		<a href="complaint_user.php"><i class="fa fa-sticky-note sidenav-icon"></i> Complaint Form</a>
		<a href="user_feedback.php"><i class="fa fa-comments sidenav-icon"></i> Feedback Form</a>
		<a href="user_activity.php" class="active"><i class="fa fa-file-text sidenav-icon"></i> Activity History</b></a>
        <small class="copyright"><i>Copyright &copy; 2024 - Library System Management</i></small>
	</nav>

	<!-- header at right side -->
    <header>
		<!-- title your page -->
        <h2>Feedback Form</h2>

        <?php 
		// Check if the user is not logged in, redirect to login page
		if (!isset($_SESSION["userEmail"])) {
			header("Location: index.php");
			exit();
		}
		else{
			echo '<a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>';	
		}			
		?>
	</header>

    <!-- masukkan body page kmu disini -->
    <div class="content">
        <p><i>Note: The (*) in the question indicates that it is required to be filled.</i></p>
        <form method="post" action="user_feedback_action.php">
            <input type="hidden" name="userID" value="<?=$userID?>">

            <label><b>1. How would you rate your overall experience with the library's services and facilities?*</b></label><br>
            <input type="radio" name="overall_rate" value="Excellent" required> Excellent<br>
            <input type="radio" name="overall_rate" value="Good"> Good<br>
            <input type="radio" name="overall_rate" value="Average"> Average<br>
            <input type="radio" name="overall_rate" value="Poor"> Poor<br><br>

            <label><b>2. How satisfied are you with the display of your user profile information in the system?*</b></label><br>
            <input type="radio" name="userDisplay_rate" value="Excellent" required> Excellent<br>
            <input type="radio" name="userDisplay_rate" value="Good"> Good<br>
            <input type="radio" name="userDisplay_rate" value="Average"> Average<br>
            <input type="radio" name="userDisplay_rate" value="Poor"> Poor<br><br>

            <label><b>3. How would you rate the process of searching and managing books in the library catalog?*</b></label><br>
            <input type="radio" name="manageBook_rate" value="Excellent" required> Excellent<br>
            <input type="radio" name="manageBook_rate" value="Good"> Good<br>
            <input type="radio" name="manageBook_rate" value="Average"> Average<br>
            <input type="radio" name="manageBook_rate" value="Poor"> Poor<br><br>

            <label><b>4. How satisfied are you with the system's ability to assign and track the resolution of your submitted complaints?*</b></label><br>
            <input type="radio" name="manageComplaint_rate" value="Excellent" required> Excellent<br>
            <input type="radio" name="manageComplaint_rate" value="Good"> Good<br>
            <input type="radio" name="manageComplaint_rate" value="Average"> Average<br>
            <input type="radio" name="manageComplaint_rate" value="Poor"> Poor<br><br>

            <label><b>5. How satisfied are you with the system's ability to manage your room booking?*</b></label><br>
            <input type="radio" name="manageBooking_rate" value="Excellent" required> Excellent<br>
            <input type="radio" name="manageBooking_rate" value="Good"> Good<br>
            <input type="radio" name="manageBooking_rate" value="Average"> Average<br>
            <input type="radio" name="manageBooking_rate" value="Poor"> Poor<br><br>

            <label><b>6. How satisfied are you with the system's ability to manage your event booking?*</b></label><br>
            <input type="radio" name="manageEvent_rate" value="Excellent" required> Excellent<br>
            <input type="radio" name="manageEvent_rate" value="Good"> Good<br>
            <input type="radio" name="manageEvent_rate" value="Average"> Average<br>
            <input type="radio" name="manageEvent_rate" value="Poor"> Poor<br><br>

            <label><b>7. How satisfied are you with the assistance and support provided by our library staff?*</b></label><br>
            <input type="radio" name="staff_rate" value="Excellent" required> Excellent<br>
            <input type="radio" name="staff_rate" value="Good"> Good<br>
            <input type="radio" name="staff_rate" value="Average"> Average<br>
            <input type="radio" name="staff_rate" value="Poor"> Poor<br><br>

            <label><b>8. Will you recommed our library system to other people?*</b></label><br>
            <input type="radio" name="recommendation_rate" value="Yes" required> Yes<br>
            <input type="radio" name="recommendation_rate" value="No"> No<br>
            <input type="radio" name="recommendation_rate" value="Maybe"> Maybe<br><br>

            <label><b>9. Do you have any suggestions for improving the library system or its services?*</b></label><br>
            <textarea placeholder="Please give us your honest feedback..." id="suggestion" name="suggestion" rows="5" cols="90" required></textarea><br><br>
            <input type="submit" name="submit" value="Submit">
        </form>
        <br><br>
    </div>
</body>
</html>