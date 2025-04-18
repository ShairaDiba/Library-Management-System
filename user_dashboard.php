<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

function get_user_issue_book_count() {
    $connection = mysqli_connect("localhost", "root", "", "lms");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT COUNT(*) as user_issue_book_count FROM issued_books WHERE student_id = ? AND status = 1";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($user_issue_book_count);

    $stmt->fetch();
    $stmt->close();
    mysqli_close($connection);

    return $user_issue_book_count;
}

function get_user_total_fine_amount() {
    $connection = mysqli_connect("localhost", "root", "", "lms");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT fine_amount FROM student WHERE student_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($fine_amount);

    $stmt->fetch();
    $stmt->close();
    mysqli_close($connection);

    return $fine_amount;
}

function get_overdue_books() {
    $connection = mysqli_connect("localhost", "root", "", "lms");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT book_id, return_date FROM issued_books WHERE student_id = ? AND status = 1";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($book_id, $return_date);

    $overdue_books = [];
    $current_date = date("Y-m-d");

    while ($stmt->fetch()) {
        $return_date_obj = new DateTime($return_date);
        $current_date_obj = new DateTime($current_date);

        // If the return date is in the past
        if ($current_date_obj > $return_date_obj) {
            $interval = $current_date_obj->diff($return_date_obj);
            $overdue_books[] = [
                'book_id' => $book_id,
                'return_date' => $return_date,
                'overdue_days' => $interval->days - 7 // Subtract grace period
            ];
        }
    }

    $stmt->close();
    mysqli_close($connection);

    return $overdue_books;
}

function calculate_and_update_overdue_fine() {
    $connection = mysqli_connect("localhost", "root", "", "lms");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $fine_per_day = 5; // Set fine rate
    $current_date = date("Y-m-d");

    // Query to get overdue books for the user
    $overdue_books = get_overdue_books();
    $new_fine = 0;

    foreach ($overdue_books as $book) {
        $overdue_days = max(0, $book['overdue_days']);
        $new_fine += $overdue_days * $fine_per_day;
    }

    // Get current fine from the student table
    $current_fine = get_user_total_fine_amount();

    // Only update the fine in the database if there is a change
    if ($new_fine != $current_fine) {
        $update_query = "UPDATE student SET fine_amount = ? WHERE student_id = ?";
        $update_stmt = $connection->prepare($update_query);
        $update_stmt->bind_param("di", $new_fine, $_SESSION['id']);
        $update_stmt->execute();
        $update_stmt->close();
    }

    mysqli_close($connection);

    return $new_fine;
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
                <a class="navbar-brand" href="user_dashboard.php">Library Management System (LMS)</a>
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
              </li>            </ul>
        </div>
    </nav><br>
    <span><marquee>This is library management system. Library opens at 8:00 AM and closes at 8:00 PM</marquee></span><br><br>
    <div class="row">
        <div class="col-md-3" style="margin: 25px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Books Issued</div>
                <div class="card-body">
                    <p class="card-text">Number of books issued: <?php echo get_user_issue_book_count(); ?></p>
                    <a class="btn btn-success" href="view_issued_book.php">View Issued Books</a>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="margin: 25px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Total Fine Amount</div>
                <div class="card-body">
                    <?php
                    $total_fine = calculate_and_update_overdue_fine();
                    ?>
                    <p class="card-text">Total fine amount due: $<?php echo number_format($total_fine, 2); ?></p>
                    <?php if ($total_fine > 0) : ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>Warning!</strong> You have overdue books and a fine to pay.
                            Contact Library Staff ASAP!!.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Overdue Books Section -->
        <div class="col-md-6" style="margin: 25px">
            <div class="card bg-light">
                <div class="card-header">Overdue Books</div>
                <div class="card-body">
                    <?php
                    $overdue_books = get_overdue_books();
                    if (count($overdue_books) > 0) {
                        echo "<ul>";
                        foreach ($overdue_books as $book) {
                            // Format return date to a nice format
                            $formatted_return_date = (new DateTime($book['return_date']))->format('d-m-Y');
                            echo "<li>Book ID: {$book['book_id']} - Overdue Days: {$book['overdue_days']} - Return date was: {$formatted_return_date}</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>No overdue books.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
