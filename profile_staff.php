<?php
session_start();

// If staff is not logged in, redirect to login page
if (!isset($_SESSION['staffEmail'])) {
    header("Location: login_staff.php");
    exit;
}

include 'config.php';

$staffEmail = $_SESSION['staffEmail'];

$sql = "SELECT * FROM staff WHERE staffEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $staffEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $staffName = $row['staffName'];
    $staffID = $row['staffID'];
    $staffEmail = $row['staffEmail'];
    $staffPhone = $row['staffPhone'];
    $staffAdd = $row['staffAdd'];
    $staffState = $row['staffState'];
    $staffDob = $row['staffDob'];
    $staffGender = $row['staffGender'];
    $staffIC = $row['staffIC'];
    $staffPhoto = $row['staffPhoto'];
} else {
    echo "No data found.";
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
        <h2>Staff Profile</h2>
        <?php
        // Check if the user is not logged in, redirect to login page
        if (!isset($_SESSION["staffEmail"])) {
            header("Location: index.php");
            exit();
        } else {
            echo '<a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>';
        }
        ?>
    </header>
    <div class="content">
        <!-- Staff Information Table -->
        <br>
        <?php if (!empty($staffPhoto)) : ?>
            <img src="<?php echo $staffPhoto; ?>" alt="Staff Photo" class="staff-img" width="200" height="200">
        <?php else : ?>
            <p>No photo available</p>
        <?php endif; ?>
        <table class="complaint-table">
        <tr>
            <td><strong>Name:</strong></td>
            <td><?php echo $staffName; ?></td>
        </tr>
        <tr>
            <td><strong>Staff ID:</strong></td>
            <td><?php echo $staffID; ?></td>
        </tr>
        <tr>
            <td><strong>Staff IC:</strong></td>
            <td><?php echo $staffIC; ?></td>
        </tr>
        <tr>
            <td><strong>Date of Birth:</strong></td>
            <td><?php echo $staffDob; ?></td>
        </tr>
        <tr>
            <td><strong>Gender:</strong></td>
            <td><?php echo $staffGender; ?></td>
        </tr>
        <tr>
            <td><strong>Phone:</strong></td>
            <td><?php echo $staffPhone; ?></td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td><?php echo $staffEmail; ?></td>
        </tr>
        <tr>
            <td><strong>Home Address:</strong></td>
            <td><?php echo $staffAdd; ?></td>
        </tr>
        <tr>
            <td><strong>State:</strong></td>
            <td><?php echo $staffState; ?></td>
        </tr>       
     </table>

        <!-- Edit button -->
        <form action="editinfo_staff.php" method="get" class="edit-complaint-form">
            <input type="hidden" name="staffEmail" value="<?php echo $staffEmail; ?>">
            <input type="submit" value="Edit">
        </form><br>
    </div>
</body>

</html>
