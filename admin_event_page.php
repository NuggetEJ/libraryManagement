<?php
session_start();
include("config.php");
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
    $_SESSION["UID"] = $staffID; // Store staffID in the session
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
    <?php include 'admin_event_menu.php';?>

	<!-- header at right side -->
    <header>
		<!-- title your page -->
        <h2>Library Event</h2>
        <a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>
	</header>

	<!-- masukkan body page kmu disini -->
    <div class="content">
        <p>
        <div>
            <a href="admin_event_view.php" class="event-button">View All Events</a>
            <a href="admin_event_view_aevent.php" class="event-button">View Admin Event</a>
        </div>
        </p>
    </div>
	
	<script src="js/script.js"></script>
</body>
</html>