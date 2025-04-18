<?php
$connection = mysqli_connect("localhost", "root", "", "lms");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $table = $role == 'Student' ? 'Student' : 'Faculty';

    // Sanitize user inputs to avoid SQL injection
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Securely hash the password
    $phone_number = mysqli_real_escape_string($connection, $_POST['phone_number']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);

    // Use a prepared statement to prevent SQL injection
    $stmt = $connection->prepare("INSERT INTO $table (name, email, password, phone_number, address) 
                                  VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $phone_number, $address);

    if ($stmt->execute()) {
        echo "<script type='text/javascript'>
            alert('Registration successful...You may Login now!!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script type='text/javascript'>
            alert('Registration failed: " . $stmt->error . "');
            window.location.href = 'signup.php';
        </script>";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    mysqli_close($connection);
}
?>
