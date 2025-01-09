<?php
session_start();
include 'config.php';

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
$sql = "SELECT * FROM complaint WHERE userID = ?";
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


// Handle form submission to save changes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadErrors = false;  // Variable to keep track of upload errors

    foreach ($_POST['complaints'] as $complaintID => $values) {
        $issueType = $values['issueType'];
        $complaintDescription = $values['complaintDescription'];
    
        // Check if a new photo was uploaded
        $target_file = null;
        if (isset($_FILES['complaints']['tmp_name'][$complaintID]['complaintPhoto']) && 
            $_FILES['complaints']['tmp_name'][$complaintID]['complaintPhoto'] !== '') {
            
            $target_dir = "uploads/";
            $unique_filename = uniqid() . '-' . basename($_FILES["complaints"]["name"][$complaintID]["complaintPhoto"]);
            $target_file = $target_dir . $unique_filename;
    
            if (move_uploaded_file($_FILES["complaints"]["tmp_name"][$complaintID]["complaintPhoto"], $target_file)) {
                // Update database with the new photo path
            } else {
                $uploadErrors = true;  // Set to true if an upload error occurs
            }
        }
        if (isset($target_file)) {
            $sql = "UPDATE complaint SET issueType = ?, complaintDescription = ?, complaintPhoto = ? WHERE complaintID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $issueType, $complaintDescription, $target_file, $complaintID);
        } else {
            $sql = "UPDATE complaint SET issueType = ?, complaintDescription = ? WHERE complaintID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $issueType, $complaintDescription, $complaintID);
        }
        $stmt->execute();
        
    }

    // Display the error message if $uploadErrors is true
    if ($uploadErrors) {
        echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
    } else {
        echo '<script>alert("Changes saved successfully!");</script>';
    }

    echo '<script>window.location.href = "complaint_user.php";</script>';
    exit;
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
        <h2>Edit Complaint</h2>

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
        <!-- Edit Complaint Form -->
        <form action="editcomplaint_user.php" method="post" enctype="multipart/form-data">
            <table class="complaint-table2">
                <tr>
                    <th>Complaint Type</th>
                    <th>Complaint Description</th>
                    <th>Photo</th>
                </tr>
                <?php foreach ($complaints as $complaint): ?>
                    <tr>
                    <td>
                        <select name="complaints[<?php echo $complaint['complaintID']; ?>][issueType]" required>
                        <option value="Damaged Book" <?php if ($complaint['issueType'] == 'Damaged Book') echo 'selected'; ?>>Damaged Book</option>
                        <option value="Facility Malfunctions" <?php if ($complaint['issueType'] == 'Facility Malfunctions') echo 'selected'; ?>>Facility Malfunctions</option>
                        <option value="Damaged Furniture" <?php if ($complaint['issueType'] == 'Damaged Furniture') echo 'selected'; ?>>Damaged Furniture</option>
                        <option value="Wi-Fi/Internet Issues" <?php if ($complaint['issueType'] == 'Wi-Fi/Internet Issues') echo 'selected'; ?>>Wi-Fi/Internet Issues</option>
                        <option value="Safety Issues" <?php if ($complaint['issueType'] == 'Safety Issues') echo 'selected'; ?>>Safety Issues</option>
                        <option value="Computer/Printer Issues" <?php if ($complaint['issueType'] == 'Computer/Printer Issues') echo 'selected'; ?>>Computer/Printer Issues</option>
                        <option value="other" <?php if ($complaint['issueType'] == 'other') echo 'selected'; ?>>other</option>
                    </select></td>
                        <td>
                    <textarea name="complaints[<?= $complaint['complaintID']; ?>][complaintDescription]" required>
                    <?= htmlspecialchars($complaint['complaintDescription'], ENT_QUOTES, 'UTF-8'); ?>
                    </textarea>
                        </td>
                        <td><input type="file" name="complaints[<?php echo $complaint['complaintID']; ?>][complaintPhoto]"></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <div class="button-container">
                <input type="submit" value="Save Changes" class="savechanges-complaint-btn">
            </div>
        </form>
    </div>
</body>
</html>