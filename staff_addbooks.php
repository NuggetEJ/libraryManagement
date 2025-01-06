<?php
session_start();
include 'config.php';

$staffEmail = $_SESSION['staffEmail'];

// Fetching staffID based on staffEmail
$sqlStaffID = "SELECT staffID FROM staff WHERE staffEmail = ?";
$stmtStaffID = $conn->prepare($sqlStaffID);
$stmtStaffID->bind_param("s", $staffEmail);
$stmtStaffID->execute();
$resultStaffID = $stmtStaffID->get_result();

if ($resultStaffID->num_rows > 0) {
    $rowStaffID = $resultStaffID->fetch_assoc();
    $staffID = $rowStaffID['staffID'];
} else {
    echo "Staff not found.";
    exit;
}

// Handle adding book
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookTitle = isset($_POST['bookTitle']) ? $_POST['bookTitle'] : '';
    $bookAuthor = isset($_POST['bookAuthor']) ? $_POST['bookAuthor'] : '';
    $bookCategory = isset($_POST['bookCategory']) ? $_POST['bookCategory'] : '';
    $bookISBN = isset($_POST['bookISBN']) ? $_POST['bookISBN'] : '';
    $bookShelf = isset($_POST['bookShelf']) ? $_POST['bookShelf'] : '';
    $bookDateBorrowed = isset($_POST['bookDateBorrowed']) ? $_POST['bookDateBorrowed'] : '';
    $bookDueDate = isset($_POST['bookDueDate']) ? $_POST['bookDueDate'] : '';
    $bookReturnStatus = isset($_POST['bookReturnStatus']) ? $_POST['bookReturnStatus'] : '';
    $bookAvailability = isset($_POST['bookAvailability']) ? $_POST['bookAvailability'] : '';

    if (empty($bookTitle) || empty($bookAuthor) || empty($bookCategory) || empty($bookISBN) || empty($bookShelf) || empty($bookDateBorrowed) ||
        empty($bookDueDate) || empty($bookReturnStatus) || empty($bookAvailability)) {
        echo '<script>alert("All fields are required!");</script>';
    } else {
        // Handling the file upload
        $bookPhoto = null; // Set default value

        if (isset($_FILES['bookPhoto']) && $_FILES['bookPhoto']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $unique_filename = uniqid() . '-' . basename($_FILES["bookPhoto"]["name"]);
            $target_file = $target_dir . $unique_filename;

            if (move_uploaded_file($_FILES["bookPhoto"]["tmp_name"], $target_file)) {
                $bookPhoto = $target_file; // Set the photo path only if uploaded
            } else {
                echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
            }
        }

        // Insert into the database
        $sql = "INSERT INTO books (staffID, bookTitle, bookPhoto, bookAuthor, bookCategory, bookISBN, bookShelf, bookDateBorrowed, bookDueDate, bookReturnStatus, bookAvailability) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $staffID, $bookTitle, $bookPhoto, $bookAuthor, $bookCategory, $bookISBN, $bookShelf, $bookDateBorrowed, $bookDueDate, $bookReturnStatus, $bookAvailability);

        try {
            $stmt->execute();
            echo '<script>alert("Book added successfully!");</script>';
            echo '<script>window.location.href = "staffbooks.php";</script>';
            exit;
        } catch (mysqli_sql_exception $e) {
            echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to the stylesheet -->
</head>
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

    <div class="content">
    <h2>Add Book</h2>
    <!-- Add Book Form -->
    <form action="staff_addbooks.php" method="post" enctype="multipart/form-data" class="add-book-form">
        Title: <input type="text" name="bookTitle" required><br>
        Author: <input type="text" name="bookAuthor" required><br>
        Category:
        <select name="bookCategory" required>
            <option value="Fiction">Fiction</option>
            <option value="Non-Fiction">Non-Fiction</option>
            <option value="Mystery">Mystery</option>
            <option value="Science Fiction">Science Fiction</option>
            <option value="Romance">Romance</option>
            <option value="Biography">Biography</option>
            <option value="History">History</option>
            <option value="Fantasy">Fantasy</option>
            <option value="Self-help">Self-help</option>
            <option value="Educational">Educational</option>
            <option value="Comic">Comic</option>
            <option value="Suspense/Thriller">Suspense/Thriller</option>
            <option value="Classic">Classic</option>
        </select><br>
        ISBN: <input type="text" name="bookISBN" required><br>
        Shelf: <input type="text" name="bookShelf" required><br>
        Date Borrowed: <input type="date" name="bookDateBorrowed" required><br>
        Due Date: <input type="date" name="bookDueDate" required><br>
        Return Status:
        <select name = "bookReturnStatus" required>
            <option value="Returned">Returned</option>
            <option value="Unreturned">Unreturned</option>
        </select><br>
        Availability:
        <select name = "bookAvailability" required>
            <option value="Available">Available</option>
            <option value="Unavailable">Unavailable</option>
        </select><br>
        Photo: <input type="file" name="bookPhoto" accept="image/*"><br>

        <div class="button-container">
    <input type="submit" value="Add Book" class="add-book-button">
    <button onclick="window.location.href='staffbooks.php'" class="back-to-list-button">Back to Book List</button>
</div>

    </form>
</div>
</body>
</html>
