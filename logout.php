<?php
// Start the session to access session variables
session_start();

// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page
header("Location: index.php");
exit();
?>
