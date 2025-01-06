<?php
session_start();
include 'config.php';

$userEmail = $_SESSION['userEmail'];

// Fetching userID based on userEmail
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

// Handle adding complaint
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issueType = isset($_POST['issueType']) ? $_POST['issueType'] : '';
    $complaintDescription = isset($_POST['complaintDescription']) ? $_POST['complaintDescription'] : '';
    $dateSubmitted = date("Y-m-d"); // Current date
    $complaintStatus = "Pending"; // Default status

    if (empty($issueType) || empty($complaintDescription)) {
        echo '<script>alert("All fields are required!");</script>';
    } else {
        // Handling the file upload
        if (isset($_FILES['complaintPhoto']) && $_FILES['complaintPhoto']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $unique_filename = uniqid() . '-' . basename($_FILES["complaintPhoto"]["name"]);
            $target_file = $target_dir . $unique_filename;

            if (move_uploaded_file($_FILES["complaintPhoto"]["tmp_name"], $target_file)) {
                // Insert into database
                $sql = "INSERT INTO complaint (userID, issueType, complaintDescription, complaintPhoto, complaintStatus, dateSubmitted) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $userID, $issueType, $complaintDescription, $target_file, $complaintStatus, $dateSubmitted);
                
                try {
                    $stmt->execute();
                    echo '<script>alert("Complaint added successfully!");</script>';
                    echo '<script>window.location.href = "complaint_user.php";</script>';  // Redirect to complaint_user.php after displaying the alert
                    exit;
                } catch (mysqli_sql_exception $e) {
                    echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
                }
            } else {
                echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
            }
        } else {
            echo '<script>alert("Complaint photo is required.");</script>';
        }
    }
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
        <h2>Submit Complaint</h2>

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
        <!-- Add Complaint Form -->
        <form action="addcomplaint_user.php" method="post" enctype="multipart/form-data" class="complaint-form">
            Issue Type: <select name="issueType" required>
                <option value="Damaged Book">Damaged Book</option>
                <option value="Facility Malfunctions">Facility Malfunctions</option>
                <option value="Damaged Furniture">Damaged Furniture</option>
                <option value="Wi-Fi/Internet Issues">Wi-Fi/Internet Issues</option>
                <option value="Safety Issues">Safety Issues</option>
                <option value="Computer/Printer Issues">Computer/Printer Issues</option>
                <option value="other">other</option>
            </select><br>
            Complaint Description: <textarea name="complaintDescription" rows="4" cols="50" required></textarea><br>
            Complaint Photo: <input type="file" name="complaintPhoto" accept="image/*"><br>
            <input type="submit" value="Submit Complaint" class="submit-complaint-btn">
        </form>
</div>
</body>
</html>
