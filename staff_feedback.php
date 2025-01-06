<?php
session_start(); // Start the session
include("config.php");

$staffEmail = $_SESSION['staffEmail'];

// Fetch staffID based on staffEmail
$sql = "SELECT staffID FROM staff WHERE staffEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $staffEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $staffID = $row['staffID'];
} else {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Library Management System</title>
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
        <h2>List of User Feedbacks</h2>

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

    <!-- masukkan body page kmu disini -->
    <div class="content">
        <div style="text-align: right; padding:10px;">
            <form action="staff_feedback_search.php" method="post">
                <input type="date" placeholder="Search by date..." name="search">
                <input type="submit" value="Search">
            </form> 
        </div>
        <div style="text-align: right; padding:10px;">
        <table id="staff_feedback">
            <tr>
                <th>No</th>
                <th>Username</th>
                <th class="question-cell">Question 1<div class="tooltip">How would you rate your overall experience with the library's services and facilities?</div></th>
                <th class="question-cell">Question 2<div class="tooltip">How satisfied are you with the display of your user profile information in the system?</div></th>
                <th class="question-cell">Question 3<div class="tooltip">How would you rate the process of searching and managing books in the library catalog?</div></th>
                <th class="question-cell">Question 4<div class="tooltip">How satisfied are you with the system's ability to assign and track the resolution of your submitted complaints?</div></th>
                <th class="question-cell">Question 5<div class="tooltip">How satisfied are you with the system's ability to manage your room booking?</div></th>
                <th class="question-cell">Question 6<div class="tooltip">How satisfied are you with the system's ability to manage your event booking?</div></th>
                <th class="question-cell">Question 7<div class="tooltip">How satisfied are you with the assistance and support provided by our library staff?</div></th>
                <th class="question-cell">Question 8<div class="tooltip">Will you recommed our library system to other people?</div></th>
                <th>Suggestions</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php
                $sql = "SELECT feedback.*, users.userName 
                        FROM feedback
                        INNER JOIN users ON feedback.userID = users.userID";

                $result = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($result) > 0) {
                // output data of each row
                    $numrow=1;
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $numrow . "</td><td>". $row["userName"] . "</td><td>" . $row["overall_rate"]. "</td><td>" . $row["userDisplay_rate"] . "</td><td>" . $row["manageBook_rate"] . "</td>
                        <td>" . $row["manageComplaint_rate"] . "</td><td>" . $row["manageBooking_rate"] . "</td><td>" . $row["manageEvent_rate"] . "</td><td>" . $row["staff_rate"] . "</td>
                        <td>" . $row["recommendation_rate"] . "</td><td>" . $row["suggestion"] . "</td><td>" . $row["date"] . "</td>";
                        echo '<td><a href="delete_feedback.php?id=' . $row["feedbackID"] . '" onClick="return confirm(\'Delete?\');">Delete</a></td>';
                        echo "</tr>";
                        $numrow++;
                    }
                } 
                else {
                    echo '<tr><td colspan="13">0 results</td></tr>';
                } 
            mysqli_close($conn); 
            ?>
        </table>
        </div>
    </div>
</body>
</html>