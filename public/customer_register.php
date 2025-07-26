<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ./index.php");
    exit();
}

include 'conn.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $country = trim($_POST['country'] ?? 'Pakistan');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $company_name = trim($_POST['company_name'] ?? '');
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all required fields";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } else {
        // Check if email already exists
        $check_email = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_email);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $error = "Email already exists. Please use a different email or login.";
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_user = "INSERT INTO users (first_name, last_name, email, password, phone, address, city, country, postal_code, company_name) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $insert_user);
            mysqli_stmt_bind_param($stmt, "ssssssssss", $first_name, $last_name, $email, $hashed_password, $phone, $address, $city, $country, $postal_code, $company_name);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
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
    <title>Customer Registration - Fruitables</title>
    <link rel="stylesheet" href="assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .registration-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 50px auto;
            max-width: 800px;
        }
        .registration-header {
            background: linear-gradient(135deg, #FFD43B 0%, #FFA500 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .registration-form {
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
        .btn-register {
            background: linear-gradient(135deg, #FFD43B 0%, #FFA500 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 212, 59, 0.4);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .required {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="registration-container">
            <div class="registration-header">
                <h1><i class="fas fa-user-plus"></i> Customer Registration</h1>
                <p>Join Fruitables and enjoy fresh products delivered to your door!</p>
            </div>
            
            <div class="registration-form">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                        <br><a href="./customer_login.php" class="alert-link">Click here to login</a>
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="registrationForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name <span class="required">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name <span class="required">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password <span class="required">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="form-text text-muted">Minimum 6 characters</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" 
                               value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>" 
                               placeholder="House Number Street Name">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control" id="country" name="country" 
                                       value="<?php echo htmlspecialchars($_POST['country'] ?? 'Pakistan'); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="postal_code">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                       value="<?php echo htmlspecialchars($_POST['postal_code'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_name">Company Name (Optional)</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" 
                                       value="<?php echo htmlspecialchars($_POST['company_name'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-register btn-lg w-100">
                            <i class="fas fa-user-plus"></i> Create Account
                        </button>
                    </div>
                </form>
                
                <div class="login-link">
                    <p>Already have an account? <a href="./customer_login.php" class="text-primary">Login here</a></p>
                    <p><a href="./index.php" class="text-muted"><i class="fas fa-arrow-left"></i> Back to Home</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/Laibraries/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            // Password confirmation validation
            confirmPassword.addEventListener('input', function() {
                if (password.value !== this.value) {
                    this.setCustomValidity('Passwords do not match');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            password.addEventListener('input', function() {
                if (confirmPassword.value && this.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            });
            
            // Phone number formatting
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    if (value.length <= 3) {
                        value = value;
                    } else if (value.length <= 6) {
                        value = value.slice(0, 3) + '-' + value.slice(3);
                    } else {
                        value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
                    }
                }
                e.target.value = value;
            });
        });
    </script>
</body>
</html> 