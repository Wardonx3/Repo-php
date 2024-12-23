<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users WHERE status='pending'";
$result = $conn->query($sql);
?>

<h1>Admin Panel</h1>
<table>
    <tr>
        <th>Name</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
            <a href="approve.php?id=<?php echo $row['id']; ?>">Approve</a> |
            <a href="reject.php?id=<?php echo $row['id']; ?>">Reject</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php
$conn->close();
?>
