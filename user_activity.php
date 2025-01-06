<?php
session_start(); // Start the session
include("config.php");

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Library Management System</title>
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
        <h2>My Activity History</h2>

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

    <!-- masukkan body page kmu disini -->
    <div class="content" >
        <br>
        <table id="user_activity">
            <tr>
                <th width="3%">No</th>
                <th width="20%">Activity History</th>
                <th width="20%">Description</th>
                <th width="10%">Date</th>
            </tr>
            <?php
                $sql = "SELECT 'Reserve a book' as activity_type, book_reservations.bookID as description, book_reservations.bookReservationDate as date
                        FROM book_reservations
                        INNER JOIN users ON book_reservations.userID = users.userID
                        WHERE users.userID = $userID
                UNION
                        SELECT 'Make a Complaint' as activity_type, complaint.issueType as description, complaint.dateSubmitted as date
                        FROM complaint
                        INNER JOIN users ON complaint.userID = users.userID
                        WHERE users.userID = $userID
                UNION
                        SELECT 'Give Feeedback' as activity_type, feedback.suggestion as description, feedback.date as date
                        FROM feedback
                        INNER JOIN users ON feedback.userID = users.userID
                        WHERE users.userID = $userID
                UNION
                        SELECT 'Make a Booking Room' as activity_type, booking.room_name as description, booking.date as date
                        FROM booking
                        INNER JOIN users ON booking.userID = users.userID
                        WHERE users.userID = $userID
                UNION
                        SELECT 'Make Booking for Library Event' as activity_type, events.eventName as description, events.eventDateStart as date
                        FROM events
                        INNER JOIN users ON events.userID = users.userID
                        WHERE users.userID = $userID";

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    $numrow = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $numrow . "</td><td>". $row["activity_type"] . "</td><td>" . $row["description"] . "</td><td>" . $row["date"] . "</td>";
                        echo "</tr>";
                        $numrow++;
                    }
                } else {
                    echo '<tr><td colspan="4">0 results</td></tr>';
                }
                mysqli_close($conn);
            ?>
        </table>
    </div>
</body>
</html>