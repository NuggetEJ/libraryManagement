<?php
session_start();
include 'config.php';

// Fetch all distinct categories from the books table
$sqlCategories = "SELECT DISTINCT bookCategory FROM books";
$resultCategories = $conn->query($sqlCategories);

$categories = [];

if ($resultCategories->num_rows > 0) {
    while ($row = $resultCategories->fetch_assoc()) {
        $categories[] = $row['bookCategory'];
    }
}

$books = [];

// Check if the form is submitted for filtering
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter'])) {
    // Fetch filter options
    $categoryFilter = isset($_POST['category']) ? $_POST['category'] : '%';

    // Prepare SQL query with dynamic conditions based on filters
    $sql = "SELECT * FROM books WHERE bookCategory LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $categoryFilter);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Filtered Book List</title>
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
        <h2>Filtered Book List</h2>
        <?php 
        echo '<a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>';	
        ?>
    </header>

    <div class="content">
        <h2>Filtered Book List</h2>

        <!-- Filter Form -->
        <form action="user_filterbooks.php" method="post" class="filter-form">
            <label for="category">Select Category:</label>
            <select name="category" id="category">
                <option value="%">All Categories</option>
                <?php
                foreach ($categories as $category) {
                    echo '<option value="' . $category . '">' . $category . '</option>';
                }
                ?>
            </select>

            <input type="submit" name="filter" value="Filter">
        </form>

        <div class="center-container">
        <!-- Display the Book List -->
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
                    <th>Actions</th> <!-- New column for Reserve button -->
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
                                echo '<a href="reservebooks.php?bookID=' . $book['bookID'] . '" class="reserve-btn">Reserve</a>';
                            } else {
                                echo '<p>This book is currently unavailable for reservation.</p>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No books found based on the selected category.</p>
        <?php endif; ?>
    </div>
    <div class="booksdeletebutton-container">               
    <button type="button" onclick="window.location.href='userbooks.php'" class="booksdelete-button">Back to Book List</button>
    </div>
</body>
</html>
