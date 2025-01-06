<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <form action="register_user_action.php" method="post" enctype="multipart/form-data">
            <div class="register-form">
                <h1>User Registration Form</h1>
                <p>Fill in the form with your details.</p>
                <table class="reg-table">
                    <tr>
                        <td><label for="userName">Name</label></td>
                        <td><input type="text" name="userName" size="30" required></td>
                    </tr>
                    <tr>
                        <td><label for="userEmail">Email</label></td>
                        <td><input type="text" name="userEmail" size="30"required></td>
                    </tr>
                    <tr>
                        <td><label for="userPass">Create Password</label></td>
                        <td><input type="password" name="userPass" size="30"required></td>
                    </tr>
                    <tr>
                        <td><label for="userIC">NRIC</label></td>
                        <td><input type="text" name="userIC" size="30"required></td>
                    </tr>
                    <tr>
                        <td><label for="userPhone">Phone Number</label></td>
                        <td><input type="text" name="userPhone" size="30" required></td>
                    </tr>
                    <tr>
                        <td><label for="userDob">Birthday</label></td>
                        <td><input type="date" name="userDob" size="30" required></td>
                    </tr>
                    <tr>
                        <td><label for="userGender">Gender</label></td>
                        <td>
                            <select name="userGender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="userAdd">Address</label></td>
                        <td><input type="text" name="userAdd" size="30"required></td>
                    </tr>
                    <tr>
                        <td><label for="userState">State of Origin</label></td>
                        <td>
                            <select name="userState" required>
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
                        <td colspan="2">
                        <div class="login-btn">
                    <input type="submit" value="Register">
                    <div class="back">
                            <a href="login_user.php">Back</a>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>
</html>
                
                