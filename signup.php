<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <link rel="stylesheet" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <script>
        function toggleIDField() {
            const role = document.getElementById("role").value;
            const idField = document.getElementById("id_field");
            const idLabel = document.getElementById("id_label");

            if (role === "Student") {
                idField.setAttribute("name", "student_id");
                idLabel.textContent = "Student ID:";
                idField.placeholder = "Enter your Student ID";
            } else if (role === "Faculty") {
                idField.setAttribute("name", "faculty_id");
                idLabel.textContent = "Faculty ID:";
                idField.placeholder = "Enter your Faculty ID";
            }
        }
    </script>
</head>
<style type="text/css">
    #main_content {
        padding: 50px;
        background-color: whitesmoke;
    }
    #side_bar {
        background-color: whitesmoke;
        padding: 50px;
        width: 300px;
        height: 450px;
    }
</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Library Management System (LMS)</a>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item"><a class="nav-link" href="index.php">Admin Login</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php">Login</a></li>
            </ul>
        </div>
    </nav><br>
    <div class="row">
        <div class="col-md-4" id="side_bar">
            <h5>Library Timing</h5>
            <ul>
                <li>Opening: 8:00 AM</li>
                <li>Closing: 8:00 PM</li>
                <li>(Sunday Off)</li>
            </ul>
            <h5>What We Provide?</h5>
            <ul>
                <li>Full Furniture</li>
                <li>Free Wi-Fi</li>
                <li>Newspapers</li>
                <li>Discussion Room</li>
                <li>RO Water</li>
                <li>Peaceful Environment</li>
            </ul>
        </div>
        <div class="col-md-8" id="main_content">
            <center><h3><u>User Registration Form</u></h3></center>
            <form action="" method="post">
                <div class="form-group">
                    <label for="role">Register As:</label>
                    <select id="role" name="role" class="form-control" onchange="toggleIDField()" required>
                        <option value="Student">Student</option>
                        <option value="Faculty">Faculty</option>
                    </select>
                </div>
                <div class="form-group">
                    <label id="id_label" for="id_field">Student ID:</label>
                    <input type="text" id="id_field" name="student_id" class="form-control" placeholder="Enter your Student ID" required>
                </div>
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email ID:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile:</label>
                    <input type="text" name="mobile" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea name="address" class="form-control" required></textarea>
                </div>
                <button type="submit" name="register" class="btn btn-primary">Register</button>
            </form>

            <?php
            if (isset($_POST['register'])) {
                $connection = mysqli_connect("localhost", "root", "", "lms");
                if (!$connection) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $role = $_POST['role'];
                $id_field = ($role == 'Student') ? 'student_id' : 'faculty_id';
                $id_value = mysqli_real_escape_string($connection, $_POST[$id_field]);
                $name = mysqli_real_escape_string($connection, $_POST['name']);
                $email = mysqli_real_escape_string($connection, $_POST['email']);
                $password = mysqli_real_escape_string($connection, $_POST['password']);
                $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);
                $address = mysqli_real_escape_string($connection, $_POST['address']);

                // Hash the password before storing it
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Determine table and validate ID
                $table = ($role == 'Student') ? 'Student' : 'Faculty';

                // Check if ID already exists
                $check_id_query = "SELECT * FROM $table WHERE $id_field = '$id_value'";
                $check_id_result = mysqli_query($connection, $check_id_query);

                if (mysqli_num_rows($check_id_result) > 0) {
                    echo "<br><br><center><span class='alert-danger'>ID already exists. Please choose a different ID.</span></center>";
                } else {
                    // Insert data into the appropriate table
                    $query = "INSERT INTO $table ($id_field, name, email, password, mobile, address) 
                              VALUES ('$id_value', '$name', '$email', '$hashed_password', '$mobile', '$address')";
                    if (mysqli_query($connection, $query)) {
                        echo "<br><br><center><span class='alert-success'>Registration Successful</span></center>";
                    } else {
                        echo "<br><br><center><span class='alert-danger'>Registration Failed: " . mysqli_error($connection) . "</span></center>";
                    }
                }

                mysqli_close($connection);
            }
            ?>
        </div>
    </div>
</body>
</html>
