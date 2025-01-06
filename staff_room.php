<?php
session_start();
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

    <!-- Room Detail -->
    <div class= "content">
    <br><br>
    <table id="staff_booking">
        <tr>
        <th width="5%">No</th>
            <th width="15%">Room Name</th>
            <th width="50%">Room Description</th>
            <th width="10%">Capacity</th>
            <th width="25%">Availability</th>
            <th width="15%">Action</th>
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
         
                echo '<td> <a href="staff_room_edit.php?id=' . $row["room_descID"] . '">Edit</a>&nbsp;|&nbsp;';
                echo '<a href="staff_room_delete.php?id=' . $row["room_descID"] . '" onClick="return confirm(\'Delete?\');">Delete</a> </td>';
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

    <a href="staff_booking.php" class="booking-button">Go to Booking</a>
    <!-- Spacing -->
    <br><br><br><br>
    
    <!-- Form for adding new rooms -->
    <form method="POST" action="staff_room_action.php" enctype="multipart/form-data">
        <table style="margin: auto;">
            <tr>
                <td>Room Name*</td>
                <td>:</td>
                <td>
                    <input type="text" name="room_name" size="10" required>                                    
                </td>
            </tr>
            <tr>
                <td>Description*</td>
                <td>:</td>
                <td>
                    <textarea rows="4" name="description" cols="60" required></textarea>
                </td>
            </tr>
            <tr>
                <td>Capacity*</td>
                <td>:</td>
                <td>
                    <textarea rows="4" name="capacity" cols="10" required></textarea>
                </td>
            </tr>
            <tr>
                <td>Availability*</td>
                <td>:</td>
                <td>
                    <textarea rows="4" name="availability" cols="30" required></textarea>
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
