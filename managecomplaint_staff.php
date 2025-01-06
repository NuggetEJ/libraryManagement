<?php
session_start();
include 'config.php';

// Check if the staff is logged in
if (!isset($_SESSION['staffEmail'])) {
    header("Location: login_staff.php");
    exit;
}

// Fetch all complaints
$sql = "SELECT * FROM complaint WHERE complaintStatus IN ('Pending', 'In Progress')";
$result = $conn->query($sql);
$complaints = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
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
        <a href="profile_staff.php"><i class="fa fa-user sidenav-icon"></i> <b> Staff Profile</a>
		<a href="staffbooks.php"><i class="fa fa-book sidenav-icon"></i> Book Management</a>
		<a href="staff_room.php"><i class="fa fa-users sidenav-icon"></i> Room Booking Management</a>
		<a href="admin_event_page.php"><i class="fa fa-calendar sidenav-icon"></i> Library Event Management</a>
		<a href="managecomplaint_staff.php"><i class="fa fa-sticky-note sidenav-icon"></i> Complaint Management</a>
		<a href="staff_feedback.php"><i class="fa fa-comments sidenav-icon"></i> Feedback Management</a>
		<a href="staff_report.php" class="active"><i class="fa fa-file-text sidenav-icon"></i> Report Management</b></a>
        <small class="copyright"><i>Copyright &copy; 2024 - Library System Management</i></small>
	</nav>
    <header>
        <h2>Manage Complaint</h2>

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
    <div class="content">
    <table class="complaint-table2">
        <tr>
            <th>Complaint ID</th>
            <th>User ID</th>
            <th>Issue Type</th>
            <th>Description</th>
            <th>Photo</th>
            <th>Status</th>
            <th>Date Submitted</th>
            <th>Notes</th>
            <th>Action</th>
        </tr>
        <?php foreach ($complaints as $complaint): ?>
            <tr>
                <td><?php echo $complaint['complaintID']; ?></td>
                <td><?php echo $complaint['userID']; ?></td>
                <td><?php echo $complaint['issueType']; ?></td>
                <td><?php echo $complaint['complaintDescription']; ?></td>
                <td><img src="<?php echo $complaint['complaintPhoto']; ?>" alt="Complaint Photo" width="100", height="100"></td>
                <td><?php echo $complaint['complaintStatus']; ?></td>
                <td><?php echo $complaint['dateSubmitted']; ?></td>
                <td><?php echo $complaint['notes']; ?></td>
                <td><a href="updatecomplaint_staff.php?id=<?php echo $complaint['complaintID']; ?>">Update</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
