<?php
session_start();
include 'config.php';

$userEmail = $_SESSION['userEmail'];

$sql = "SELECT * FROM users WHERE userEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

$userName = '';
$userEmail = '';
$userPhone = '';
$userAdd = '';
$userState = '';
$userDob = '';
$userGender = '';
$userIC = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userName = $row['userName'];
    $userEmail = $row['userEmail'];
    $userPhone = $row['userPhone'];
    $userAdd = $row['userAdd'];
    $userState = $row['userState'];
    $userDob = $row['userDob'];
    $userGender = $row['userGender'];
    $userIC = $row['userIC'];
}else {
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
        <h2>Edit Profile</h2>

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

    <div class="content">
    <form action="successedit_user.php" method="post" enctype="multipart/form-data">
        <table class="complaint-table">
    <tr>
        <td><strong>Name:</strong></td>
        <td><input type="text" name="userName" value="<?php echo $userName; ?>"></td>
    </tr>
    <tr>
        <td><strong>IC Number:</strong></td>
        <td><input type="text" name="userIC" value="<?php echo $userIC; ?>"></td>
    </tr>
    <tr>
        <td><strong>Date of Birth:</strong></td>
        <td><input type="date" name="userDob" value="<?php echo $userDob; ?>"></td>
    </tr>
    <tr>
        <td><strong>Gender:</strong></td>
        <td>
            <select name="userGender" value="<?php echo $userGender; ?>">
                <option>Male</option>
                <option>Female</option>
            </select>
        </td>
    </tr>
    <tr>
        <td><strong>Phone:</strong></td>
        <td><input type="text" name="userPhone" value="<?php echo $userPhone; ?>"></td>
    </tr>
    <tr>
        <td><strong>Home Address:</strong></td>
        <td><textarea name="userAdd" rows="4" cols="50"><?php echo $userAdd; ?></textarea></td>
    </tr>
    <tr>
        <td><strong>State:</strong></td>
        <td>
            <select name="userState" value="<?php echo $userState; ?>">
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
            <input type="hidden" name="userEmail" value="<?php echo $userEmail; ?>">
            <br>
            <input type="submit" value="Save Changes" class="savechanges-complaint-form">
        </div>
    </form>
</body>
</html>