<?php
session_start();
include 'config.php';

$userEmail = $_SESSION['userEmail'];

$sql = "SELECT userID FROM users WHERE userEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userID = $row['userID'];
} else {
    echo "User not found.";
    exit;
}

// Check if there are filtered books in the session
$filteredBooks = isset($_SESSION['filtered_books']) ? $_SESSION['filtered_books'] : null;

// If there are no filtered books, fetch all books from the database
if (!$filteredBooks) {
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);

    $books = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
}

unset($_SESSION['filtered_books']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Library Management System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,  initial-scale=1.0">

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
        <h2>Book List</h2>

        <?php 
        echo '<a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>';	
        ?>
    </header>

        <div class="content">
        <h2>List of Books</h2>
        <div class="search-container">
            <form action="user_searchbooks.php" method="post">
                <input type="text" placeholder="Search.." name="search">
                <input type="submit" value="Search" id="search-button">
            </form>
            <div class="gap"></div>
            <div class="booksbutton-container">
            <a href="user_filterbooks.php" class="booksbutton">Filter Book by Category</a>
            </div>

        <div class="center-container">
            <!-- Display the Book List -->
            <?php if (!empty($filteredBooks)): ?>
                <p>Filtered Books:</p>
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
                        <th>Actions</th> 
                    </tr>
                    
                    <?php foreach ($filteredBooks as $book): ?>
                        <tr>
                            <td><?php echo $book['bookTitle']; ?></td>
                            <td><?php echo $book['bookAuthor']; ?></td>
                            <td><?php echo $book['bookCategory']; ?></td>
                            <td><?php echo $book['bookISBN']; ?></td>
                            <td><?php echo $book['bookShelf']; ?></td>
                            <td><?php echo $book['bookDateBorrowed']; ?></td>
                            <td><?php echo $book['bookDueDate']; ?></td>
                            <td><?php echo $book['bookReturnStatus']; ?></td>
                            <td><?php echo $book['bookAvailability']; ?></td>
                            <td><img src="<?php echo $book['bookPhoto']; ?>" alt="Book Photo" width="100", height="100"></td>
                            <td>
                                <?php
                                if ($book['bookAvailability'] == 'Available') {
                                    echo '<a href="user_reservebooks.php?bookID=' . $book['bookID'] . '" class="reserve-btn">Reserve</a>';
                                } else {
                                    echo '<p>This book is currently unavailable for reservation.</p>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php elseif (!empty($books)): ?>
                <p>All Books:</p>
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
                        <th>Actions</th> 
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
                            <td><?php echo $book['bookAvailability']; ?></td>
                            <td><img src="<?php echo $book['bookPhoto']; ?>" alt="Book Photo" width="100", height="100"></td>
                            <td>
                                <?php
                                if ($book['bookAvailability'] == 'Available') {
                                    echo '<a href="user_reservebooks.php?bookID=' . $book['bookID'] . '" class="reserve-btn">Reserve</a>';
                                } else {
                                    echo '<p>This book is currently unavailable for reservation.</p>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No books found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
