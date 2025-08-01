<?php
$user = $_GET['user'] ?? null;

if ($user === 'admin') {
    header("Location: /api/dashboard.php?user=admin");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login (Vercel Compatible)</title>
</head>

<body>
    <h2>Login Page</h2>
    <form method="get" action="login.php">
        <label>Username:</label><br>
        <input type="text" name="user" /><br><br>
        <input type="submit" value="Login" />
    </form>
</body>

</html>