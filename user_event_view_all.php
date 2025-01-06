<?php
session_start();
include("config.php");

if(!isset($_SESSION["userEmail"])){
    header("location:logout.php");
    exit;
}

$sql = "SELECT userID FROM users WHERE userEmail = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    $userEmail = $_SESSION["userEmail"];
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userID);

    if (mysqli_stmt_fetch($stmt)) {
        $_SESSION["UID"] = $userID; // Store userID in the session
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
        <a href="profile_user.php"><i class="fa fa-user sidenav-icon"></i> <b> User Profile</a>
		<a href="userbooks.php"><i class="fa fa-book sidenav-icon"></i> Book Management</a>
		<a href="user_booking.php"><i class="fa fa-users sidenav-icon"></i> Room Booking Management</a>
		<a href="user_event_page.php"><i class="fa fa-calendar sidenav-icon"></i> Library Event Management</a>
		<a href="complaint_user.php"><i class="fa fa-sticky-note sidenav-icon"></i> Complaint Form</a>
		<a href="user_feedback.php"><i class="fa fa-comments sidenav-icon"></i> Feedback Form</a>
		<a href="user_activity.php" class="active"><i class="fa fa-file-text sidenav-icon"></i> Activity History</b></a>
        <small class="copyright"><i>Copyright &copy; 2024 - Library System Management</i></small>
	</nav>
	<!-- header at right side -->
    <header>
		<!-- title your page -->
        <h2>Library Event</h2>
        <a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>;
	</header>

	<!-- masukkan body page kmu disini -->
    <div class="content">
        
        <h2>List of All Events</h2>
        <button class="event-detail-button" onclick="location.href='user_event_add.php'">Add Event</button>
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
    $sql = "SELECT * FROM events";
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
        echo '<a href="user_event_detail.php?id=' . $row["eventID"] . '">View Detail</a>';
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

</body>
</html>