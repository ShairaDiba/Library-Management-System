<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

function get_faculty_issued_books() {
    $connection = mysqli_connect("localhost", "root", "", "lms");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to get the issued book details for the faculty member
    $query = "SELECT b.book_name, i.faculty_id, i.issue_date, i.return_date 
              FROM issued_books i
              LEFT JOIN books b ON i.book_id = b.book_id
              WHERE i.faculty_id = ? AND i.status = 1";
    
    // Check if query was executed properly
    if (!$stmt = $connection->prepare($query)) {
        die("Query preparation failed: " . $connection->error);
    }

    // Bind the faculty ID parameter
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if result contains any data
    if ($result->num_rows === 0) {
        echo "No books found for this faculty.";
    }

    $issued_books = [];

    while ($row = $result->fetch_assoc()) {
        $issued_books[] = $row;
    }

    // Close the statement and connection
    $stmt->close();
    mysqli_close($connection);

    return $issued_books;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Issued Books</title>
    <meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="faculty_dashboard.php">Library Management System (LMS)</a>
            </div>
            <font style="color: white"><span><strong>Welcome: <?php echo $_SESSION['name']; ?></strong></span></font>
            <font style="color: white"><span><strong>Email: <?php echo $_SESSION['email']; ?></strong></span></font>
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
    <div class="container">
        <h2>Issued Books for Faculty</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Faculty ID</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $issued_books = get_faculty_issued_books();
                if (empty($issued_books)) {
                    echo "<tr><td colspan='4' class='text-center'>No books issued</td></tr>";
                } else {
                    foreach ($issued_books as $book) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($book['book_name']) . "</td>"; // Sanitize output
                        echo "<td>" . htmlspecialchars($book['faculty_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($book['issue_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($book['return_date']) . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
