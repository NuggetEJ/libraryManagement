<?php
session_start();
include('config.php');


// Check if the ID parameter is set
if (isset($_GET['id'])) {
    $bookingID = $_GET['id'];

    // Fetch existing Booking details from the database
    $sql = "SELECT * FROM booking WHERE bookingID = '$bookingID'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        ?>
        <!DOCTYPE html>
        <head>
        <title>Room Booking Management</title>
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

            <header>
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

            <div class= "content">
                <br>
                <form method="POST" action="user_booking_action.php" enctype="multipart/form-data">
                <input type="hidden" name="bookingID" value="<?php echo $bookingID; ?>">
                <table style="margin: auto;">
                <tr>
                    <td>Room Name*</td>
                    <td>:</td>
                    <td>
                        <input type="text" name="room_name" size="15" value="<?php echo $row['room_name']; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Date*</td>
                    <td>:</td>
                    <td>
                        <input type="date" name="date" size= "15" value="<?php echo $row['date'];?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Start Time*</td>
                    <td>:</td>
                    <td>
                        <input type="time" name="start_time" size= "15" value="<?php echo $row['start_time'];?>" required>
                    </td>
                </tr>
                <tr>
                    <td>End Time*</td>
                    <td>:</td>
                    <td>
                    <input type="time" name="end_time" size= "15" value="<?php echo $row['end_time'];?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Capacity*</td>
                    <td>:</td>
                    <td>
                        <input type="number" name="capacity" size= "15" value="<?php echo $row['capacity'];?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Purpose*</td>
                    <td>:</td>
                    <td>
                        <textarea rows="4" name="purpose" cols="30" required><?php echo $row['purpose']; ?></textarea>
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
        echo '<a href="user_booking.php">Back</a>';
    } else {
        echo "Error fetching Booking details: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    // Redirect to the Booking list if no ID is provided
    header("Location: user_booking.php");
    exit();
}
?>
