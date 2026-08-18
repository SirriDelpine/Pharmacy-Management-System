<?php
// Simple dashboard.php - shows basic info for logged-in user
require_once 'db.php';
session_start();
// Redirect if not logged in
if (!isset($_SESSION['CustomerID']) && !isset($_SESSION['AdminID'])) {
    header('Location: login.php');
    exit;
}
$isAdmin = isset($_SESSION['AdminID']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — PharmaCare</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="header">
    <a class="logo" href="index.html">PharmaCare</a>
    <nav class="navbar" id="navbar">
        <a href="index.html">Home</a>
        <a href="index.html#about">About</a>
        <a href="index.html#services">Services</a>
        <a href="index.html#contact">Contact</a>
        <a class="btn outline login-btn" href="logout.php">Logout</a>
    </nav>
</header>
<section class="auth-page">
    <div class="auth-card">
        <?php if ($isAdmin): ?>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['UserName']); ?> (Admin)</h2>
            <p>You are logged in as an administrator.</p>
        <?php else: ?>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['CustomerName']); ?></h2>
            <p>Customer ID: <?php echo htmlspecialchars($_SESSION['CustomerID']); ?></p>
        <?php endif; ?>
        <p style="margin-top:18px;"><a href="logout.php">Log out</a></p>
    </div>
</section>
</body>
</html>
