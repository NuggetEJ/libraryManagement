<?php
session_start();
include 'config.php';

if (!isset($_SESSION['userEmail'])) {
    header("Location: login_user.php");
    exit();
}

if (!isset($_GET['bookID'])) {
    echo "Invalid request. Please go back and try again.";
    exit();
}

$bookID = $_GET['bookID'];

// Check if the selected book is available and returned
$sqlCheckAvailability = "SELECT * FROM books WHERE bookID = ? AND bookAvailability = 'Available' AND bookReturnStatus = 'Returned'";
$stmtCheckAvailability = $conn->prepare($sqlCheckAvailability);
$stmtCheckAvailability->bind_param("i", $bookID);
$stmtCheckAvailability->execute();
$resultCheckAvailability = $stmtCheckAvailability->get_result();

if ($resultCheckAvailability->num_rows > 0) {
    // Book is available and returned, proceed with reservation

    // Handle form submission for reservation
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Process the form data (pickup date and time)
        $pickupDate = $_POST['pickupDate'];
        $pickupTime = $_POST['pickupTime'];

        // Basic implementation of generateReservationID function
        function generateReservationID() {
            return uniqid('reservation_', true);
        }

        function fetchUserID($conn, $userEmail) {
            $sql = "SELECT userID FROM users WHERE userEmail = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $userEmail);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['userID'];
            } else {
                return null;
            }
        }

        // Update book availability status
        $updateSql = "UPDATE books SET bookAvailability = 'Reserved' WHERE bookID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $bookID);

        if ($updateStmt->execute()) {
            // Fetch userID based on userEmail
            $userEmail = $_SESSION['userEmail'];
            $userID = fetchUserID($conn, $userEmail);

            if ($userID !== null) { // Ensure a valid user ID is retrieved
                // Insert reservation into book_reservations table
                $reservationID = generateReservationID();
                $reservationSql = "INSERT INTO book_reservations (userID, bookID, bookReservationDate, bookPickupDate, bookReservationStatus) VALUES (?, ?, CURRENT_DATE, ?, 'Pending')";
                $reservationStmt = $conn->prepare($reservationSql);
                $reservationStmt->bind_param("iis", $userID, $bookID, $pickupDate);

                if ($reservationStmt->execute()) {
                    // Display reservation details
                    echo "Reservation ID: $reservationID<br>";
                    echo "Pickup Instructions: Please pick up the book on $pickupDate at $pickupTime.<br>";
                    exit();
                } else {
                    echo "Error inserting reservation: " . $conn->error;
                }
            } else {
                echo "Error fetching user ID.";
            }
        } else {
            echo "Error updating book availability: " . $conn->error;
        }
    }

    // Fetch book details for display
    $sqlBookDetails = "SELECT * FROM books WHERE bookID = ?";
    $stmtBookDetails = $conn->prepare($sqlBookDetails);
    $stmtBookDetails->bind_param("i", $bookID);
    $stmtBookDetails->execute();
    $resultBookDetails = $stmtBookDetails->get_result();

    if ($resultBookDetails->num_rows > 0) {
        $book = $resultBookDetails->fetch_assoc();
    } else {
        echo "Book not found.";
        exit();
    }
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Library Management System - Reserve Book</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
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

    <div class="content">
            <h2>Reserve Book - <?php echo $book['bookTitle']; ?></h2>

            <!-- Display Book Information -->
            <p><strong>Title:</strong> <?php echo $book['bookTitle']; ?></p>
            <p><strong>Author:</strong> <?php echo $book['bookAuthor']; ?></p>
            <p><strong>Category:</strong> <?php echo $book['bookCategory']; ?></p>

            <!-- Reservation Form -->
            <form action="user_reservebooks.php?bookID=<?php echo $bookID; ?>" method="post" class="complaint-form">
                <label for="pickupDate">Select Pickup Date:</label>
                <input type="date" id="pickupDate" name="pickupDate" required>
                <br></br>
                <label for="pickupTime">Select Pickup Time:</label>
                <input type="time" id="pickupTime" name="pickupTime" required>
                <br></br>
                <input type="submit" value="Reserve Book" class="submit-complaint-btn">
            </form>
            <div class="booksdeletebutton-container">               
                <button type="button" onclick="window.location.href='userbooks.php'" class="booksdelete-button">Back to Book List</button>
            </div>
        </div>
    </body>
    </html>

<?php
} else {
    echo "Selected book is not available for reservation.";
}
?>
</div>