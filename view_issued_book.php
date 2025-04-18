<?php
session_start();
# Fetch data from the database
$connection = mysqli_connect("localhost", "root", "", "lms");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Initialize variables
$book_name = "";
$author = "";
$student_id = "";
$issue_date = "";
$return_date = "";

// Query to fetch required columns including issue_date and return_date
$query = "SELECT book_name, book_author, student_id, issue_date, return_date 
          FROM issued_books 
          WHERE student_id = {$_SESSION['id']} AND status = 1";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Issued Books</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="user_dashboard.php">Library Management System (LMS)</a>
            </div>
            <font style="color: white"><span><strong>Welcome: <?php echo $_SESSION['name']; ?></strong></span></font>
            <font style="color: white"><span><strong>Email: <?php echo $_SESSION['email']; ?></strong></span></font>
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
    <span><marquee>This is a library management system. Library opens at 8:00 AM and closes at 8:00 PM</marquee></span><br><br>
    <center><h4>Issued Book's Detail</h4><br></center>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <form>
                <table class="table-bordered" width="900px" style="text-align: center">
                    <tr>
                        <th>Book Name</th>
                        <th>Author</th>
                        <th>Student ID</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                    </tr>
                
                    <?php
                    $query_run = mysqli_query($connection, $query);
                    if (!$query_run) {
                        echo "<tr><td colspan='5'>Error fetching data: " . mysqli_error($connection) . "</td></tr>";
                    } elseif (mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            ?>
                            <tr>
                                <td><?php echo $row['book_name']; ?></td>
                                <td><?php echo $row['book_author']; ?></td>
                                <td><?php echo $row['student_id']; ?></td>
                                <td><?php echo $row['issue_date']; ?></td>
                                <td><?php echo $row['return_date']; ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5'>No books issued.</td></tr>";
                    }
                    ?>  
                </table>
            </form>
        </div>
        <div class="col-md-2"></div>
    </div>
</body>
</html>