<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>LMS - User Login</title>
    <meta charset="utf-8" name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<style type="text/css">
    #main_content{
        padding: 50px;
        background-color: whitesmoke;
    }
    #side_bar{
        background-color: whitesmoke;
        padding: 50px;
        width: 300px;
        height: 450px;
    }
</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Library Management System (LMS)</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item">
                    <a class="nav-link" href="admin/index.php">Admin Login</a>  <!-- Admin Login Link -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Login</a>
                </li>
            </ul>
        </div>
    </nav><br>
    <span><marquee>This is library management system. Library opens at 8:00 AM and closes at 8:00 PM</marquee></span><br><br>
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
                <li>Full furniture</li>
                <li>Free Wi-Fi</li>
                <li>Newspapers</li>
                <li>Discussion Room</li>
                <li>RO Water</li>
                <li>Peaceful Environment</li>
            </ul>
        </div>
        <div class="col-md-8" id="main_content">
            <center><h3><u>User Login</u></h3></center>
            <form action="" method="post">
                <div class="form-group">
                    <label for="role">Login As:</label>
                    <select name="role" class="form-control" required>
                        <option value="Student">Student</option>
                        <option value="Faculty">Faculty</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email ID:</label>
                    <input type="text" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Login</button> |
                <a href="signup.php">Not registered yet?</a>    
            </form>

            <?php
            if (isset($_POST['login'])) {
                $connection = mysqli_connect("localhost", "root", "");
                $db = mysqli_select_db($connection, "lms");

                $role = $_POST['role'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                if ($role == 'Student') {
                    // Query for student login
                    $query = "SELECT * FROM Student WHERE email = '$email'";
                } else if ($role == 'Faculty') {
                    // Query for faculty login
                    $query = "SELECT * FROM Faculty WHERE email = '$email'";
                }

                $query_run = mysqli_query($connection, $query);
                if ($row = mysqli_fetch_assoc($query_run)) {
                    // Verify password using password_verify if password is hashed
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['name'] = $row['name'];
                        $_SESSION['email'] = $row['email'];
                        
                        if ($role == 'Student') {
                            $_SESSION['id'] = $row['student_id'];
                        } else if ($role == 'Faculty') {
                            $_SESSION['id'] = $row['faculty_id'];
                        }

                        // Redirect to user dashboard (Student or Faculty)
                        header("Location: user_dashboard.php");
                    } else {
                        ?>
                        <br><br><center><span class="alert-danger">Wrong Password !!</span></center>
                        <?php
                    }
                } else {
                    ?>
                    <br><br><center><span class="alert-danger">User Not Found !!</span></center>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
