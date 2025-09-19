<?php
session_start();
// Destroy all session data
session_unset();
session_destroy();
// Redirect to login page (change to your login page if needed)
header('Location: Login.php');
exit;
