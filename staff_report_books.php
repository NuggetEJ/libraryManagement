<?php
session_start(); // Start the session
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

// Default values for month and year
$selectedMonth = isset($_POST['month']) ? $conn->real_escape_string($_POST['month']) : date('m');
$selectedYear = isset($_POST['year']) ? $conn->real_escape_string($_POST['year']) : date('Y');

// Fetch data from the database based on selected month and year
$popularCategoryQuery = "SELECT bookAvailability, COUNT(*) as count FROM books WHERE MONTH(bookDueDate) = $selectedMonth 
AND YEAR(bookDueDate) = $selectedYear GROUP BY bookAvailability ORDER BY count";

$statusQuery = "SELECT bookReturnStatus, COUNT(*) as count FROM books WHERE MONTH(bookDueDate) = $selectedMonth 
AND YEAR(bookDueDate) = $selectedYear GROUP BY bookReturnStatus ORDER BY count";

// Execute queries and fetch results
$popularCategoryResult = $conn->query($popularCategoryQuery);
$statusResult = $conn->query($statusQuery);

// Check for query execution success
if ($popularCategoryResult && $statusResult) {
    $categoryData = [];
    while ($row = $popularCategoryResult->fetch_assoc()) {
        $categoryData[] = [$row['bookAvailability'], (int)$row['count']];
    }
    $statusData = [];
    while ($row = $statusResult->fetch_assoc()) {
        $statusData[] = [$row['bookReturnStatus'], (int)$row['count']];
    }
} else {
    $categoryData = [];
    $statusData = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Library Management System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,  initial-scale=1.0">

	<!-- import css style -->
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
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

	<!-- header at right side -->
    <header>
		<!-- title your page -->
        <h2>Book Report Analysis</h2>

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

    <!-- masukkan body page kmu disini -->
    <div class="content">
        <br><form method="post" action="">
            <label for="month">Select Month:</label>
            <select id="month" name="month">
                <?php
                for ($i = 1; $i <= 12; $i++) {
                    $selected = ($selectedMonth == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>" . date('F', mktime(0, 0, 0, $i, 1)) . "</option>";
                }
                ?>
            </select>

            <label for="year">Select Year:</label>
            <select id="year" name="year">
                <?php
                for ($i = date('Y'); $i >= 2010; $i--) {
                    $selected = ($selectedYear == $i) ? 'selected' : '';
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
            <input type="submit" value="Submit">
        </form>

        
        <?php
        if ($categoryData == [] && $statusData == []) {
            echo "<p>No results for the selected month and year.</p>";
        } else {
        ?>

        <!-- Display return status chart -->
        <div id="statusChart" style="width:100%; height:250px;"></div>
        <script>
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawStatusChart);

            function drawStatusChart() {
                // Set Data dynamically
                const statusData = google.visualization.arrayToDataTable([
                    ['Status', 'Count'],
                    <?php
                    foreach ($statusData as $status) {
                        echo "['{$status[0]}', {$status[1]}],";
                    }
                    ?>
                ]);

                // Set Options
                const statusOptions = {
                    title: 'Books Return Status by Month',
                    is3D: true
                };

                // Draw
                const statusChart = new google.visualization.PieChart(document.getElementById('statusChart'));
                statusChart.draw(statusData, statusOptions);
            }
        </script>

        <!-- Display popular category chart as Bar chart -->
        <div id="categoryChart" style="width:100%; height:250px;"></div>
        <script>
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawCategoryChart);

            function drawCategoryChart() {
                // Set Data dynamically
                const categoryData = google.visualization.arrayToDataTable([
                    ['Category', 'Count'],
                    <?php
                    foreach ($categoryData as $category) {
                        echo "['{$category[0]}', {$category[1]}],";
                    }
                    ?>
                ]);

                // Set Options
                const categoryOptions = {
                    title: 'How Many Available Books in Month',
                    chartArea: {width: '50%'},
                    hAxis: {
                        title: 'Count',
                        minValue: 0,
                    },
                    vAxis: {
                        title: 'Available Books',
                    }
                };

                // Draw
                const categoryChart = new google.visualization.BarChart(document.getElementById('categoryChart'));
                categoryChart.draw(categoryData, categoryOptions);
            }
        </script>
        <?php
        }
        ?>
    </div>
</body>
</html>