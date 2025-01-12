<?php
session_start();
require_once(__DIR__ . '/config.php');

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

// Fetch user's complaints based on userID
$sql = "SELECT issueType, complaintDescription, complaintPhoto, complaintStatus, dateSubmitted, notes FROM complaint WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$complaints = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}
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

    <header>
        <h2>Complaint</h2>

        <?php 
		// Check if the user is not logged in, redirect to login page
		if (!isset($_SESSION["userEmail"])) {
			header("Location: login_staff.php");
			exit();
		}
		else{
            <a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>
		}			
		?>
    </header>
    <div class="content">
        <!-- Check if complaints are available -->
        <?php if (!empty($complaints)): ?>
            <table class="complaint-table2">
                <tr>
                    <th>Complaint Type</th>
                    <th>Complaint Description</th>
                    <th>Photo</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Notes</th>
                </tr>
                <?php foreach ($complaints as $complaint): ?>
                    <tr>
                        <td><?php echo $complaint['issueType']; ?></td>
                        <td><?php echo $complaint['complaintDescription']; ?></td>
                        <td><img src="<?php echo $complaint['complaintPhoto']; ?>" alt="Complaint Photo" width="100" height="100"></td>
                        <td><?php echo $complaint['complaintStatus']; ?></td>
                        <td><?php echo $complaint['dateSubmitted']; ?></td>
                        <td><?php echo $complaint['notes']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No complaints found.</p>
        <?php endif; ?>
        <div class="complaintlink-container">
            <a href="addcomplaint_user.php" class="complaint-link">Add Complaint</a>
            <a href="editcomplaint_user.php" class="complaint-link">Edit Complaint</a>
        </div>
    </div>
</body>
</html>