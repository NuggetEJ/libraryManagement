<?php
session_start();
include 'config.php';

// If staff is not logged in, redirect to login page
if (!isset($_SESSION['staffEmail'])) {
    header("Location: login_staff.php");
    exit();
}

// Fetch reservation details
$sql = "SELECT * FROM book_reservations";
$result = $conn->query($sql);

$reservations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}

// Check if the form is submitted for updating reservation status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateStatus'])) {
    $reservationID = $_POST['reservationID'];
    $newStatus = $_POST['newStatus'];

    // Update the reservation status in the database
    $updateSql = "UPDATE book_reservations SET bookReservationStatus = ? WHERE bookReservationID = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $newStatus, $reservationID);

    if ($updateStmt->execute()) {
        // If the new status is "Completed," update the book availability and bookReturnedStatus in the 'books' table
        if ($newStatus == 'Completed') {
            $bookID = $_POST['bookID'];

            // Start a transaction
            $conn->begin_transaction();

            // Update the book availability and bookReturnStatus in the 'books' table
            $updateBookSql = "UPDATE books SET bookAvailability = 'Unavailable', bookReturnStatus = 'Unreturned' WHERE bookID = ?";
            $updateBookStmt = $conn->prepare($updateBookSql);
            $updateBookStmt->bind_param("i", $bookID);

            // Execute the book update statement
            if ($updateBookStmt->execute()) {
                // If both updates are successful, commit the transaction
                $conn->commit();
                echo '<script>alert("Reservation status updated successfully. Book availability updated to Unavailable and BookReturnedStatus updated to Unreturned.");</script>';
            } else {
                // If there is an error, roll back the transaction
                $conn->rollback();
                echo '<script>alert("Error updating book availability and bookReturnedStatus: ' . $conn->error . '");</script>';
            }
        } else {
            // If the new status is not "Completed," only update the reservation status
            echo '<script>alert("Reservation status updated successfully.");</script>';
        }
    } else {
        // Error message
        echo '<script>alert("Error updating reservation status: ' . $conn->error . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Books</title>
    <link rel="stylesheet" href="css/style.css">
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

    <!-- Your page content and reservation details display -->
    <div class="content">
        <h2>Book Reservations</h2>

        <?php if (!empty($reservations)): ?>
            <table class="editbooks">
                <tr>
                    <th>Reservation ID</th>
                    <th>User ID</th>
                    <th>Book ID</th>
                    <th>Reservation Date</th>
                    <th>Pickup Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo $reservation['bookReservationID']; ?></td>
                        <td><?php echo $reservation['userID']; ?></td>
                        <td><?php echo $reservation['bookID']; ?></td>
                        <td><?php echo $reservation['bookReservationDate']; ?></td>
                        <td><?php echo $reservation['bookPickupDate']; ?></td>
                        <td><?php echo $reservation['bookReservationStatus']; ?></td>
                        <td>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="reservationID" value="<?php echo $reservation['bookReservationID']; ?>">
                                <input type="hidden" name="bookID" value="<?php echo $reservation['bookID']; ?>">
                                <select name="newStatus">
                                    <option value="Pending">Pending</option>
                                    <option value="Completed">Completed</option>
                                </select>
                                <input type="submit" name="updateStatus" value="Update Status">
                            </form>
                        </td>
                    </tr>
                    
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No reservations found.</p>
        <?php endif; ?>
        <div class="booksdeletebutton-container">               
            <button type="button" onclick="window.location.href='staffbooks.php'" class="booksdelete-button">Back to Book List</button>
        </div>
    </div>
</body>
</html>
