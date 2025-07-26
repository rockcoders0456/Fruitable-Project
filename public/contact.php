<?php
session_start();

$success_message = '';
$error_message = '';

// Get user's approximate location by IP (core PHP)
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

$ip = getUserIP();
$geo = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
$latitude = ($geo && $geo->status === 'success') ? $geo->lat : 30.843854815497036; // fallback to store
$longitude = ($geo && $geo->status === 'success') ? $geo->lon : 73.03951823702808;

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        $error_message = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Include database connection
        include 'conn.php';
        
        // Insert message into messages table
        $insert_message = "INSERT INTO messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $insert_message);
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
        
        if (mysqli_stmt_execute($stmt)) {
            $message_id = mysqli_insert_id($conn);
            
            // Create notification for admin
            include 'admin/Admin/notification_helper.php';
            $notification_result = notifyAdminOfContactMessage($conn, $name, $email, $message);
            
            if ($notification_result) {
                $success_message = "Thank you for your message! We'll get back to you soon.";
            } else {
                $success_message = "Message sent successfully! (Notification creation failed)";
            }
            
            // Clear form data
            $name = $email = $message = '';
        } else {
            $error_message = "Sorry, there was an error sending your message. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/Css/contact.css">
</head>

<body>
    <div class="container nv">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6 location">
                        <i class="fa-solid fa-location-dot" style="color: #FFD43B;"></i> 123 Main Street, New York
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <i class="fa-solid fa-envelope" style="color: #FFD43B;"></i> Email@example.com
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 policy">
                <p>Privacy Policy / Terms of Use / Sales and refunds</p>
            </div>
        </div>
    </div>
    <header>
        <div class="container my-5">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 logo">
                    <h1>Fruitables</h1>
                </div>
                <div class="col-lg-7 col-md-12 col-sm-12 text-center list">
                    <ul>
                        <li><a href="./index.php">Home</a></li>
                        <li><a href="">Shop</a></li>
                        <li><a href="./shop_details.php">Shop Details</a></li>
                        <li><a href="./index.php#service">Our Services</a></li>
                        <li><a href="https://wa.me/923484693403?text=Assalamualaikum" target="_blank">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12 icon">
                    <i class="fa-solid fa-magnifying-glass srch"></i>
                    <a href="./add_to_cart.php" style="text-decoration: none; color: inherit;">
                        <i class="fa-solid fa-bag-shopping bag">
                            <span class="position-absolute translate-middle badge">
                                <span class="badge num">
                                    <?= count($_SESSION['cart'] ?? []) ?>
                                </span>
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        </i>
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown d-inline-block">
                            <a href="#" class="text-decoration-none text-dark" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user"></i>
                                <span class="ms-1"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="./customer_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="./customer_login.php" class="text-decoration-none text-dark">
                            <i class="fa-solid fa-user"></i>
                            <span class="ms-1">Login</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <main>
        <section id="hero" class="hero mb-5">
            <div class="container_fluid">
                <div class="row">
                    <div class="col">
                        <h1 class="text-center text-light">Contact</h1>
                        <div class="crumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section id="in_touch">
            <div class="container in_touch">
                <div class="row">
                    <div class="col-12">
                        <h1>Get in touch</h1>
                        <p class="ps-5 pe-s">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Molestiae,
                            tempore praesentium
                            aperiam consequuntur suscipit a eligendi ad vitae doloribus! Impedit. Lorem ipsum dolor, sit
                            amet consectetur adipisicing elit. Expedita distinctio exercitationem vero, corrupti quis
                            numquam dolores reiciendis officia repellendus temporibus?</p>
                        <span>Download Now</span>
                        <div class="map">
                            <iframe width="600" height="450" style="border:0" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?php echo $latitude; ?>,<?php echo $longitude; ?>&z=15&output=embed"></iframe>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7 col-md-6 col-sm-12">
                        <div class="form">
                            <?php if ($success_message): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($error_message): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <form action="" method="POST">
                                <input type="text" name="name" placeholder="Your Name" required>
                                <input type="email" name="email" placeholder="Enter Your email" required>
                                <textarea name="message" id="" cols="30" rows="5" placeholder="Your Message" required></textarea>
                                <button type="submit">Send Message</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <div class="contact-info">
                            <h3>Contact Information</h3>
                            <div class="info-item">
                                <i class="fa-solid fa-location-dot"></i>
                                <div>
                                    <h4>Address</h4>
                                    <p>123 Main Street, New York, NY 10001</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fa-solid fa-phone"></i>
                                <div>
                                    <h4>Phone</h4>
                                    <p>+1 (555) 123-4567</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fa-solid fa-envelope"></i>
                                <div>
                                    <h4>Email</h4>
                                    <p>info@fruitables.com</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fa-solid fa-clock"></i>
                                <div>
                                    <h4>Business Hours</h4>
                                    <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                                    Saturday: 10:00 AM - 4:00 PM<br>
                                    Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <footer>
        <div class="container-fluid main-footer">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12 text">
                    <h3>Fruitables</h3>
                    <p>Fresh Products</p>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <form action="">
                        <div class="my-5 main-form">
                            <input type="search" class="" placeholder="Search">
                            <div class="form-button">
                                <button class="btn " value="Submit">Submit Now
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 my-3 footer-icon">
                    <i class="fa-brands fa-twitter"></i>
                    <i class="fa-brands fa-facebook-f"></i>
                    <i class="fa-brands fa-youtube"></i>
                    <i class="fa-brands fa-linkedin-in"></i>
                </div>
            </div>
            <hr>
            <div class="list my-3">
                <div class="row text-center">
                    <div class="col-lg-3 col-md-6 col-sm-12 text-start">
                        <ul>
                            <li>
                                <h3>Why People Like Us!</h3>
                            </li>
                            <li>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis sed consequatur
                                    dolorem
                                    ipsa nisi sint ipsum commodi vero adipisci doloremque!</p>
                            </li>
                            <li>
                                <a href="#" class="btn-outline-warning">Read More</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 text-start">
                        <ul>
                            <li>
                                <h3>Shop Info</h3>
                            </li>
                            <li>About us</li>
                            <li>Contact Us</li>
                            <li>Privacy Policy</li>
                            <li>Terms & Condtions</li>
                            <li>Return Policy</li>
                            <li>FAQs & Help</li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 text-start">
                        <ul>
                            <li>
                                <h3>Account</h3>
                            </li>
                            <li>My Account</li>
                            <li>Shop Details</li>
                            <li>Shopping Card</li>
                            <li>Wishlist</li>
                            <li>Order History</li>
                            <li>International Orders</li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 text-start">
                        <ul>
                            <li>
                                <h3>Contact</h3>
                            </li>
                            <li>Address: Garh Fath Shah <br> Fsd.Rd.</li>
                            <li>Email:silentboy01352 <br> @gamil.com</li>
                            <li>Phone: +923470231352</li>
                            <li>Payment Accepted</li>
                            <li><img src="./assets/img/payment.png" class="img-fluid" alt=""></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row line">
                <div class="col-lg-6 col-md-12 col-sm-12 text my-2">
                    <i class="fa-solid fa-copyright" style="color: #ffffff;"></i> <span>Your Site Name</span>, All
                    right reserved.
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 text my-2">
                    Designed By <span>ROCK JUTT</span> Distributed By <span> ThemeWagon</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/Laibraries/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>