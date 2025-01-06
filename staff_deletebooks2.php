<?php
session_start();
include 'config.php';

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $bookID = $_GET["id"];

    $deleteQuery = "DELETE FROM books WHERE bookID = ?";
    $stmtDelete = mysqli_prepare($conn, $deleteQuery);

    if ($stmtDelete) {
        mysqli_stmt_bind_param($stmtDelete, "i", $bookID);
        mysqli_stmt_execute($stmtDelete);

        if (mysqli_stmt_affected_rows($stmtDelete) > 0) {
            echo "Book deleted successfully";

            header("Location: staffbooks.php");
            exit();
        } else {
            echo "No records deleted. Check bookID.";
        }

        mysqli_stmt_close($stmtDelete);
    } else {
        echo "Error in delete prepared statement: " . mysqli_error($conn);
    }
} else {
    echo "Invalid parameters";
}

mysqli_close($conn);
?>
