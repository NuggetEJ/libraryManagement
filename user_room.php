<?php
session_start();
include("config.php");

$userEmail = $_SESSION['userEmail'];

// Fetch staffID based on staffEmail
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Management System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <h2>Room Booking Management</h2>

        <?php 
		// Check if the user is not logged in, redirect to login page
		//if (!isset($_SESSION["UID"])) {
			//header("Location: index.php");
			//exit();
		//}
		//else{
			echo '<a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>';	
		//}			
		?>
	</header>

    <!-- Room Detail -->
    <div class= "content">
    <br><br>
        <table id="user_booking">
        <tr>
            <th width="5%">No</th>
            <th width="15%">Room Name</th>
            <th width="50%">Room Description</th>
            <th width="10%">Capacity</th>
            <th width="25%">Availability</th>
        </tr>
        <?php
        $sql = "SELECT * FROM room_description";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
        // output data of each row
        $numrow = 1;
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $numrow . "</td>
                <td>" . $row["room_name"] . "</td>
                <td>" . $row["description"] . "</td>
                <td>" . $row["capacity"] . "</td>
                <td>" . $row["availability"] . "</td>";
     

                // Increment the row number for the next iteration
                $numrow++;
            }
        } else {
            echo '<tr><td colspan="7">0 results</td></tr>';
        } 
        mysqli_close($conn);
        ?>
        </table>

        <a href="user_booking.php" class="booking-button">Go to Booking</a>
    </div>
	<script src="js/script.js"></script>
</body>
</html>
