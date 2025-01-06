<?php
session_start();
include 'config.php';

// Fetch all books
function fetchBooks($conn) {
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    return $books;
}

$books = fetchBooks($conn);

if (empty($books)) {
    echo '<script>alert("No books found!");</script>';
    echo '<script>window.location.href = "staffbooks.php";</script>';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['books'] as $bookID => $values) {
        $bookTitle = $values['bookTitle'];
        $bookAuthor = $values['bookAuthor'];
        $bookCategory = $values['bookCategory'];
        $bookISBN = $values['bookISBN'];
        $bookShelf = $values['bookShelf'];
        $bookDateBorrowed = $values['bookDateBorrowed'];
        $bookDueDate = $values['bookDueDate'];
        $bookReturnStatus = $values['bookReturnStatus'];
        $bookAvailability = ($bookReturnStatus == 'Returned') ? 'Available' : 'Unavailable';

$target_file = null;
$currentPhoto = $values['currentPhoto'];

if (isset($_FILES['books']['tmp_name'][$bookID]['bookPhoto']) && $_FILES['books']['error'][$bookID]['bookPhoto'] == UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    $file_extension = pathinfo($_FILES["books"]["name"][$bookID]["bookPhoto"], PATHINFO_EXTENSION);
    $unique_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $unique_filename;

    if (move_uploaded_file($_FILES["books"]["tmp_name"][$bookID]["bookPhoto"], $target_file)) {
        if (!empty($currentPhoto) && file_exists($currentPhoto)) {
            unlink($currentPhoto);
        }
    } else {
        echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
    }
} else {
    $target_file = $currentPhoto;
}
        $sql = "UPDATE books SET bookTitle = ?, bookAuthor = ?, bookCategory = ?, bookISBN = ?, bookShelf = ?, bookDateBorrowed = ?, bookDueDate = ?, bookReturnStatus = ?, bookAvailability = ?, bookPhoto = ? WHERE bookID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssi", $bookTitle, $bookAuthor, $bookCategory, $bookISBN, $bookShelf, $bookDateBorrowed, $bookDueDate, $bookReturnStatus, $bookAvailability, $target_file, $bookID);
        $stmt->execute();
    }
    echo '<script>alert("Changes saved successfully!");</script>';
    echo '<script>window.location.href = "staffbooks.php";</script>';
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Books</title>
    <link rel="stylesheet" href="css/style.css">

    <script>
    function displayImage(input, currentPhoto) {
        const fileInput = input;
        const img = input.previousElementSibling;

        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            img.src = e.target.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            img.src = currentPhoto;
        }
    }
</script>

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

    <div class="content">
        <h2>Edit Books</h2>

        <form action="staff_editbooks.php" method="post" enctype="multipart/form-data">
            <table class="editbooks">
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
                        <td><input type="text" name="books[<?php echo $book['bookID']; ?>][bookTitle]" value="<?php echo $book['bookTitle']; ?>" required></td>
                        <td><input type="text" name="books[<?php echo $book['bookID']; ?>][bookAuthor]" value="<?php echo $book['bookAuthor']; ?>" required></td>
                        <td>
                            <select name="books[<?php echo $book['bookID']; ?>][bookCategory]" required>
                            <option value="Fiction" <?php if ($book['bookCategory'] == 'Fiction') echo 'selected'; ?>>Fiction</option>
                                <option value="Non-Fiction" <?php if ($book['bookCategory'] == 'Non-Fiction') echo 'selected'; ?>>Non-Fiction</option>
                                <option value="Mystery" <?php if ($book['bookCategory'] == 'Mystery') echo 'selected'; ?>>Mystery</option>
                                <option value="Science Fiction" <?php if ($book['bookCategory'] == 'Science Fiction') echo 'selected'; ?>>Science Fiction</option>
                                <option value="Romance" <?php if ($book['bookCategory'] == 'Romance') echo 'selected'; ?>>Romance</option>
                                <option value="Biography" <?php if ($book['bookCategory'] == 'Biography') echo 'selected'; ?>>Biography</option>
                                <option value="History" <?php if ($book['bookCategory'] == 'History') echo 'selected'; ?>>History</option>
                                <option value="Fantasy" <?php if ($book['bookCategory'] == 'Fantasy') echo 'selected'; ?>>Fantasy</option>
                                <option value="Self-help" <?php if ($book['bookCategory'] == 'Self-help') echo 'selected'; ?>>Self-help</option>
                                <option value="Educational" <?php if ($book['bookCategory'] == 'Educational') echo 'selected'; ?>>Educational</option>
                                <option value="Comic" <?php if ($book['bookCategory'] == 'Comic') echo 'selected'; ?>>Comic</option>
                                <option value="Suspense/Thriller" <?php if ($book['bookCategory'] == 'Suspense/Thriller') echo 'selected'; ?>>Suspense/Thriller</option>
                                <option value="Classic" <?php if ($book['bookCategory'] == 'Classic') echo 'selected'; ?>>Classic</option>
                            </select>
                        </td>
                        <td><input type="text" name="books[<?php echo $book['bookID']; ?>][bookISBN]" value="<?php echo $book['bookISBN']; ?>" required></td>
                        <td><input type="text" name="books[<?php echo $book['bookID']; ?>][bookShelf]" value="<?php echo $book['bookShelf']; ?>" required></td>
                        <td><input type="date" name="books[<?php echo $book['bookID']; ?>][bookDateBorrowed]" value="<?php echo $book['bookDateBorrowed']; ?>" required></td>
                        <td><input type="date" name="books[<?php echo $book['bookID']; ?>][bookDueDate]" value="<?php echo $book['bookDueDate']; ?>" required></td>
                        <td>
                            <select name="books[<?php echo $book['bookID']; ?>][bookReturnStatus]" required>
                            <option value="Returned" <?php if ($book['bookReturnStatus'] == 'Returned') echo 'selected'; ?>>Returned</option>
                                <option value="Unreturned" <?php if ($book['bookReturnStatus'] == 'Unreturned') echo 'selected'; ?>>Unreturned</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" value="<?php echo ($book['bookReturnStatus'] == 'Returned') ? 'Available' : 'Unavailable'; ?>" readonly>
                        </td>
                        <td>
    <?php if (!empty($book['bookPhoto'])): ?>
        <img src="<?php echo $book['bookPhoto']; ?>" alt="Book Photo" style="max-width: 100px; max-height: 100px;">
    <?php endif; ?>
    <input type="file" name="books[<?php echo $book['bookID']; ?>][bookPhoto]" accept="image/*">
    <input type="hidden" name="books[<?php echo $book['bookID']; ?>][currentPhoto]" value="<?php echo $book['bookPhoto']; ?>">
</td>

                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <div class="booksdeletebutton-container">               
                <input type="submit" value="Save Changes" class="booksdelete-button">
                <button type="button" onclick="window.location.href='staffbooks.php'" class="booksdelete-button">Back to Book List</button>
            </div>
        </form>
    </div>
</body>
</html>
