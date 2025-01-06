<?php
session_start(); // Start the session
include("config.php");

$staffEmail = $_SESSION['staffEmail'];

// Fetch staffID based on staffEmail
$sql = "SELECT staffID FROM staff WHERE staffEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $staffEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $staffID = $row['staffID'];
} else {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Library Management System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,  initial-scale=1.0">

	<!-- import css style -->
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<nav class="sidenav" id="mySidenav">
		<!-- logo lib -->
        <img src="img/logo.png" alt="logo" class="logo-img">

		<!-- link nav + its own icon -->
        <a href="profile_staff.php"><i class="fa fa-user sidenav-icon"></i> <b> Staff Profile</a>
		<a href="staffbooks.php"><i class="fa fa-book sidenav-icon"></i> Book Management</a>
		<a href="staff_room.php"><i class="fa fa-users sidenav-icon"></i> Room Booking Management</a>
		<a href="admin_event_page.php"><i class="fa fa-calendar sidenav-icon"></i> Library Event Management</a>
		<a href="managecomplaint_staff.php"><i class="fa fa-sticky-note sidenav-icon"></i> Complaint Management</a>
		<a href="staff_feedback.php"><i class="fa fa-comments sidenav-icon"></i> Feedback Management</a>
		<a href="staff_report.php" class="active"><i class="fa fa-file-text sidenav-icon"></i> Report Management</b></a>
        <small class="copyright"><i>Copyright &copy; 2024 - Library System Management</i></small>
	</nav>

	<!-- header at right side -->
    <header>
		<!-- title your page -->
        <h2>Report Insights</h2>

        <?php 
		// Check if the user is not logged in, redirect to login page
		if (!isset($_SESSION["staffEmail"])) {
			header("Location: index.php");
			exit();
		}
		else{
			echo '<a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>';	
		}			
		?>
	</header>

    <!-- masukkan body page kmu disini -->
    <div class="content" >
        <section class="grid">
            <a href="staff_report_books.php" class="card">
                <h2>Books Report &rarr;</h2>
                <p>Books Return Status, Available Books per Month</p>
            </a>

            <a href="staff_report_complaint.php" class="card">
                <h2>Complaint Report &rarr;</h2>
                <p>Complaint Status, Popular Complaint Issue Types</p>
            </a>

            <a href="staff_report_feedback.php" class="card">
                <h2>Feedback Report &rarr;</h2>
                <p>User Feedbacks Analysis</p>
            </a>

            <a href="staff_report_roombooking.php" class="card">
                <h2>Room Report &rarr;</h2>
                <p>Popular Booked Room</p>
            </a>

            <a href="staff_report_event.php" class="card">
                <h2>Event Report &rarr;</h2>
                <p>Popular Event Booking Categories</p>
            </a>
        </section>
    </div>
</body>
</html>