<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users (name, password, status) VALUES ('$name', '$password', 'pending')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful, waiting for approval.";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Enter your name" required>
    <input type="password" name="password" placeholder="Enter password" required>
    <button type="submit">Register</button>
</form>
