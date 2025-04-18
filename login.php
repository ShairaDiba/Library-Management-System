<?php
    session_start();
    $connection = mysqli_connect("localhost", "root", "");
    $db = mysqli_select_db($connection, "lms");

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password']; // Password entered by user

        // Check user role (assuming you have a role dropdown or session variable)
        $role = $_POST['role'];
        $table = $role == 'Student' ? 'Student' : 'Faculty';

        // Fetch user data based on email
        $query = "SELECT * FROM $table WHERE email = '$email'";
        $query_run = mysqli_query($connection, $query);
        $user = mysqli_fetch_assoc($query_run);

        // Check if user exists and verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, set session variables and redirect
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $role; // Store role in session
            header("Location: user_dashboard.php");
        } else {
            // Invalid email or password
            echo "<script type='text/javascript'>
                    alert('Invalid email or password.');
                    window.location.href = 'login.php';
                  </script>";
        }
    }
?>
