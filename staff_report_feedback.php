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

// Fetch data based on selected month and year
$q1Query = "SELECT overall_rate, COUNT(*) as count FROM feedback WHERE MONTH(date) = $selectedMonth AND YEAR(date) = $selectedYear 
GROUP BY overall_rate ORDER BY count";
$q2Query = "SELECT userDisplay_rate, COUNT(*) as count FROM feedback WHERE MONTH(date) = $selectedMonth AND YEAR(date) = $selectedYear 
GROUP BY userDisplay_rate ORDER BY count";
$q3Query = "SELECT manageBook_rate, COUNT(*) as count FROM feedback WHERE MONTH(date) = $selectedMonth AND YEAR(date) = $selectedYear 
GROUP BY manageBook_rate ORDER BY count";
$q4Query = "SELECT manageComplaint_rate, COUNT(*) as count FROM feedback WHERE MONTH(date) = $selectedMonth AND YEAR(date) = $selectedYear 
GROUP BY manageComplaint_rate ORDER BY count";
$q5Query = "SELECT manageBooking_rate, COUNT(*) as count FROM feedback WHERE MONTH(date) = $selectedMonth AND YEAR(date) = $selectedYear 
GROUP BY manageBooking_rate ORDER BY count";
$q6Query = "SELECT manageEvent_rate, COUNT(*) as count FROM feedback WHERE MONTH(date) = $selectedMonth AND YEAR(date) = $selectedYear 
GROUP BY manageEvent_rate ORDER BY count";
$q7Query = "SELECT staff_rate, COUNT(*) as count FROM feedback WHERE MONTH(date) = $selectedMonth AND YEAR(date) = $selectedYear 
GROUP BY staff_rate ORDER BY count";
$q8Query = "SELECT recommendation_rate, COUNT(*) as count FROM feedback WHERE MONTH(date) = $selectedMonth AND YEAR(date) = $selectedYear 
GROUP BY recommendation_rate ORDER BY count";

// Execute queries and fetch results
$q1Result = $conn->query($q1Query);
$q2Result = $conn->query($q2Query);
$q3Result = $conn->query($q3Query);
$q4Result = $conn->query($q4Query);
$q5Result = $conn->query($q5Query);
$q6Result = $conn->query($q6Query);
$q7Result = $conn->query($q7Query);
$q8Result = $conn->query($q8Query);

