<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ./index.php");
    exit();
}

include 'conn.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password";
    } else {
        // Check user credentials
        $check_user = "SELECT id, first_name, last_name, email, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_user);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['first_name'] . ' ' . $row['last_name'];
                $_SESSION['user_email'] = $row['email'];
                
                // Redirect to home page or checkout if cart has items
                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    header("Location: ./checkout.php");
                } else {
                    header("Location: ./index.php");
                }
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - Fruitables</title>
    <link rel="stylesheet" href="assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 50px auto;
            max-width: 500px;
        }
        .login-header {
            background: linear-gradient(135deg, #FFD43B 0%, #FFA500 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-form {
            padding: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #FFD43B;
            box-shadow: 0 0 0 0.2rem rgba(255, 212, 59, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #FFD43B 0%, #FFA500 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 212, 59, 0.4);
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .input-group-text {
            background: transparent;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        .input-group .form-control:focus {
            border-left: none;
        }
        .input-group:focus-within .input-group-text {
            border-color: #FFD43B;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h1><i class="fas fa-sign-in-alt"></i> Customer Login</h1>
                <p>Welcome back! Please login to your account</p>
            </div>
            
            <div class="login-form">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" id="loginForm">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-login btn-lg w-100">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </div>
                </form>
                
                <div class="register-link">
                    <p>Don't have an account? <a href="./customer_register.php" class="text-primary">Register here</a></p>
                    <p><a href="./index.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Home</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/Laibraries/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            
            // Form validation
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();
                
                if (!email || !password) {
                    e.preventDefault();
                    alert('Please fill in all fields');
                    return false;
                }
                
                // Basic email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Please enter a valid email address');
                    return false;
                }
            });
        });
    </script>
</body>
</html> 