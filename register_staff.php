<!DOCTYPE html>
<html>
    <head>
        <title> Staff Registration </title>
        <link rel = "stylesheet" href ="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <head>
<body>
    <div class="container">
    <form action="register_staff_action.php" method="post" enctype="multipart/form-data">
        <div class="register-form">
            <h1>Staff Registration Form</h1>
            <p> Fill in the form with your details. </p>
            <table class="reg-table">
                    <tr>
                        <td><label for="staffName">Name</label></td>
                        <td><input type="text" name="staffName" size="30" required></td>
                    </tr>
                    <tr>
                        <td><label for="staffEmail">Email</label></td>
                        <td><input type="text" name="staffEmail" size="30"required></td>
                    </tr>
                    <tr>
                        <td><label for="staffPass">Create Password</label></td>
                        <td><input type="password" name="staffPass" size="30"required></td>
                    </tr>
                    <tr>
                        <td><label for="staffIC">NRIC</label></td>
                        <td><input type="text" name="staffIC" size="30"required></td>
                    </tr>
                    <tr>
                        <td><label for="staffPhone">Phone Number</label></td>
                        <td><input type="text" name="staffPhone" size="30" required></td>
                    </tr>
                    <tr>
                        <td><label for="staffDob">Birthday</label></td>
                        <td><input type="date" name="staffDob" size="30" required></td>
                    </tr>
                    <tr>
                        <td><label for="staffGender">Gender</label></td>
                        <td>
                            <select name="staffGender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="staffAdd">Address</label></td>
                        <td><input type="text" name="staffAdd" size="30"required></td>
                    </tr>
                    <tr>
                        <td><label for="staffState">State of Origin</label></td>
                        <td>
                            <select name="staffState" required>
                                <option value="" disabled selected>Select State of Origin</option>
                                <option value="Johor">Johor Bahru</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="Labuan">Labuan</option>
                                <option value="Perak">Perak</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Malacca">Malacca</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Penang">Penang</option>
                                <option value="Putrajaya">Putrajaya</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                                <option value="Terengganu">Terengganu</option>
                                </select>
            </td>
        </tr>
        <tr>
            <td><label for="staffPhoto">Photo</label></td>
            <td><input type="file" name="staffPhoto"></td>
        </tr>
        <tr>
                        <td colspan="2">
                        <div class="login-btn">
                    <input type="submit" value="Register">
                    <div class="back">
                            <a href="login_staff.php">Back</a>
                        </td>
                    </tr>
    </table>
</div>
</form>
</div>
</body>
</html>