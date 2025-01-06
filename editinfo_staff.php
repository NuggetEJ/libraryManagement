<?php
session_start();
include 'config.php';

$staffEmail = $_SESSION['staffEmail'];

$sql = "SELECT * FROM staff WHERE staffEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $staffEmail);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $staffName = $row['staffName'];
    $staffID = $row['staffID'];
    $staffEmail = $row['staffEmail'];
    $staffPhone = $row['staffPhone'];
    $staffAdd = $row['staffAdd'];
    $staffState = $row['staffState'];
    $staffDob = $row['staffDob'];
    $staffGender = $row['staffGender'];
    $staffIC = $row['staffIC'];
    if (!empty($row['staffPhoto'])) {
        $staffPhoto = $row['staffPhoto'];
    }
} else {
    echo "No data found.";
}
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
        <h2>Edit Profile</h2>

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
    <form action="successedit_staff.php" method="post" enctype="multipart/form-data">
    <table class="complaint-table">
    <tr>
        <td><strong>Profile Picture:</strong></td>
        <td><input type="file" name="staffPhoto" accept="image/*"></td>
    </tr>
    <tr>
        <td><strong>Name:</strong></td>
        <td><input type="text" name="staffName" value="<?php echo $staffName; ?>"></td>
    </tr>
    <tr>
        <td><strong>Staff ID:</strong></td>
        <td><input type="text" name="staffID" value="<?php echo $staffID; ?>"></td>
    </tr>
    <tr>
        <td><strong>IC Number:</strong></td>
        <td><input type="text" name="staffIC" value="<?php echo $staffIC; ?>"></td>
    </tr>
    <tr>
        <td><strong>Date of Birth:</strong></td>
        <td><input type="date" name="staffDob" value="<?php echo $staffDob; ?>"></td>
    </tr>
    <tr>
        <td><strong>Gender:</strong></td>
        <td>
            <select name="staffGender" value="<?php echo $staffGender; ?>">
                <option>Male</option>
                <option>Female</option>
            </select>
        </td>
    </tr>
    <tr>
        <td><strong>Phone:</strong></td>
        <td><input type="text" name="staffPhone" value="<?php echo $staffPhone; ?>"></td>
    </tr>
    <tr>
        <td><strong>Home Address:</strong></td>
        <td><textarea name="staffAdd" rows="4" cols="50"><?php echo $staffAdd; ?></textarea></td>
    </tr>
    <tr>
        <td><strong>State:</strong></td>
        <td>
            <select name="staffState" value="<?php echo $staffState; ?>">
                <option>Sabah</option>
                <option>Sarawak</option>
                <option>Kedah</option>
                <option>Johor</option>
                <option>Kelantan</option>
                <option>Perak</option>
                <option>Selangor</option>
                <option>Melaka</option>
                <option>Negeri Sembilan</option>
                <option>Pahang</option>
                <option>Perlis</option>
                <option>Pulau Pinang</option>
                <option>Terengganu</option>
            </select>
        </td>
    </tr>
</table>
        <input type="hidden" name="staffEmail" value="<?php echo $staffEmail; ?>">
        <br>
        <input type="submit" value="Save Changes" class="savechanges-complaint-form">
        </div>
    </form>
</body>
</html>