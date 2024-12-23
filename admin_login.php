<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_password = $_POST['password'];
    if ($admin_password == "FAIZU_H3R3") { // admin password
        $_SESSION['admin'] = true;
        header("Location: admin_dashboard.php");
    } else {
        echo "Invalid password.";
    }
}
?>

<form method="POST">
    <input type="password" name="password" placeholder="Enter admin password" required>
    <button type="submit">Login</button>
</form>
