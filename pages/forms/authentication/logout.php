<?php
session_start();
session_destroy();

// Clear cookies
setcookie("user_id", "", time() - 3600, "/");
setcookie("email", "", time() - 3600, "/");
setcookie("role", "", time() - 3600, "/");

// Redirect to login page (change the path as needed)
header('Location: ../../../index.php');
exit;