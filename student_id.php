<?php
session_start();

$connection = mysqli_connect("localhost", "root", "", "lms");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM students WHERE email = ? AND password = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $_SESSION['student_id'] = $student['student_id']; // Save student_id in session
        $_SESSION['name'] = $student['name'];
        header("Location: user_dashboard.php");
        exit();
    } else {
        echo "Invalid login credentials.";
    }
}
?>
