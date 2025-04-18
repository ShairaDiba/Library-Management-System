<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Establish database connection
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$name = "";
$email = "";
$mobile = "";
$address = "";

// Determine the user role
$role = $_SESSION['role'];  // Assuming 'role' is stored in session (Student or Faculty)

// Use a prepared statement to avoid SQL injection
if ($role == 'Student') {
    // For Student
    $query = "SELECT name, email, mobile, address FROM Student WHERE email = ?";
} else if ($role == 'Faculty') {
    // For Faculty
    $query = "SELECT name, email, mobile, address FROM Faculty WHERE email = ?";
}

$stmt = $connection->prepare($query);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$stmt->bind_result($name, $email, $mobile, $address);
$stmt->fetch();
$stmt->close();
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Profile</title>
    <link rel="stylesheet" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
    
    <style>
        /* Replace deprecated marquee with CSS animation */
        .announcement {
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            padding: 10px;
            text-align: center;
            animation: scroll-left 10s linear infinite;
        }
        
        @keyframes scroll-left {
            from { transform: translateX(100%); }
            to { transform: translateX(-100%); }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo ($role == 'Student') ? 'user_dashboard.php' : 'faculty.php'; ?>">Library Management System (LMS)</a>
            </div>
            <span class="navbar-text text-white">
                <strong>Welcome: <?php echo $_SESSION['name']; ?></strong> | <strong>Email: <?php echo $_SESSION['email']; ?></strong>
            </span>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="view_profile.php">View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav><br>

    <span><marquee>This is library management system. Library opens at 8:00 AM and closes at 8:00 PM</marquee></span><br><br>
    
    <center><h4><?php echo ($role == 'Student') ? 'Student' : 'Faculty'; ?> Profile Details</h4></center><br>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($name); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" value="<?php echo htmlspecialchars($email); ?>" class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label for="mobile">Phone Number:</label>
                    <input type="text" value="<?php echo htmlspecialchars($mobile); ?>" class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" value="<?php echo htmlspecialchars($address); ?>" class="form-control" disabled>
                </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>

</body>
</html>
