<?php
session_start();
include '../includes/header.php';

// Destroy session
session_unset();
session_destroy();

// Redirect to main homepage
header("Location: ../index.php");
exit;
?>