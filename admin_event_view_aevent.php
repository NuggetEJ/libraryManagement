<?php
session_start();
include("config.php");

if(!isset($_SESSION["staffEmail"])){
    header("location:logout.php");
    exit;
}

$sql = "SELECT staffID FROM staff WHERE staffEmail = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    $staffEmail = $_SESSION["staffEmail"];
    mysqli_stmt_bind_param($stmt, "s", $staffEmail);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $staffID);

    if (mysqli_stmt_fetch($stmt)) {
        $_SESSION["UID"] = $staffID;
    }

    mysqli_stmt_close($stmt);
} else {
    // Handle the case when the statement preparation fails
    echo "Error in preparing the statement.";
    exit;
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
        
        <h2>List of My Events</h2>
        <button class="event-detail-button" onclick="location.href='admin_event_add.php'">Add Event</button>
        <p>
<div style="padding:0 10px;">
<table border="1" width="100%" id="event_view">
    <tr>
    <th width="5%">No</th>
    <th width="20%">Event Name</th>
    <th width="30%">Description</th>
    <th width="30%">Photo</th>
    <th width="10%">Action</th>
    </tr>
    <?php
    $sql = "SELECT * FROM events WHERE staffID=". $_SESSION["UID"];
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    $numrow=1;
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $numrow . "</td><td>". $row["eventName"] . "</td><td>" . $row["eventDesc"]. "</td><td>";
        
        // Display the photo if it exists
        if (!empty($row["eventPhoto"])) {
            $photoPath = "uploads/" . $row["eventPhoto"];
            echo '<img src="' . $photoPath . '" alt="Event Photo" style="max-width: 300px;">';
        }
        
        echo "</td><td>";
        echo '<a href="admin_event_detail.php?id=' . $row["eventID"] . '">View Detail</a>'; // Link to the event detail page
        $numrow++;
        }
    } else {
    echo '<tr><td colspan="6">0 results</td></tr>';
    }
    mysqli_close($conn);
    ?>
</table>
</div>
        </p>
    </div>
	
	<!-- import external javascript -->
	<!-- put ur js at jss/script.js -->
	<script src="js/script.js"></script>
</body>
</html>