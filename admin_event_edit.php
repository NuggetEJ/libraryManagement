<?php
session_start();
include "config.php";
if (!isset($_SESSION["staffEmail"])) {
    header("location:logout.php");
    exit;
}
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
    <?php include 'admin_event_menu.php'; ?>

    <!-- header at right side -->
    <header>
        <!-- title your page -->
        <h2>Library Event</h2>
        <a href="logout.php" class="header-icon"><i class="fa fa-sign-out"></i></a>
    </header>

    <!-- masukkan body page kmu disini -->
    <div class="content">
        <p>
            <?php
            $action = "";
            $id = "";
            $eventName = "";
            $eventDesc = "";
            $eventDateStart = " ";
            $eventTimeStart = "";
            $eventDateEnd = "";
            $eventTimeEnd = "";
            $eventLocation = " ";
            $eventCategory = "";

            if (isset($_GET["id"]) && $_GET["id"] != "") {
                $sql = "SELECT * FROM events WHERE staffID=" . $_SESSION["UID"];
                //echo $sql . "<br>";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $id = $row["eventID"];
                    $eventName = $row["eventName"];
                    $eventDesc = $row["eventDesc"];
                    $eventDateStart = $row["eventDateStart"];
                    $eventTimeStart = $row["eventTimeStart"];
                    $eventDateEnd = $row["eventDateEnd"];
                    $eventTimeEnd = $row["eventTimeEnd"];
                    $eventLocation = $row["eventLocation"];
                    $eventCategory = $row["eventCategory"];
                }
            }

            mysqli_close($conn);
            ?>

        <p align="center" width="100%">
        <div style="padding:0 10px;" id="editEvent">
            <h3 align="center">Edit Event</h3>

            <form method="POST" action="admin_event_edit_action.php" enctype="multipart/form-data" id="eventForm">
                <!--hidden value: id to be submitted to action page-->
                <input type="hidden" id="eventID" name="eventID" value="<?= $_GET['id'] ?>">
                <table border="1" id="addEvent_table" align="center">

                    <tr>
                        <td>Event Name</td>
                        <td width="1px">:</td>
                        <td>
                            <textarea rows="3" cols="40" name="eventName" required><?php echo $eventName; ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td>Description</td>
                        <td>:</td>
                        <td>
                            <textarea rows="8" cols="40" name="eventDesc" required><?php echo $eventDesc; ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td>Date Start</td>
                        <td>:</td>
                        <td>
                            <input type="date" name="eventDateStart" value="<?php echo $eventDateStart; ?>" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Time Start</td>
                        <td>:</td>
                        <td>
                            <input type="time" name="eventTimeStart" value="<?php echo $eventTimeStart; ?>" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Date End</td>
                        <td>:</td>
                        <td>
                            <input type="date" name="eventDateEnd" value="<?php echo $eventDateEnd; ?>" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Time End</td>
                        <td>:</td>
                        <td>
                            <input type="time" name="eventTimeEnd" value="<?php echo $eventTimeEnd; ?>" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Location</td>
                        <td>:</td>
                        <td>
                            <select size="1" name="eventLocation" required>
                                <option value="">&nbsp;</option>
                                <?php
                                if ($eventLocation == "Meeting Room")
                                    echo '<option value="Meeting Room" selected>Meeting Room</option>';
                                else
                                    echo '<option value="Meeting Room">Meeting Room</option>';

                                if ($eventLocation == "Auditorium")
                                    echo '<option value="Auditorium" selected>Auditorium</option>';
                                else
                                    echo '<option value="Auditorium">Auditorium</option>';

                                if ($eventLocation == "Community Room")
                                    echo '<option value="Community Room" selected>Community Room</option>';
                                else
                                    echo '<option value="Community Room">Community Room</option>';

                                if ($eventLocation == "Conference Room")
                                    echo '<option value="Conference Room" selected>Conference Room</option>';
                                else
                                    echo '<option value="Conference Room">Conference Room</option>';

                                if ($eventLocation == "Children Area")
                                    echo '<option value="Children Area" selected>Children Area</option>';
                                else
                                    echo '<option value="Children Area">Children Area</option>';

                                if ($eventLocation == "Courtyard")
                                    echo '<option value="Courtyard" selected>Courtyard</option>';
                                else
                                    echo '<option value="Courtyard">Courtyard</option>';
                                ?>
                            </select>
                    </tr>

                    <tr>
                        <td>Category</td>
                        <td>:</td>
                        <td>
                            <select size="1" name="eventCategory" required>
                                <option value="">&nbsp;</option>
                                <?php
                                if ($eventCategory == "Children Program")
                                    echo '<option value="Children Program" selected>Children Program</option>';
                                else
                                    echo '<option value="Children Program">Children Program</option>';

                                if ($eventCategory == "Teen Program")
                                    echo '<option value="Teen Program" selected>Teen Program</option>';
                                else
                                    echo '<option value="Teen Program">Teen Program</option>';

                                if ($eventCategory == "Literary Events")
                                    echo '<option value="Literary Events" selected>Literary Events</option>';
                                else
                                    echo '<option value="Literary Events">Literary Events</option>';

                                if ($eventCategory == "Technology and Digital Literacy")
                                    echo '<option value="Technology and Digital Literacy" selected>Technology and Digital Literacy</option>';
                                else
                                    echo '<option value="Technology and Digital Literacy">Technology and Digital Literacy</option>';

                                if ($eventCategory == "Cultural Events")
                                    echo '<option value="Cultural Events" selected>Cultural Events</option>';
                                else
                                    echo '<option value="Cultural Events">Cultural Events</option>';

                                if ($eventCategory == "Health and Wellness Programs")
                                    echo '<option value="Health and Wellness Programs" selected>Health and Wellness Programs</option>';
                                else
                                    echo '<option value="Health and Wellness Programs">Health and Wellness Programs</option>';

                                if ($eventCategory == "Local History and Genealogy")
                                    echo '<option value="Local History and Genealogy" selected>Local History and Genealogy</option>';
                                else
                                    echo '<option value="Local History and Genealogy">Local History and Genealogy</option>';

                                if ($eventCategory == "Community Engagement")
                                    echo '<option value="Community Engagement" selected>Community Engagement</option>';
                                else
                                    echo '<option value="Community Engagement">Community Engagement</option>';

                                if ($eventCategory == "Others")
                                    echo '<option value="Others" selected>Others</option>';
                                else
                                    echo '<option value="Others">Others</option>';
                                ?>
                            </select>
                    </tr>

                    <tr>
                        <td>Upload photo</td>
                        <td>:</td>
                        <td>
                            Max size: 2MB<br>
                            <input type="file" name="fileToUpload" id="fileToUpload" accept=".jpg, .jpeg, .png">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" align="right">
                            <input type="submit" value="Submit" name="B1">
                            <input type="reset" value="Reset" name="B2">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        </p>
    </div>

    <script>
        //reset form after modification to a php echo to fields
        function resetForm() {
            document.getElementById("eventForm").reset();
        }

        //this clear form to empty the form for new data
        function clearForm() {
            var form = document.getElementById("eventForm");
            if (form) {
                var inputs = form.getElementsByTagName("input");
                var textareas = form.getElementsByTagName("textarea");

                //clear select
                form.getElementsByTagName("select")[0].selectedIndex = 0;

                //clear all inputs
                for (var i = 0; i < inputs.length; i++) {
                    if (inputs[i].type !== "button" && inputs[i].type !== "submit" && inputs[i].type !== "reset") {
                        inputs[i].value = "";
                    }
                }

                //clear all textareas
                for (var i = 0; i < textareas.length; i++) {
                    textareas[i].value = "";
                }
            } else {
                console.error("Form not found");
            }
        }
    </script>
    <script src="js/script.js"></script>
</body>

</html>