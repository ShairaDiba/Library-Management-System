<?php
session_start();
// Database connection
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "lms");

if (isset($_POST['update'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Ensure the new password and confirm password match
    if ($new_password !== $confirm_password) {
        echo "<script type='text/javascript'>
                alert('New password and confirm password do not match.');
                window.location.href = 'change_password.php';
              </script>";
        exit();
    }

    // Determine the user role and corresponding table
    $role = $_SESSION['role'];
    $table = $role == 'Student' ? 'Student' : 'Faculty';

    // Retrieve current user details
    $email = $_SESSION['email'];

    // Use prepared statement to avoid SQL injection
    $query = "SELECT password FROM $table WHERE email = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $email);  // Bind email as parameter
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // Check if the old password matches
    if ($stored_password && password_verify($old_password, $stored_password)) {
        // Hash the new password
        $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password if old password matches
        $update_query = "UPDATE $table SET password = ? WHERE email = ?";
        $update_stmt = $connection->prepare($update_query);
        $update_stmt->bind_param("ss", $new_password_hashed, $email);  // Bind parameters
        $update_run = $update_stmt->execute();

        if ($update_run) {
            echo "<script type='text/javascript'>
                    alert('Password updated successfully.');
                    window.location.href = 'user_dashboard.php';
                  </script>";
        } else {
            echo "<script type='text/javascript'>
                    alert('Failed to update password. Please try again.');
                    window.location.href = 'change_password.php';
                  </script>";
        }
    } else {
        echo "<script type='text/javascript'>
                alert('Old password is incorrect.');
                window.location.href = 'change_password.php';
              </script>";
    }
}
?>
