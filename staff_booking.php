<?php
session_start();
include("config.php");

// $staffID = $_SESSION["UID"]; use this one to get staffID
$staffID = "S14";
?>

<!DOCTYPE html>
<html>
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

    <!-- Booking Detail -->
    <div class= "content">
    <br>
    <div style="text-align: right; padding:10px;">
        <form action="staff_booking_search.php" method="post">
            <input type="text" placeholder="Search Room" name="search">
            <input type="submit" value="Search">
        </form> 
    </div>
    <br><br>    
    <table id="staff_booking">
        <tr>
            <th width="5%">No</th>
            <th width="10%">Room Name</th>
            <th width="15%">Date</th>
            <th width="15%">Start Time</th>
            <th width="15%">End Time</th>
            <th width="20%">Capacity (Must be less or equal to room capacity)</th>
            <th width="40%">Purpose</th>
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
                
                echo '<td> <a href="staff_booking_delete.php?id=' . $row["bookingID"] . '"onClick="return confirm(\'Delete?\');">Delete</a> </a>&nbsp;</td>';
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
    <a href="staff_room.php" class="booking-button">Back to Room Description</a><br><br>
    </div>
    <script src="js/script.js"></script>
</body>
</html>

       
