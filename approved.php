<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE users SET status='approved' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "User approved successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>


approved.php

<?php
session_start();
if ($_SESSION['status'] != 'approved') {
    header("Location: pending.php");
    exit();
}
?>

<h1>Your now approved!</h1>
<button onclick="window.location.href='http://faizu.wuaze.com'">Click to Enjoy</button>

