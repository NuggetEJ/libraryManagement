<?php
session_start();
include("config.php");
?>

<!DOCTYPE html>
<html>
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

    <!-- Booking Detail -->
    <div class= "content">
        <br>
    <a href="user_room.php" class="booking-button">Back to Room Description</a>
    <br><br>
        <table id="user_booking">
            <tr>
                <th width="5%">No</th>
                <th width="10%">Room Name</th>
                <th width="20%">Date</th>
                <th width="20%">Start Time</th>
                <th width="20%">End Time</th>
                <th width="30%">Capacity (Must be less or equal to room capacity)</th>
                <th width="30%">Purpose</th>
                <th width="10%">Action</th>
            </tr>
            <?php
            $sql = "SELECT * FROM booking";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                $numrow = 1;
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $numrow . "</td>
                    <td>" . $row["room_name"] . "</td>
                    <td>" . $row["date"] . "</td>
                    <td>" . $row["start_time"] . "</td>
                    <td>" . $row["end_time"] . "</td>
                    <td>" . $row["capacity"] . "</td>
                    <td>" . $row["purpose"] . "</td>";
                    
                    echo '<td> <a href="user_booking_edit.php?id=' . $row["bookingID"] . '">Edit</a>&nbsp;|&nbsp;';
                    echo '<a href="user_booking_delete.php?id=' . $row["bookingID"] . '" onClick="return confirm(\'Delete?\');">Delete</a> </td>';
                    echo "</tr>" . "\n\t\t";
                    
                    // Increment the row number for the next iteration
                    $numrow++;
                }
            } else {
                echo '<tr><td colspan="7">0 results</td></tr>';
            } 

            mysqli_close($conn);
        ?>
        </table>
        <br><br><br><br>
        <!-- Booking Form -->
        <form method="POST" action="user_booking_action.php" enctype="multipart/form-data">
        <table style="margin: auto;">
            <tr>
                <td>Room Name*</td>
                <td>:</td>
                <td>
                    <input type="text" id="room_name" size="15" name="room_name" required>
                </td>
            </tr>
            <tr>
            <td>Date*</td>
            <td>:</td>
            <td>
                <input type="date" id="date" size="15" name="date" required>
            </td>
        </tr>
            <tr>
                <td>Start Time*</td>
                <td>:</td>
                <td>
                    <input type="time" id="start_time" size="15" name="start_time" required>
                </td>
            </tr>
            <tr>
                <td>End Time*</td>
                <td>:</td>
                <td>
                    <input type="time" id="end_time" size="15" name="end_time" required>
                </td>
            </tr>
            <tr>
                <td>Capacity*</td>
                <td>:</td>
                <td>
                    <input type="number" id="capacity" size="15" name="capacity" min="1" required>
                </td>
            </tr>
            <tr>
                <td>Purpose*</td>
                <td>:</td>
                <td>
                    <textarea id="purpose" cols="30" name="purpose" rows="4" required></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="right">
                    <input type="submit" value="Submit" name="B1">
                    <input type="reset" value="Reset" name="B2">
                </td>
            </tr>
        </table>
        </form>
    </div>
</body>
</html>

       
