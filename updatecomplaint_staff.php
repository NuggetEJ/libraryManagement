<?php
session_start();
include 'config.php';

// Check if the staff is logged in
if (!isset($_SESSION['staffEmail'])) {
    header("Location: login_staff.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaintID = $_POST['complaintID'];
    $complaintStatus = $_POST['complaintStatus'];
    $notes = $_POST['notes'];

    // Update the complaint in the database
    $sql = "UPDATE complaint SET complaintStatus=?, notes=? WHERE complaintID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $complaintStatus, $notes, $complaintID);
    $stmt->execute();

    header("Location: managecomplaint_staff.php");
    exit;
}

// Fetch the complaint details
$complaintID = $_GET['id'];
$sql = "SELECT * FROM complaint WHERE complaintID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $complaintID);
$stmt->execute();
$result = $stmt->get_result();
$complaint = $result->fetch_assoc();
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
        <h2>Update Complaint</h2>

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
    <form action="updatecomplaint_staff.php" method="post" class="complaint-form">
        <input type="hidden" name="complaintID" value="<?php echo $complaint['complaintID']; ?>">
        Status: <select name="complaintStatus">
            <option value="Pending" <?php if ($complaint['complaintStatus'] == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="In Progress" <?php if ($complaint['complaintStatus'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
            <option value="Resolved" <?php if ($complaint['complaintStatus'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
        </select><br><br>
        Notes: <textarea name="notes" rows="4" cols="50"><?php echo $complaint['notes']; ?></textarea><br>
        <input type="submit" value="Update" class="update-complaint-btn">
    </form>
</div>

</body>
</html>
