<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE name='$name'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['status'] = $user['status'];

            if ($user['status'] == 'pending') {
                header("Location: pending.php"); // Redirect to pending page
            } elseif ($user['status'] == 'approved') {
                header("Location: approved.php"); // Redirect to approved page
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this name.";
    }

    $conn->close();
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Enter your name" required>
    <input type="password" name="password" placeholder="Enter password" required>
    <button type="submit">Login</button>
</form>
