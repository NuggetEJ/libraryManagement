<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $overall_rate = $_POST['overall_rate'];
    $userDisplay_rate = $_POST['userDisplay_rate'];
    $manageBook_rate = $_POST['manageBook_rate'];
    $manageComplaint_rate = $_POST['manageComplaint_rate'];
    $manageBooking_rate = $_POST['manageBooking_rate'];
    $manageEvent_rate = $_POST['manageEvent_rate'];
    $staff_rate = $_POST['staff_rate'];
    $recommendation_rate = $_POST['recommendation_rate'];
    $suggestion = $_POST['suggestion'];

    // Inserting data into the database
    $insert_query = "INSERT INTO feedback (userID, overall_rate, userDisplay_rate, manageBook_rate, manageComplaint_rate, 
                    manageBooking_rate, manageEvent_rate, staff_rate, recommendation_rate, suggestion)
                    VALUES ('$userID', '$overall_rate', '$userDisplay_rate', '$manageBook_rate', '$manageComplaint_rate', 
                    '$manageBooking_rate', '$manageEvent_rate', '$staff_rate', '$recommendation_rate', '$suggestion')";
    $insert_result = mysqli_query($conn, $insert_query);

    if ($insert_result) {
        // Successful insertion
        echo '<script type="text/javascript">
            alert("New feedback is successfully added!");
            window.location.replace("user_feedback.php");
        </script>';
    } else {
        // If insertion fails, handle the error (you might want to log this)
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // If the form was not submitted properly or if the button name 'B1' wasn't set
    echo '<script type="text/javascript">
        alert("Form submission error!");
        window.history.back();
    </script>';
}

mysqli_close($conn);
?>