// Check for query execution success
if ($q1Result && $q2Result && $q3Result && $q4Result && $q5Result && $q6Result && $q7Result && $q8Result) {
    $q1Data = $q2Data = $q3Data = $q4Data = $q5Data = $q6Data = $q7Data = $q8Data = [];
    while ($row = $q1Result->fetch_assoc()) {
        $q1Data[] = [$row['overall_rate'], (int)$row['count']];
    }
    while ($row = $q2Result->fetch_assoc()) {
        $q2Data[] = [$row['userDisplay_rate'], (int)$row['count']];
    }
    while ($row = $q3Result->fetch_assoc()) {
        $q3Data[] = [$row['manageBook_rate'], (int)$row['count']];
    }
    while ($row = $q4Result->fetch_assoc()) {
        $q4Data[] = [$row['manageComplaint_rate'], (int)$row['count']];
    }
    while ($row = $q5Result->fetch_assoc()) {
        $q5Data[] = [$row['manageBooking_rate'], (int)$row['count']];
    }
    while ($row = $q6Result->fetch_assoc()) {
        $q6Data[] = [$row['manageEvent_rate'], (int)$row['count']];
    }
    while ($row = $q7Result->fetch_assoc()) {
        $q7Data[] = [$row['staff_rate'], (int)$row['count']];
    }
    while ($row = $q8Result->fetch_assoc()) {
        $q8Data[] = [$row['recommendation_rate'], (int)$row['count']];
    }
} else {
    $q1Data = $q2Data = $q3Data = $q4Data = $q5Data = $q6Data = $q7Data = $q8Data = [];
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
        <h2>User Feedback Report Analysis</h2>

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
        </form><br>

        <!-- Display feedback status chart -->
        <?php
        if ($q1Data == [] && $q2Data == [] && $q3Data == [] && $q4Data == [] && $q5Data == [] && $q6Data == [] && $q7Data == [] && $q8Data == []
        ){
            echo "<p>No results for the selected month and year.</p>";
        } else {
        ?>
            <!-- Display q1 chart -->
            <div id="q1Chart" style="width:100%; height:200px;"></div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawQ1Chart);

                function drawQ1Chart() {
                    // Set Data dynamically
                    const q1Data = google.visualization.arrayToDataTable([
                        ['Question_1', 'Count'],
                        <?php
                        foreach ($q1Data as $q1) {
                            echo "['{$q1[0]}', {$q1[1]}],";
                        }
                        ?>
                    ]);

                    // Set Options
                    const q1Options = {
                        title: 'Question 1: Overall Rating',
                        is3D: true
                    };

                    // Draw
                    const q1Chart = new google.visualization.PieChart(document.getElementById('q1Chart'));
                    q1Chart.draw(q1Data, q1Options);
                }
            </script>

            <!-- Display q2 chart -->
            <div id="q2Chart" style="width:100%; height:200px;"></div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawQ2Chart);

                function drawQ2Chart() {
                    // Set Data dynamically
                    const q2Data = google.visualization.arrayToDataTable([
                        ['Question_2', 'Count'],
                        <?php
                        foreach ($q2Data as $q2) {
                            echo "['{$q2[0]}', {$q2[1]}],";
                        }
                        ?>
                    ]);

                    // Set Options
                    const q2Options = {
                        title: 'Question 2: User Display Rating',
                        is3D: true
                    };

                    // Draw
                    const q2Chart = new google.visualization.PieChart(document.getElementById('q2Chart'));
                    q2Chart.draw(q2Data, q2Options);
                }
            </script>

            <!-- Display q3 chart -->
            <div id="q3Chart" style="width:100%; height:200px;"></div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawQ3Chart);

                function drawQ3Chart() {
                    // Set Data dynamically
                    const q3Data = google.visualization.arrayToDataTable([
                        ['Question_3', 'Count'],
                        <?php
                        foreach ($q3Data as $q3) {
                            echo "['{$q3[0]}', {$q3[1]}],";
                        }
                        ?>
                    ]);

                    // Set Options
                    const q3Options = {
                        title: 'Question 3: Books Management Rating',
                        is3D: true
                    };

                    // Draw
                    const q3Chart = new google.visualization.PieChart(document.getElementById('q3Chart'));
                    q3Chart.draw(q3Data, q3Options);
                }
            </script>

            <!-- Display q4 chart -->
            <div id="q4Chart" style="width:100%; height:200px;"></div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawQ4Chart);

                function drawQ4Chart() {
                    // Set Data dynamically
                    const q4Data = google.visualization.arrayToDataTable([
                        ['Question_4', 'Count'],
                        <?php
                        foreach ($q4Data as $q4) {
                            echo "['{$q4[0]}', {$q4[1]}],";
                        }
                        ?>
                    ]);

                    // Set Options
                    const q4Options = {
                        title: 'Question 4: Complaint Management Rating',
                        is3D: true
                    };

                    // Draw
                    const q4Chart = new google.visualization.PieChart(document.getElementById('q4Chart'));
                    q4Chart.draw(q4Data, q4Options);
                }
            </script>

            <!-- Display q5 chart -->
            <div id="q5Chart" style="width:100%; height:200px;"></div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawQ5Chart);

                function drawQ5Chart() {
                    // Set Data dynamically
                    const q5Data = google.visualization.arrayToDataTable([
                        ['Question_5', 'Count'],
                        <?php
                        foreach ($q5Data as $q5) {
                            echo "['{$q5[0]}', {$q5[1]}],";
                        }
                        ?>
                    ]);

                    // Set Options
                    const q5Options = {
                        title: 'Question 5: Booking Management Rating',
                        is3D: true
                    };

                    // Draw
                    const q5Chart = new google.visualization.PieChart(document.getElementById('q5Chart'));
                    q5Chart.draw(q5Data, q5Options);
                }
            </script>

            <!-- Display q6 chart -->
            <div id="q6Chart" style="width:100%; height:200px;"></div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawQ6Chart);

                function drawQ6Chart() {
                    // Set Data dynamically
                    const q6Data = google.visualization.arrayToDataTable([
                        ['Question_6', 'Count'],
                        <?php
                        foreach ($q6Data as $q6) {
                            echo "['{$q6[0]}', {$q6[1]}],";
                        }
                        ?>
                    ]);

                    // Set Options
                    const q6Options = {
                        title: 'Question 6: Event Management Rating',
                        is3D: true
                    };

                    // Draw
                    const q6Chart = new google.visualization.PieChart(document.getElementById('q6Chart'));
                    q6Chart.draw(q6Data, q6Options);
                }
            </script>

            <!-- Display q7 chart -->
            <div id="q7Chart" style="width:100%; height:200px;"></div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawQ7Chart);

                function drawQ7Chart() {
                    // Set Data dynamically
                    const q7Data = google.visualization.arrayToDataTable([
                        ['Question_7', 'Count'],
                        <?php
                        foreach ($q7Data as $q7) {
                            echo "['{$q7[0]}', {$q7[1]}],";
                        }
                        ?>
                    ]);

                    // Set Options
                    const q7Options = {
                        title: 'Question 7: Library Staff Rating',
                        is3D: true
                    };

                    // Draw
                    const q7Chart = new google.visualization.PieChart(document.getElementById('q7Chart'));
                    q7Chart.draw(q7Data, q7Options);
                }
            </script>

            <!-- Display q8 chart -->
            <div id="q8Chart" style="width:100%; height:200px;"></div>
            <script>
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawQ8Chart);

                function drawQ8Chart() {
                    // Set Data dynamically
                    const q8Data = google.visualization.arrayToDataTable([
                        ['Question_8', 'Count'],
                        <?php
                        foreach ($q8Data as $q8) {
                            echo "['{$q8[0]}', {$q8[1]}],";
                        }
                        ?>
                    ]);

                    // Set Options
                    const q8Options = {
                        title: 'Question 8: Recommend to Other People',
                        is3D: true
                    };

                    // Draw
                    const q8Chart = new google.visualization.PieChart(document.getElementById('q8Chart'));
                    q8Chart.draw(q8Data, q8Options);
                }
            </script>

            <!-- Display suggestion chart as Bar chart -->
            <div style="text-align: right; padding:10px;">
            <table id="staff_feedback">
            <tr>
                <th>No</th>
                <th>Suggestions</th>
                <th>Date</th>
            </tr>
            <?php
                $sql = "SELECT suggestion, date FROM feedback WHERE MONTH(date) = $selectedMonth AND YEAR(date) = $selectedYear";
                $result = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($result) > 0) {
                // output data of each row
                    $numrow=1;
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $numrow . "</td><td>". $row["suggestion"] . "</td><td>" . $row["date"]. "</td>";
                        echo "</tr>";
                        $numrow++;
                    }
                } 
                else {
                    echo '<tr><td colspan="33">0 results</td></tr>';
                } 
            mysqli_close($conn); 
            ?>
        </table>
        <?php
        }
        ?>
    </div>
</body>
</html>
