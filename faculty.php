<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

function get_faculty_issue_book_count() {
    $connection = mysqli_connect("localhost", "root", "", "lms");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to count issued books for the faculty member
    $query = "SELECT COUNT(*) as faculty_issue_book_count FROM issued_books WHERE faculty_id = ? AND status = 1";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($faculty_issue_book_count);

    // Fetch result
    $stmt->fetch();

    // Close the statement and connection
    $stmt->close();
    mysqli_close($connection);

    return $faculty_issue_book_count;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/juqery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
            </div>
            <font style="color: white"><span><strong>Welcome: <?php echo $_SESSION['name'];?></strong></span></font>
            <font style="color: white"><span><strong>Email: <?php echo $_SESSION['email'];?></strong></font>
            <ul class="nav navbar-nav navbar-right">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile </a>
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
    <div class="row">
        <div class="col-md-3" style="margin: 25px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Books Issued</div>
                <div class="card-body">
                    <p class="card-text">Number of books issued: <?php echo get_faculty_issue_book_count(); ?></p>
                    <a class="btn btn-success" href="view_faculty_issued_books.php">View Issued Books</a>
                </div>
            </div>
        </div>

        <!-- Removed Issued Books Details Box -->
        <!-- The section below has been removed as per your request -->
    </div>
</body>
</html>
