<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_name'])) {
    header("Location: ./dashboad.php");
    exit();
}

include '../conn.php';

$error = '';
$success = '';

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        $select = "SELECT * FROM admin_users WHERE email = '$email'";
        $query = mysqli_query($conn, $select);
        
        if (!$query) {
            $error = "Database error: " . mysqli_error($conn);
        } else {
            $row_count = mysqli_num_rows($query);
            
            if ($row_count > 0) {
                $result = mysqli_fetch_assoc($query);
                $checkPassword = $result['password'];
                
                if ($password == $checkPassword) {
                    $_SESSION['admin_id'] = $result['id'];
                    $_SESSION['admin_name'] = $result['name'];
                    $_SESSION['admin_img'] = $result['image'];
                    $_SESSION['admin_email'] = $result['email'];
                    
                    header("location: ./dashboad.php");
                    exit();
                } else {
                    $error = "Invalid password!";
                }
            } else {
                $error = "Email not found. Please enter a valid email.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In - Fruitables</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/CSS/admin_auth.css">
</head>

<body>
    <div class="container">
        <h2><i class="fa-solid fa-user-shield"></i> Admin Sign In</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="post" class="form" id="signinForm">
            <div class="input-box">
                <label for="email"><i class="fa-solid fa-envelope"></i> Email Address</label>
                <input type="email" id="email" placeholder="Enter your email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="input-box">
                <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
                <input type="password" id="password" placeholder="Enter your password" name="password" required>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            
            <button type="submit" name="submit" class="signin-btn" id="signinBtn">
                <i class="fa-solid fa-sign-in-alt"></i> Sign In
            </button>
            
            <p>Don't have an account? <a href="./signup.php" class="signup-link">Sign Up</a></p>
            
            <div class="forgot-password">
                <a href="#" onclick="alert('Contact administrator to reset your password')">Forgot Password?</a>
            </div>
        </form>
        
        <div class="text-center mt-4">
            <a href="../index.php" style="color: #667eea; text-decoration: none; font-size: 0.9rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Website
            </a>
        </div>
    </div>

    <script src="../../assets/Laibraries/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation and enhancement
        document.getElementById('signinForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const submitBtn = document.getElementById('signinBtn');
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return;
            }
            
            // Show loading state
            // submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Signing In...';
            // submitBtn.disabled = true;
        });
        
        // Email validation
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#dc3545';
                this.style.backgroundColor = '#fff5f5';
            } else {
                this.style.borderColor = '#e1e5e9';
                this.style.backgroundColor = '#f8f9fa';
            }
        });
        
        // Password visibility toggle
        const passwordField = document.getElementById('password');
        const passwordLabel = passwordField.previousElementSibling;
        
        passwordLabel.innerHTML += ' <i class="fa-solid fa-eye-slash toggle-password" style="cursor: pointer; color: #999;"></i>';
        
        document.querySelector('.toggle-password').addEventListener('click', function() {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.className = 'fa-solid fa-eye toggle-password';
            } else {
                passwordField.type = 'password';
                this.className = 'fa-solid fa-eye-slash toggle-password';
            }
        });
    </script>
</body>

</html>