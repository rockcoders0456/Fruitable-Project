<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if already logged in
if (isset($_SESSION['admin_name'])) {
    header("Location: ./dashboad.php");
    exit();
}

include '../conn.php';

$error = '';
$success = '';

// Debug: Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<!-- Debug: Form submitted via POST -->";
    
    if (isset($_POST['submit'])) {
        echo "<!-- Debug: Submit button was clicked -->";
        
        $username = trim($_POST['userName']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $created_at = date('Y-m-d H:i:s');
        
        // Debug: Print form data
        echo "<!-- Debug: Username: $username, Email: $email -->";
        echo "<!-- Debug: Password length: " . strlen($password) . " -->";
        echo "<!-- Debug: Confirm password length: " . strlen($confirm_password) . " -->";
        
        // Validation
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $error = "Please fill in all fields";
            echo "<!-- Debug: Validation failed - empty fields -->";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match";
            echo "<!-- Debug: Validation failed - passwords don't match -->";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters long";
            echo "<!-- Debug: Validation failed - password too short -->";
        } else {
            echo "<!-- Debug: Validation passed, checking email existence -->";
            
            // Check if email already exists
            $check_email = "SELECT id FROM admin_users WHERE email = '$email'";
            echo "<!-- Debug: Email check query: $check_email -->";
            
            $email_query = mysqli_query($conn, $check_email);
            
            if (!$email_query) {
                $error = "Database error: " . mysqli_error($conn);
                echo "<!-- Debug: Email check query failed: " . mysqli_error($conn) . " -->";
            } else {
                $email_row = mysqli_num_rows($email_query);
                echo "<!-- Debug: Email check result: $email_row rows found -->";
                
                if ($email_row > 0) {
                    $error = "Email already exists. Please use a different email.";
                    echo "<!-- Debug: Email already exists -->";
                } else {
                    echo "<!-- Debug: Email is unique, processing file upload -->";
                    
                    // Handle file upload
                    $img = 'default.jpg'; // Default image
                    
                    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                        echo "<!-- Debug: File upload detected -->";
                        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                        $file_type = $_FILES['img']['type'];
                        
                        if (in_array($file_type, $allowed_types)) {
                            $img = $_FILES['img']['name'];
                            $tmpImg = $_FILES['img']['tmp_name'];
                            $newImg = rand(1111, 9999) . '_' . $img;
                            $target = '../../assets/Admin_img/';
                            
                            // Create directory if it doesn't exist
                            if (!is_dir($target)) {
                                mkdir($target, 0777, true);
                                echo "<!-- Debug: Created directory: $target -->";
                            }
                            
                            if (move_uploaded_file($tmpImg, $target . $newImg)) {
                                $img = $newImg;
                                echo "<!-- Debug: File uploaded successfully: $img -->";
                            } else {
                                $error = "Image upload failed.";
                                echo "<!-- Debug: File upload failed -->";
                            }
                        } else {
                            $error = "Please upload a valid image file (JPEG, PNG, GIF)";
                            echo "<!-- Debug: Invalid file type: $file_type -->";
                        }
                    } else {
                        echo "<!-- Debug: No file upload or upload error: " . ($_FILES['img']['error'] ?? 'no file') . " -->";
                    }
                    
                    if (empty($error)) {
                        echo "<!-- Debug: No errors, attempting database insertion -->";
                        
                        // Insert new admin user
                        $insert = "INSERT INTO admin_users (name, email, password, image, created_at) VALUES ('$username', '$email', '$password', '$img', '$created_at')";
                        
                        // Debug: Print the SQL query
                        echo "<!-- Debug: SQL Query: $insert -->";
                        
                        $insert_query = mysqli_query($conn, $insert);
                        
                        if ($insert_query) {
                            $insert_id = mysqli_insert_id($conn);
                            $success = "Account created successfully! You can now sign in.";
                            echo "<!-- Debug: Insert successful! ID: $insert_id -->";
                            // Clear form data
                            $_POST = array();
                        } else {
                            $error = "Error in account creation: " . mysqli_error($conn);
                            // Debug: Print detailed error
                            echo "<!-- Debug: MySQL Error: " . mysqli_error($conn) . " -->";
                            echo "<!-- Debug: MySQL Errno: " . mysqli_errno($conn) . " -->";
                            echo "<!-- Debug: Insert query failed -->";
                        }
                    } else {
                        echo "<!-- Debug: Error occurred before insertion: $error -->";
                    }
                }
            }
        }
    } else {
        echo "<!-- Debug: Form submitted but submit button not found -->";
        echo "<!-- Debug: POST data: " . print_r($_POST, true) . " -->";
    }
} else {
    echo "<!-- Debug: Page loaded via GET request -->";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up - Fruitables</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/CSS/admin_auth.css">
</head>

<body>
    <div class="container">
        <h2><i class="fa-solid fa-user-plus"></i> Admin Sign Up</h2>
        
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
        
        <form action="" method="post" enctype="multipart/form-data" class="form" id="signupForm">
            <div class="input-box">
                <label for="userName"><i class="fa-solid fa-user"></i> Username</label>
                <input type="text" id="userName" name="userName" placeholder="Enter your username" required 
                       value="<?php echo isset($_POST['userName']) ? htmlspecialchars($_POST['userName']) : ''; ?>">
            </div>
            
            <div class="input-box">
                <label for="email"><i class="fa-solid fa-envelope"></i> Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="input-box">
                <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <div class="password-strength">
                    <div class="password-strength-bar" id="passwordStrength"></div>
                </div>
            </div>
            
            <div class="input-box">
                <label for="confirm_password"><i class="fa-solid fa-lock"></i> Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            
            <div class="input-box">
                <label for="img"><i class="fa-solid fa-image"></i> Profile Image (Optional)</label>
                <input type="file" id="img" name="img" accept="image/*">
                <small style="color: #666; font-size: 0.8rem;">Accepted formats: JPEG, PNG, GIF</small>
            </div>
            
            <button type="submit" name="submit" class="signup-btn" id="signupBtn">
                <i class="fa-solid fa-user-plus"></i> Create Account
            </button>
            
            <p>Already have an account? <a href="./signin.php" class="signin-link">Sign In</a></p>
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
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const username = document.getElementById('userName').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const submitBtn = document.getElementById('signupBtn');
            
            console.log('Form submission detected');
            console.log('Username:', username);
            console.log('Email:', email);
            console.log('Password length:', password.length);
            console.log('Confirm password length:', confirmPassword.length);
            
            if (!username || !email || !password || !confirmPassword) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long');
                return;
            }
            
            console.log('Form validation passed, submitting...');
            
            // // Show loading state
            // submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creating Account...';
            // submitBtn.disabled = true;
        });
        
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            if (strength <= 2) {
                strengthBar.classList.add('weak');
            } else if (strength <= 3) {
                strengthBar.classList.add('medium');
            } else {
                strengthBar.classList.add('strong');
            }
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
        const passwordFields = ['password', 'confirm_password'];
        passwordFields.forEach(fieldId => {
            const passwordField = document.getElementById(fieldId);
            const passwordLabel = passwordField.previousElementSibling;
            
            passwordLabel.innerHTML += ' <i class="fa-solid fa-eye-slash toggle-password" style="cursor: pointer; color: #999;"></i>';
            
            passwordLabel.querySelector('.toggle-password').addEventListener('click', function() {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    this.className = 'fa-solid fa-eye toggle-password';
                } else {
                    passwordField.type = 'password';
                    this.className = 'fa-solid fa-eye-slash toggle-password';
                }
            });
        });
        
        // File input styling
        document.getElementById('img').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // MB
                if (fileSize > 5) {
                    alert('File size should be less than 5MB');
                    this.value = '';
                }
            }
        });
    </script>
</body>

</html>