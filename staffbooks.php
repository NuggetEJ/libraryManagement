<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("config.php");

// If staff is not logged in, redirect to login page
if (!isset($_SESSION['staffEmail'])) {
    header("Location: login_staff.php");
    exit;
}

// Fetch existing books for the staff
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

$books = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Management System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    <header>
        <h2>Book List</h2>
        <?php 
        echo '<a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>';	
        ?>
    </header>
    <div class="content">
        <h2>List of Books</h2>
        <div class="search-container">
            <form action="staff_searchbooks.php" method="post">
                <input type="text" placeholder="Search.." name="search">
                <input type="submit" value="Search" id="search-button">
            </form>
            <div class="gap"></div>
            <div class="booksbutton-container">
                <a href="staff_reservebooks.php" class="booksbutton">View Reservations</a>
                <a href="staff_addbooks.php" class="booksbutton">Add Books</a>
                <a href="staff_editbooks.php" class="booksbutton">Edit Books</a>
                <a href="staff_deletebooks.php" class="booksbutton">Delete Books</a>
            </div>
            <div class="center-container">
                <?php if (!empty($books)): ?>
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
                                <td><?php echo $book['bookTitle']; ?></td>
                                <td><?php echo $book['bookAuthor']; ?></td>
                                <td><?php echo $book['bookCategory']; ?></td>
                                <td><?php echo $book['bookISBN']; ?></td>
                                <td><?php echo $book['bookShelf']; ?></td>
                                <td><?php echo $book['bookDateBorrowed']; ?></td>
                                <td><?php echo $book['bookDueDate']; ?></td>
                                <td><?php echo $book['bookReturnStatus']; ?></td>
                                <td>
                                    <?php 
                                    // Check if the book is returned
                                    if ($book['bookReturnStatus'] == 'Returned') {
                                        echo $book['bookAvailability'];
                                    } else {
                                        echo 'Unavailable';
                                    }
                                    ?>
                                </td>
                                <td><img src="<?php echo $book['bookPhoto']; ?>" alt="Book Photo" width="100", height="100"></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No books found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
