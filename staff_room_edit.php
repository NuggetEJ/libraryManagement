<?php
session_start();
include('config.php');

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

// Check if the ID parameter is set
if (isset($_GET['id'])) {
    $room_descID = $_GET['id'];

    // Fetch existing room details from the database
    $sql = "SELECT * FROM room_description WHERE room_descID = '$room_descID'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Display the form with existing room details
        ?>
        <!DOCTYPE html>
        <head>
            <title>Admin Library Management System</title>
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
        <a href="profile_staff.php"><i class="fa fa-user sidenav-icon"></i> <b> Staff Profile</a>
		<a href="staffbooks.php"><i class="fa fa-book sidenav-icon"></i> Book Management</a>
		<a href="staff_room.php"><i class="fa fa-users sidenav-icon"></i> Room Booking Management</a>
		<a href="admin_event_page.php"><i class="fa fa-calendar sidenav-icon"></i> Library Event Management</a>
		<a href="managecomplaint_staff.php"><i class="fa fa-sticky-note sidenav-icon"></i> Complaint Management</a>
		<a href="staff_feedback.php"><i class="fa fa-comments sidenav-icon"></i> Feedback Management</a>
		<a href="staff_report.php" class="active"><i class="fa fa-file-text sidenav-icon"></i> Report Management</b></a>
        <small class="copyright"><i>Copyright &copy; 2024 - Library System Management</i></small>
	</nav>
    
    <!-- header at the right side -->
    <header>
        <!-- title your page -->
        <h2>Room Booking Management</h2>

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

    <div class= "content">
        <br>
        <form method="POST" action="staff_room_action.php">
            <input type="hidden" name="room_descID" value="<?php echo $room_descID; ?>">
            <table style="margin: auto;">
                <tr>
                    <td>Room Name*</td>
                    <td>:</td>
                    <td>
                        <input type="text" name="room_name" size="15" value="<?php echo $row['room_name']; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Description*</td>
                    <td>:</td>
                    <td>
                        <textarea rows="4" name="description" cols="100" required><?php echo $row['description']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Capacity*</td>
                    <td>:</td>
                    <td>
                        <textarea rows="4" name="capacity" cols="10" required><?php echo $row['capacity']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Availability*</td>
                    <td>:</td>
                    <td>
                        <textarea rows="4" name="availability" cols="30" required><?php echo $row['availability']; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="right">
                        <input type="submit" value="Update" name="updateBtn">
                    </td>
                </tr>
            </table>
            </form>
</div>
</body>
</html>

<?php
} else {
    echo "Error fetching room details: " . mysqli_error($conn);
}

mysqli_close($conn);
} else {
    // Redirect to the room list if no ID is provided
    header("Location: staff_room.php");
    exit();
}
?>
