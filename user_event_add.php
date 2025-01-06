<?php
session_start();
include("config.php");
if(!isset($_SESSION["userEmail"])){
    header("location:logout.php");
    exit;
}
$sql = "SELECT userID FROM users WHERE userEmail = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $userEmail);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $userID);

if (mysqli_stmt_fetch($stmt)) {
    $_SESSION["UID"] = $userID; // Store userID in the session
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Library Management System</title>
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
        <h2>Library Event</h2>
        <a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>
	</header>

	<!-- masukkan body page kmu disini -->
    <div class="content">
    <p align="center" width="100%">
    <div style="padding:0 10px;" id="registerEvent">
	<h3 align="center">Register Event</h3>

    <form method="POST" action="user_event_add_action.php" enctype="multipart/form-data" id="eventForm">
        <table border="1" id="addEvent_table" align="center">

            <tr>
            <td>Event Name</td>
            <td width="1px">:</td>
            <td>
            <textarea rows="3" cols="40" name="eventName" required></textarea>
            </td>
            </tr>

            <tr>
            <td>Description</td>
            <td>:</td>
            <td>
            <textarea rows="8" cols="40" name="eventDesc" required></textarea>
            </td>
            </tr>
            
            <tr>
            <td>Date Start</td>
            <td>:</td>
            <td>
            <input type="date" name="eventDateStart" required>
            </td>
            </tr>

            <tr>
            <td>Time Start</td>
            <td>:</td>
            <td>
            <input type="time" name="eventTimeStart" required>
            </td>
            </tr>

            <tr>
            <td>Date End</td>
            <td>:</td>
            <td>
            <input type="date" name="eventDateEnd">
            </td>
            </tr>

            <tr>
            <td>Time End</td>
            <td>:</td>
            <td>
            <input type="time" name="eventTimeEnd">
            </td>
            </tr>

            <tr>
            <td>Location</td>
            <td>:</td>
            <td>
            <select size="1" name="eventLocation" required>
            <option value="">&nbsp;</option>
            <option value="Meeting Room">Meeting Room</option>;
            <option value="Auditorium">Auditorium</option>;
            <option value="Community Room">Community Room</option>;
            <option value="Conference Room">Conference Room</option>;
            <option value="Children Area">Children Area</option>;
            <option value="Courtyard">Courtyard</option>;
            </select>
            </td>
            </tr>

            <tr>
            <td>Category</td>
            <td>:</td>
            <td>
            <select size="1" name="eventCategory" required>
            <option value="">&nbsp;</option>
            <option value="Children Program">Children Program</option>;
            <option value="Teen Program">Teen Program</option>;
            <option value="Literary Events">Literary Events</option>;
            <option value="Technology and Digital Literacy">Technology and Digital Literacy</option>;
            <option value="Cultural Events">Cultural Events</option>;
            <option value="Health and Wellness Programs">Health and Wellness Programs</option>;
            <option value="Local History and Genealogy">Local History and Genealogy</option>;
            <option value="Community Engagement">Community Engagement</option>;
            <option value="Others">Others</option>;
            </select>
            </td>
            </tr>

            <tr>
            <td>Upload photo</td>
            <td>:</td>
            <td>
            Max size: 2MB<br>
            <input type="file" name="fileToUpload" id="fileToUpload" accept=".jpg, .jpeg, .png">
            </td>
            </tr>

            <tr>
            <td colspan="3" align="right">
            <input type="submit" value="Submit" name="B1">
            <input type="reset" value="Reset" name="B2">
            </td>
            </tr>
        </table>
    </form>
    </div>
    </p>
    </div>
	
	<!-- import external javascript -->
	<!-- put ur js at jss/script.js -->
	<script src="js/script.js"></script>
</body>
</html>