<?php
// register.php - handle customer registration
require_once 'db.php';
session_start();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if ($name === '' || $email === '' || $address === '' || $password === '') {
        $message = 'Please fill in all required fields.';
    } else {
        // Check if email already exists
        $stmt = $mysqli->prepare('SELECT CustomerID FROM customer WHERE Email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $message = 'A user with that email already exists.';
            $stmt->close();
        } else {
            $stmt->close();
            // Insert new customer using the PasswordHash column
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $mysqli->prepare('INSERT INTO customer (CustomerName, Email, Address, PasswordHash) VALUES (?, ?, ?, ?)');
            $ins->bind_param('ssss', $name, $email, $address, $hash);
            if ($ins->execute()) {
                $message = 'Registration successful. <a href="login.php">Click here to login</a>.';
            } else {
                $message = 'Registration failed: ' . htmlspecialchars($ins->error);
            }
            $ins->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — PharmaCare</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<header class="header">
    <a class="logo" href="index.html">PharmaCare</a>
    <button class="sidebar-toggle" type="button" id="togglebtn">
        <i class="fa-solid fa-bars"></i>
    </button>
    <nav class="navbar" id="navbar">
        <a href="index.html">Home</a>
        <a href="index.html#about">About</a>
        <a href="index.html#services">Services</a>
        <a href="index.html#testimonials">Testimonials</a>
        <a href="index.html#contact">Contact</a>
        <a class="btn outline login-btn" href="login.php">Login</a>
        <a class="btn register-btn" href="register.php">Register</a>
        <a class="readme-btn" href="readme.html">Read Me</a>
    </nav>
</header>
<section class="auth-page">
    <div class="auth-card">
        <h2>Create your PharmaCare account</h2>
        <?php if ($message): ?>
            <p style="color:var(--primary); margin-bottom:16px;"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post" action="register.php">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" placeholder="Your full name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Your address" required value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password" required>

            <button type="submit">Register</button>
        </form>
        <p class="small-link">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</section>
<footer>
    <p>© 2026 PharmaCare. All rights reserved.</p>
</footer>
<script>
    const toggleBtn = document.getElementById('togglebtn');
    const navbar = document.getElementById('navbar');
    if (toggleBtn && navbar) {
        toggleBtn.addEventListener('click', () => navbar.classList.toggle('active'));
    }

    document.querySelectorAll('.navbar a').forEach(link => link.addEventListener('click', () => navbar.classList.remove('active')));
</script>
</body>
</html>
