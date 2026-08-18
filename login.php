<?php
// login.php - handle both customer (by Phone) and admin (by UserName) logins
require_once 'db.php';
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? ''); // email or admin username
    $password = $_POST['password'] ?? '';

    if ($identifier === '' || $password === '') {
        $error = 'Please enter both identifier and password.';
    } else {
        // First try admin table by UserName
        $stmt = $mysqli->prepare('SELECT AdminID, UserName, Password FROM admin WHERE UserName = ? LIMIT 1');
        $stmt->bind_param('s', $identifier);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($adminId, $userName, $hash);
            $stmt->fetch();
            if (password_verify($password, $hash)) {
                // Admin login successful
                $_SESSION['AdminID'] = $adminId;
                $_SESSION['UserName'] = $userName;
                $stmt->close();
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Incorrect username or password.';
            }
            $stmt->close();
        } else {
            $stmt->close();
            // Try customer login by email
            $cstmt = $mysqli->prepare('SELECT CustomerID, CustomerName, PasswordHash FROM customer WHERE Email = ? LIMIT 1');
            $cstmt->bind_param('s', $identifier);
            $cstmt->execute();
            $cstmt->store_result();
            if ($cstmt->num_rows > 0) {
                $cstmt->bind_result($custId, $custName, $chash);
                $cstmt->fetch();
                if (password_verify($password, $chash)) {
                    $_SESSION['CustomerID'] = $custId;
                    $_SESSION['CustomerName'] = $custName;
                    $cstmt->close();
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = 'Incorrect email or password.';
                }
            } else {
                $error = 'No account found with that identifier.';
            }
            $cstmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — PharmaCare</title>
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
        <h2>Login to PharmaCare</h2>
        <?php if ($error): ?>
            <p style="color:#b00020; margin-bottom:16px;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label for="identifier">Email or Admin username</label>
            <input type="text" id="identifier" name="identifier" placeholder="Email or admin username" required value="<?php echo htmlspecialchars($_POST['identifier'] ?? ''); ?>">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit">Login</button>
        </form>
        <p class="small-link">Don't have an account? <a href="register.php">Create one</a>.</p>
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
