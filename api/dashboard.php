<?php
$user = $_GET['user'] ?? '';

if ($user !== 'admin') {
    header("Location: /api/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <h2>Welcome, <?php echo htmlspecialchars($user); ?>!</h2>
    <p>This is your dashboard on Vercel-compatible PHP.</p>
    <ul>
        <li><a href="#">Overview</a></li>
        <li><a href="#">Settings</a></li>
    </ul>
    <p><a href="/api/login.php">Logout</a></p>
</body>

</html>