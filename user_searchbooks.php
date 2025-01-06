<?php
session_start();
include 'config.php';

$staffEmail = $_SESSION['staffEmail'];
$search = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST["search"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <!-- Import CSS style -->
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

    <header>
        <h2 style="text-align: center;">Search Result: <?= $search ?></h2>
    </header>

    <div class="content">
        <div class="search-container">
            <form action="user_searchbooks.php" method="post">
                <input type="text" placeholder="Search.." name="search">
                <input type="submit" value="Search" id="search-button">
            </form>
        </div>

        <div class="center-container">
            <table class="userbooks">
                <thead>
                    <tr>
                        <th>No</th>
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
                </thead>
                <tbody>
                    <?php
                    if ($search != "") {
                        $search = $_POST["search"];

                        $keywords = explode(" ", $search);

                        $sql = "SELECT * FROM books WHERE (";

                        $conditions = [];
                        foreach ($keywords as $index => $keyword) {
                            $conditions[] = "bookTitle LIKE '%$keyword%' OR bookAuthor LIKE '%$keyword%' OR bookCategory LIKE '%$keyword%' OR bookISBN LIKE '%$keyword%' OR bookShelf LIKE '%$keyword%'";
                        }

                        $sql .= implode(" OR ", $conditions);

                        $sql .= ")";

                        $result = mysqli_query($conn, $sql);

                        if (!$result) {
                            die('Invalid query: ' . mysqli_error($conn));
                        }

                        function generateTableRows($result)
                        {
                            if (mysqli_num_rows($result) > 0) {
                                $numrow = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $numrow . "</td><td>" . $row["bookTitle"] . "</td><td>" . $row["bookAuthor"] . "</td><td>" . $row["bookCategory"] .
                                        "</td><td>" . $row["bookISBN"] . "</td><td>" . $row["bookShelf"] . "</td><td>" . $row["bookDateBorrowed"] . "</td><td>"
                                        . $row["bookDueDate"] . "</td><td>" . $row["bookReturnStatus"] . "</td><td>" . $row["bookAvailability"] . "</td><td>";
                                    echo '<img src="' . $row["bookPhoto"] . '" alt="' . $row["bookTitle"] . '" width="100" height="100">';
                                    echo "</td>";
                                    echo "<td>";
            if ($row['bookAvailability'] == 'Available') {
                echo '<a href="user_reservebooks.php?bookID=' . $row['bookID'] . '" class="reserve-btn">Reserve</a>';
            } else {
                echo '<p>This book is currently unavailable for reservation.</p>';
            }
            echo "</td>";
            
                            echo "</tr>" . "\n\t\t";
                            $numrow++;
                            }
                            } else {
                                echo '<tr><td colspan="11">0 results</td></tr>';
                            }
                        }

                        generateTableRows($result);

                        mysqli_close($conn);
                    } else {
                        echo "Search query is empty<br>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="button-container">
                <button type="button" onclick="window.location.href='userbooks.php'" class="crud-form">Back to Book List</button>
            </div>
            <p></p>
        </div>
    </div>

    <script>
        // For responsive sandwich menu
        function myFunction() {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") {
                x.className += " responsive";
            } else {
                x.className = "topnav";
            }
        }
    </script>
</body>

</html>
