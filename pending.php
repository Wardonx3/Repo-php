<?php
session_start();
if ($_SESSION['status'] != 'pending') {
    header("Location: approved.php");
    exit();
}
?>
<h1>Your request is pending. Please wait for approval.</h1>
