<?php
session_start();
include 'config.php';

$staffEmail = $_SESSION['staffEmail'];

function fetchBooks($conn) {
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    return $books;
}


$books = fetchBooks($conn, $staffEmail);

if (empty($books)) {
    echo '<script>alert("No books found!");</script>';
    echo '<script>window.location.href = "staffbooks.php";</script>';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteSelected'])) {
    if (!empty($_POST['selectedBooks'])) {
        foreach ($_POST['selectedBooks'] as $bookID) {
            $deleteSql = "DELETE FROM books WHERE bookID = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $bookID);
            $deleteStmt->execute();
        }
        echo "<script>alert('Books deleted successfully!');</script>";
        $bookID = fetchBooks($conn, $staffID);
    } else {
        echo "<script>alert('Please select at least one book to delete.');</script>";
    }
      // Redirect regardless of the deletion result
      echo '<script>window.location.href="staffbooks.php";</script>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Books</title>
    <link rel="stylesheet" href="css/style.css">
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
        <h2>Delete Books</h2>

        <form action="staff_deletebooks.php" method="post" class="center-container">
        <table class="userbooks">
            <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>ISBN</th>
                    <th>Shelf</th>
                    <th>Date Borrowed</th>
                    <th>Due Date</th>
                    <th>Return Status</th>
                    <th>Availability</th>
                    <th>Photo</th>
                </tr>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><input type="checkbox" name="selectedBooks[]" value="<?php echo $book['bookID']; ?>"></td>
                        <td><?php echo $book['bookTitle']; ?></td>
                        <td><?php echo $book['bookAuthor']; ?></td>
                        <td><?php echo $book['bookCategory']; ?></td>
                        <td><?php echo $book['bookISBN']; ?></td>
                        <td><?php echo $book['bookShelf']; ?></td>
                        <td><?php echo $book['bookDateBorrowed']; ?></td>
                        <td><?php echo $book['bookDueDate']; ?></td>
                        <td><?php echo $book['bookAvailability']; ?></td>
                        <td><img src="<?php echo $book['bookPhoto']; ?>" alt="Book" width="50"></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <div class="booksdeletebutton-container">               
                <button type="submit" name="deleteSelected" class="booksdelete-button">Delete</button>
                <button type="button" onclick="window.location.href='staffbooks.php'" class="booksdelete-button">Back to Book List</button>
            </div>
        </form>
    </div>
</body>
</html>


