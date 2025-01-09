<?php
session_start();
include "config.php";
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
        <h2>Library Event</h2>
        <a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>
	</header>

	<!-- masukkan body page kmu disini -->
    <div class="content">
        <?php
        
if (isset($_GET["id"]) && $_GET["id"] != "") {
    $id = $_GET["id"];
    $sql = "SELECT * FROM events WHERE eventID = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Display detailed information here
        echo "<h2>" . $row["eventName"] . "</h2>";
        echo "<p>Description: " . $row["eventDesc"] . "</p>";
        echo "<p>Date & Time Start: " . $row["eventDateStart"] . " " . $row["eventTimeStart"] . "</p>";
        echo "<p>Date & Time End: " . $row["eventDateEnd"] . " " . $row["eventTimeEnd"] . "</p>";
        echo "<p>Location: " . $row["eventLocation"] . "</p>";
        echo "<p>Category: " . $row["eventCategory"] . "</p>";
        echo "<p>Status: " . $row["eventStatus"] . "</p>";
        echo "<p>User ID: " . (($row["userID"] !== null) ? $row["userID"] : "N/A") . "</p>";

        // Display the photo if it exists
        if (!empty($row["eventPhoto"])) {
            $photoPath = "uploads/" . $row["eventPhoto"];
            echo '<img src="' . $photoPath . '" alt="Event Photo" style="max-width: 300px;">';
        }
        echo '<div class="button-container">';
        echo '<button class="event-detail-button" onclick="window.location.href=\'admin_event_edit.php?id=' . $row["eventID"] . '\'">Edit</button>';
        echo '<button class="event-detail-button" onclick="window.location.href=\'admin_event_delete.php?id=' . $row["eventID"] . '\'">Delete</button>';
        echo '<button class="event-detail-button" onclick="window.location.href=\'admin_event_view.php' . '\'">Back</button>';
        echo '</div>';
        echo "</tr>" . "\n\t\t";
    } else {
        echo "Event not found.";
    }
    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>
</div>
        </p>
    </div>
	
	<!-- import external javascript -->
	<!-- put ur js at jss/script.js -->
	<script src="js/script.js"></script>
</body>
</html>