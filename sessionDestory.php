<?php
session_start();

// Check if the session variable exists
if (isset($_SESSION['error_message'])) {
    unset($_SESSION['error_message']);  // Remove the specific session variable
}
?>
