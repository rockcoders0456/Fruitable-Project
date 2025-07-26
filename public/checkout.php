<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php
session_start();

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: add_to_cart.php');
    exit();
}

// Get cart items from database
$cart_items = [];
$total = 0;

include 'conn.php';
include 'admin/Admin/notification_helper.php';
include 'admin/Admin/simple_email.php';

// Debug: Show current database and columns
$db_result = mysqli_query($conn, "SELECT DATABASE() as db");
$db_row = mysqli_fetch_assoc($db_result);
echo '<!-- Current DB: ' . htmlspecialchars($db_row['db']) . ' -->';

$debug_columns_result = mysqli_query($conn, "SHOW COLUMNS FROM orders");
echo '<!-- Columns: ';
while ($col = mysqli_fetch_assoc($debug_columns_result)) {
    echo htmlspecialchars($col['Field']) . ', ';
}
echo ' -->';

// Get user information if logged in
$user_info = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $user_query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $user_result = mysqli_stmt_get_result($stmt);
    $user_info = mysqli_fetch_assoc($user_result);
}

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $select = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($conn, $select);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $quantity;
        $row['total'] = $row['price'] * $quantity;
        $cart_items[] = $row;
        $total += $row['total'];
    }
}

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fName = trim($_POST['fName'] ?? '');
    $lName = trim($_POST['lName'] ?? '');
    $companyName = trim($_POST['companyName'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $country = trim($_POST['country'] ?? 'Pakistan');
    $postalcode = trim($_POST['postalcode'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';

    // Validate required fields
    if (empty($fName) || empty($lName) || empty($city) || empty($postalcode) || empty($email) || empty($mobile) || empty($payment_method)) {
        $error = "Please fill all required fields and select a payment method";
    } else {
        // Validate Pakistani phone number
        if (!preg_match('/^(03[0-9]{2}-[0-9]{7}|\+92-3[0-9]{2}-[0-9]{7})$/', $mobile)) {
            $error = "Please enter a valid Pakistani phone number (03XX-XXXXXXX or +92-3XX-XXXXXXX)";
        } else {
            // Validate payment method specific details
            $payment_details_error = "";

            if ($payment_method === 'Direct Bank Transfer') {
                $bank_account_name = trim($_POST['bank_account_name'] ?? '');
                $bank_account_number = trim($_POST['bank_account_number'] ?? '');
                $bank_name = trim($_POST['bank_name'] ?? '');

                if (empty($bank_account_name) || empty($bank_account_number) || empty($bank_name)) {
                    $payment_details_error = "Please fill in all bank transfer details (Account Holder Name, Account Number, Bank Name)";
                }
            } elseif ($payment_method === 'Check Payment') {
                $check_number = trim($_POST['check_number'] ?? '');
                $check_bank_name = trim($_POST['check_bank_name'] ?? '');

                if (empty($check_number) || empty($check_bank_name)) {
                    $payment_details_error = "Please fill in all check payment details (Check Number, Bank Name)";
                }
            } elseif ($payment_method === 'PayPal') {
                $paypal_email = trim($_POST['paypal_email'] ?? '');
                $paypal_card_number = trim($_POST['paypal_card_number'] ?? '');

                if (empty($paypal_email) && empty($paypal_card_number)) {
                    $payment_details_error = "Please provide either PayPal email or card number";
                }

                if (!empty($paypal_email) && !filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) {
                    $payment_details_error = "Please enter a valid PayPal email address";
                }
            }

            if (!empty($payment_details_error)) {
                $error = $payment_details_error;
            } else {
                // Calculate shipping cost
                $shipping_cost = 3.00; // Fixed shipping cost
                $total_amount = $total + $shipping_cost;

                // Insert order into database
                $order_date = date('Y-m-d H:i:s');
                $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

                // Prepare payment details
                $bank_account_name = trim($_POST['bank_account_name'] ?? '');
                $bank_account_number = trim($_POST['bank_account_number'] ?? '');
                $bank_name = trim($_POST['bank_name'] ?? '');
                $check_number = trim($_POST['check_number'] ?? '');
                $check_bank_name = trim($_POST['check_bank_name'] ?? '');
                $paypal_email = trim($_POST['paypal_email'] ?? '');
                $paypal_card_number = trim($_POST['paypal_card_number'] ?? '');

                // Check if payment method columns exist in the database
                $check_columns = "SHOW COLUMNS FROM orders LIKE 'payment_method'";
                $columns_result = mysqli_query($conn, $check_columns);
                $has_payment_columns = mysqli_num_rows($columns_result) > 0;



                if ($has_payment_columns) {
                    // Full insert with payment method columns
                    $insert_order = "INSERT INTO orders (first_name, last_name, email, phone, address, city, country, postal_code, company_name, notes, total_amount, shipping_cost, status, order_date, payment_method, user_id, bank_account_name, bank_account_number, bank_name, check_number, check_bank_name, paypal_email, paypal_card_number) 
                            VALUES ('$fName', '$lName', '$email', '$mobile', '$address', '$city', '$country', '$postalcode', '$companyName', '$notes', $total_amount, $shipping_cost, 'pending', '$order_date', '$payment_method', " . ($user_id ? $user_id : 'NULL') . ", " .
                        ($bank_account_name ? "'$bank_account_name'" : 'NULL') . ", " .
                        ($bank_account_number ? "'$bank_account_number'" : 'NULL') . ", " .
                        ($bank_name ? "'$bank_name'" : 'NULL') . ", " .
                        ($check_number ? "'$check_number'" : 'NULL') . ", " .
                        ($check_bank_name ? "'$check_bank_name'" : 'NULL') . ", " .
                        ($paypal_email ? "'$paypal_email'" : 'NULL') . ", " .
                        ($paypal_card_number ? "'$paypal_card_number'" : 'NULL') . ")";
                } else {
                    // Basic insert without payment method columns (fallback)
                    $insert_order = "INSERT INTO orders (first_name, last_name, email, phone, address, city, country, postal_code, company_name, notes, total_amount, shipping_cost, status, order_date) 
                            VALUES ('$fName', '$lName', '$email', '$mobile', '$address', '$city', '$country', '$postalcode', '$companyName', '$notes', $total_amount, $shipping_cost, 'pending', '$order_date')";
                }

                $order_result = mysqli_query($conn, $insert_order);

                if ($order_result) {
                    $order_id = mysqli_insert_id($conn);

                    // Insert order items
                    $items_success = true;
                    foreach ($cart_items as $item) {
                        $item_total = $item['price'] * $item['quantity'];
                        $insert_item = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, total) 
                               VALUES ($order_id, {$item['id']}, '{$item['tittle']}', {$item['quantity']}, {$item['price']}, $item_total)";

                        if (!mysqli_query($conn, $insert_item)) {
                            $items_success = false;
                            break;
                        }
                    }

                    if ($items_success) {
                        // Create notification for admin
                        $customer_name = $fName . ' ' . $lName;
                        $notification_title = "New Order #$order_id";
                        $notification_message = "New order received from $customer_name for $" . number_format($total_amount, 2);
                        createNotification($conn, 'order', $notification_title, $notification_message, $order_id, $user_id ?? null);

                        // Send confirmation email to customer
                        $emailSent = sendOrderConfirmationEmail($email, $customer_name, $order_id);
                        
                        // Log email status
                        if (!$emailSent) {
                            error_log("Failed to send order confirmation email to: $email for order #$order_id");
                        }

                        // Clear cart and show success
                        unset($_SESSION['cart']);
                        $success = "Order placed successfully! Your order ID is #$order_id. " . 
                                 ($emailSent ? "A confirmation email has been sent to $email." : "Thank you for your purchase.");
                    } else {
                        // Delete the order if items failed to insert
                        mysqli_query($conn, "DELETE FROM orders WHERE id = $order_id");
                        $error = "Error processing order items. Please try again.";
                    }
                } else {
                    $error_message = mysqli_error($conn);
                    if (strpos($error_message, "Unknown column") !== false) {
                        $error = "Database needs to be updated. Please run <a href='update_database.php' style='color: #007bff; text-decoration: underline;'>update_database.php</a> first, then try again.";
                    } else {
                        $error = "Error placing order: " . $error_message;
                    }
                }
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
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/Css/checkout.css">
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

    <section id="hero" class="hero mb-5">
        <div class="container_fluid">
            <div class="row">
                <div class="col">
                    <h1 class="text-center text-light">Checkout</h1>
                    <div class="crumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="./add_to_cart.php">Cart</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (isset($success)): ?>
        <div class="container">
            <div class="alert alert-success text-center">
                <h4><?php echo $success; ?></h4>
                <a href="./index.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
    <?php else: ?>
        <section id="billing">
            <div class="container billing">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-lg-7 col-md-6 col-sm-12">
                                <div class="form">
                                    <h1>Billing details</h1>
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php endif; ?>
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <?php if ($user_info): ?>
                                            <div class="alert alert-info">
                                                <i class="fas fa-user"></i> Welcome back, <?php echo htmlspecialchars($user_info['first_name']); ?>! Your information has been auto-filled.
                                            </div>
                                        <?php endif; ?>

                                        <div class="half">
                                            <div class="frist">
                                                <label for="">First Name*</label>
                                                <input type="text" name="fName" value="<?php echo htmlspecialchars($user_info['first_name'] ?? ''); ?>" required>
                                            </div>
                                            <div class="second">
                                                <label for="">Last Name*</label>
                                                <input type="text" name="lName" value="<?php echo htmlspecialchars($user_info['last_name'] ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <label for="">Company Name</label>
                                        <input type="text" name="companyName" value="<?php echo htmlspecialchars($user_info['company_name'] ?? ''); ?>">
                                        <label for="">Address</label>
                                        <input type="text" name="address" placeholder="House Number Street Name" value="<?php echo htmlspecialchars($user_info['address'] ?? ''); ?>">
                                        <label for="">Town/City*</label>
                                        <input type="text" name="city" value="<?php echo htmlspecialchars($user_info['city'] ?? ''); ?>" required>
                                        <label for="">Country</label>
                                        <input type="text" name="country" value="<?php echo htmlspecialchars($user_info['country'] ?? 'Pakistan'); ?>">
                                        <label for="">Postalcode/Zip*</label>
                                        <input type="number" name="postalcode" value="<?php echo htmlspecialchars($user_info['postal_code'] ?? ''); ?>" required>
                                        <label for="">Email Address*</label>
                                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_info['email'] ?? ''); ?>" required>
                                        <label for="">Mobile*</label>
                                        <input
                                            type="tel"
                                            name="mobile"
                                            class="form-control"
                                            placeholder="03XX-XXXXXXX or +92-3XX-XXXXXXX"
                                            pattern="^(03[0-9]{2}-[0-9]{7}|\+92-3[0-9]{2}-[0-9]{7})$"
                                            maxlength="14"
                                            required
                                            title="Enter a valid Pakistani phone number (03XX-XXXXXXX or +92-3XX-XXXXXXX)"
                                            value="<?php echo htmlspecialchars($user_info['phone'] ?? ''); ?>">
                                        <label class="mt-3 mb-1"><strong>Payment Method*</strong></label>
                                        <div class="method">
                                            <h5 class="mb-3">Select Payment Method</h5>
                                            <div class="payment-methods">
                                                <div class="payment-method-item" onclick="selectPaymentMethod('bank_transfer')">
                                                    <input type="radio" name="payment_method" id="bank_transfer" value="Direct Bank Transfer" style="display: none;">
                                                    <label for="bank_transfer" class="payment-label" onclick="event.preventDefault(); selectPaymentMethod('bank_transfer');">
                                                        <div class="payment-icon">
                                                            <i class="fa-solid fa-university"></i>
                                                        </div>
                                                        <div class="payment-details">
                                                            <h6>Direct Bank Transfer</h6>
                                                            <p class="text-muted mb-0">Transfer money directly to our bank account</p>
                                                        </div>
                                                        <div class="payment-check">
                                                            <i class="fa-solid fa-check"></i>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="payment-details-extra" id="bank_transfer_details" style="display:none; margin-bottom: 15px;">
                                                    <div class="row g-2">
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="bank_account_name" placeholder="Account Holder Name">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="bank_account_number" placeholder="Account Number">
                                                        </div>
                                                        <div class="col-md-12 mt-2">
                                                            <input type="text" class="form-control" name="bank_name" placeholder="Bank Name">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="payment-method-item" onclick="selectPaymentMethod('check_payment')">
                                                    <input type="radio" name="payment_method" id="check_payment" value="Check Payment" style="display: none;">
                                                    <label for="check_payment" class="payment-label" onclick="event.preventDefault(); selectPaymentMethod('check_payment');">
                                                        <div class="payment-icon">
                                                            <i class="fa-solid fa-money-check-dollar"></i>
                                                        </div>
                                                        <div class="payment-details">
                                                            <h6>Check Payment</h6>
                                                            <p class="text-muted mb-0">Pay with a personal or business check</p>
                                                        </div>
                                                        <div class="payment-check">
                                                            <i class="fa-solid fa-check"></i>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="payment-details-extra" id="check_payment_details" style="display:none; margin-bottom: 15px;">
                                                    <div class="row g-2">
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="check_number" placeholder="Check Number">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="check_bank_name" placeholder="Bank Name">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="payment-method-item" onclick="selectPaymentMethod('cash_on_delivery')">
                                                    <input type="radio" name="payment_method" id="cash_on_delivery" value="Cash on Delivery" style="display: none;">
                                                    <label for="cash_on_delivery" class="payment-label" onclick="event.preventDefault(); selectPaymentMethod('cash_on_delivery');">
                                                        <div class="payment-icon">
                                                            <i class="fa-solid fa-money-bill-wave"></i>
                                                        </div>
                                                        <div class="payment-details">
                                                            <h6>Cash on Delivery</h6>
                                                            <p class="text-muted mb-0">Pay with cash when your order arrives</p>
                                                        </div>
                                                        <div class="payment-check">
                                                            <i class="fa-solid fa-check"></i>
                                                        </div>
                                                    </label>
                                                </div>
                                                <!-- No extra fields for Cash on Delivery -->

                                                <div class="payment-method-item" onclick="selectPaymentMethod('paypal')">
                                                    <input type="radio" name="payment_method" id="paypal" value="PayPal" style="display: none;">
                                                    <label for="paypal" class="payment-label" onclick="event.preventDefault(); selectPaymentMethod('paypal');">
                                                        <div class="payment-icon">
                                                            <i class="fa-brands fa-paypal"></i>
                                                        </div>
                                                        <div class="payment-details">
                                                            <h6>PayPal</h6>
                                                            <p class="text-muted mb-0">Pay securely with your PayPal account or debit/credit card</p>
                                                        </div>
                                                        <div class="payment-check">
                                                            <i class="fa-solid fa-check"></i>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="payment-details-extra" id="paypal_details" style="display:none; margin-bottom: 15px;">
                                                    <div class="row g-2">
                                                        <div class="col-md-6">
                                                            <input type="email" class="form-control" name="paypal_email" placeholder="PayPal Email">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="paypal_card_number" placeholder="Debit/Credit Card Number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <textarea name="notes" cols="30" rows="7"
                                            placeholder="Order Notes (Optional)"></textarea>
                                        <button type="submit">PLACE ORDER</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6 col-sm-12 order-summary">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Product</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <?php foreach ($cart_items as $item): ?>
                                            <tr>
                                                <td><img src="./assets/Product_Images/<?php echo $item['image']; ?>" alt="" style="width: 60px; height: 60px; object-fit: cover;"></td>
                                                <td><?php echo $item['tittle']; ?></td>
                                                <td>$<?php echo $item['price']; ?></td>
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td>$<?php echo number_format($item['total'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td><img src="./assets/img/white.png" alt=""></td>
                                            <td></td>
                                            <td></td>
                                            <td>Subtotal</td>
                                            <td>$<?php echo number_format($total, 2); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="shipping">
                                    <div class="text">
                                        <p>Shipping</p>
                                    </div>
                                    <div class="list">
                                        <ul>
                                            <li>Free Shipping</li>
                                            <li>Flat rate: $3.00</li>
                                            <li>Local Pickup: $8.00</li>
                                        </ul>
                                    </div>
                                </div>
                                <hr>
                                <div class="total">
                                    <div class="">
                                        <p>TOTAL</p>
                                    </div>
                                    <div class="">
                                        <hr>
                                        <p>$<?php echo number_format($total + 3, 2); ?></p>
                                        <hr>
                                    </div>
                                </div>

                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

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
    <script>
        // Form validation and enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');

            // Form validation
            form.addEventListener('submit', function(e) {
                const fName = form.querySelector('input[name="fName"]').value.trim();
                const lName = form.querySelector('input[name="lName"]').value.trim();
                const city = form.querySelector('input[name="city"]').value.trim();
                const postalcode = form.querySelector('input[name="postalcode"]').value.trim();
                const email = form.querySelector('input[name="email"]').value.trim();
                const mobile = form.querySelector('input[name="mobile"]').value.trim();
                const paymentMethod = form.querySelector('input[name="payment_method"]:checked');

                // Clear previous error styling
                clearErrors();

                let hasErrors = false;

                // Validate required fields
                if (!fName) {
                    showError('input[name="fName"]', 'First name is required');
                    hasErrors = true;
                }

                if (!lName) {
                    showError('input[name="lName"]', 'Last name is required');
                    hasErrors = true;
                }

                if (!city) {
                    showError('input[name="city"]', 'City is required');
                    hasErrors = true;
                }

                if (!postalcode) {
                    showError('input[name="postalcode"]', 'Postal code is required');
                    hasErrors = true;
                }

                if (!email) {
                    showError('input[name="email"]', 'Email is required');
                    hasErrors = true;
                } else if (!isValidEmail(email)) {
                    showError('input[name="email"]', 'Please enter a valid email address');
                    hasErrors = true;
                }

                if (!mobile) {
                    showError('input[name="mobile"]', 'Mobile number is required');
                    hasErrors = true;
                } else if (!isValidPhone(mobile)) {
                    showError('input[name="mobile"]', 'Please enter a valid phone number');
                    hasErrors = true;
                }

                if (!paymentMethod) {
                    showError('.payment-methods', 'Please select a payment method');
                    hasErrors = true;
                } else {
                    clearError('.payment-methods');
                    
                    // Validate payment method specific fields
                    const selectedMethod = paymentMethod.value;
                    
                    if (selectedMethod === 'Direct Bank Transfer') {
                        const bankAccountName = form.querySelector('input[name="bank_account_name"]').value.trim();
                        const bankAccountNumber = form.querySelector('input[name="bank_account_number"]').value.trim();
                        const bankName = form.querySelector('input[name="bank_name"]').value.trim();
                        
                        if (!bankAccountName) {
                            showError('input[name="bank_account_name"]', 'Account holder name is required');
                            hasErrors = true;
                        }
                        if (!bankAccountNumber) {
                            showError('input[name="bank_account_number"]', 'Account number is required');
                            hasErrors = true;
                        }
                        if (!bankName) {
                            showError('input[name="bank_name"]', 'Bank name is required');
                            hasErrors = true;
                        }
                    } else if (selectedMethod === 'Check Payment') {
                        const checkNumber = form.querySelector('input[name="check_number"]').value.trim();
                        const checkBankName = form.querySelector('input[name="check_bank_name"]').value.trim();
                        
                        if (!checkNumber) {
                            showError('input[name="check_number"]', 'Check number is required');
                            hasErrors = true;
                        }
                        if (!checkBankName) {
                            showError('input[name="check_bank_name"]', 'Bank name is required');
                            hasErrors = true;
                        }
                    } else if (selectedMethod === 'PayPal') {
                        const paypalEmail = form.querySelector('input[name="paypal_email"]').value.trim();
                        const paypalCardNumber = form.querySelector('input[name="paypal_card_number"]').value.trim();
                        
                        if (!paypalEmail && !paypalCardNumber) {
                            showError('input[name="paypal_email"]', 'Please provide either PayPal email or card number');
                            hasErrors = true;
                        }
                        
                        if (paypalEmail && !isValidEmail(paypalEmail)) {
                            showError('input[name="paypal_email"]', 'Please enter a valid PayPal email address');
                            hasErrors = true;
                        }
                    }
                }

                if (hasErrors) {
                    e.preventDefault();
                    return false;
                }
                // Prevent double submission
                form.style.pointerEvents = 'none';
            });

            // Email validation
            const emailInput = form.querySelector('input[name="email"]');
            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                if (email && !isValidEmail(email)) {
                    showError(this, 'Please enter a valid email address');
                } else {
                    clearError(this);
                }
            });

            // Phone validation
            const mobileInput = form.querySelector('input[name="mobile"]');
            mobileInput.addEventListener('blur', function() {
                const phone = this.value.trim();
                if (phone && !isValidPhone(phone)) {
                    showError(this, 'Please enter a valid phone number');
                } else {
                    clearError(this);
                }
            });

            // Real-time validation for required fields
            const requiredInputs = form.querySelectorAll('input[required]');
            requiredInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        showError(this, 'This field is required');
                    } else {
                        clearError(this);
                    }
                });

                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        clearError(this);
                    }
                });
            });
            
            // Real-time validation for payment-specific fields
            const paymentFields = form.querySelectorAll('input[name*="bank_"], input[name*="check_"], input[name*="paypal_"]');
            paymentFields.forEach(field => {
                field.addEventListener('blur', function() {
                    const selectedMethod = form.querySelector('input[name="payment_method"]:checked');
                    if (selectedMethod) {
                        validatePaymentField(this, selectedMethod.value);
                    }
                });
                
                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        clearError(this);
                    }
                });
            });
        });

        // Helper functions
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidPhone(phone) {
            // Remove spaces, dashes, parentheses
            phone = phone.replace(/[\s\-()]/g, '');

            // Pakistani numbers: +92XXXXXXXXXX or 03XXXXXXXXX
            const pkPattern = /^(\+92|0)?3[0-9]{9}$/;

            // International: + followed by 8-15 digits
            const intlPattern = /^\+[1-9][0-9]{7,14}$/;

            return pkPattern.test(phone) || intlPattern.test(phone);
        }

        function showError(element, message) {
            if (typeof element === 'string') {
                element = document.querySelector(element);
            }
            if (!element) return;
            element.style.borderColor = '#dc3545';
            element.style.backgroundColor = '#fff5f5';

            // Remove existing error message
            const existingError = element.parentNode.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }

            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-danger mt-1';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.innerHTML = '<i class="fa-solid fa-exclamation-circle"></i> ' + message;
            element.parentNode.appendChild(errorDiv);
        }

        function clearError(element) {
            if (typeof element === 'string') {
                element = document.querySelector(element);
            }
            if (!element) return;
            element.style.borderColor = '';
            element.style.backgroundColor = '';

            const errorMessage = element.parentNode.querySelector('.error-message');
            if (errorMessage) {
                errorMessage.remove();
            }
        }

        function clearErrors() {
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(error => error.remove());

            const inputs = document.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.style.borderColor = '';
                input.style.backgroundColor = '';
            });
        }
        
        function validatePaymentField(field, paymentMethod) {
            const fieldName = field.name;
            const value = field.value.trim();
            
            if (paymentMethod === 'Direct Bank Transfer') {
                if (fieldName === 'bank_account_name' && !value) {
                    showError(field, 'Account holder name is required');
                    return false;
                }
                if (fieldName === 'bank_account_number' && !value) {
                    showError(field, 'Account number is required');
                    return false;
                }
                if (fieldName === 'bank_name' && !value) {
                    showError(field, 'Bank name is required');
                    return false;
                }
            } else if (paymentMethod === 'Check Payment') {
                if (fieldName === 'check_number' && !value) {
                    showError(field, 'Check number is required');
                    return false;
                }
                if (fieldName === 'check_bank_name' && !value) {
                    showError(field, 'Bank name is required');
                    return false;
                }
            } else if (paymentMethod === 'PayPal') {
                if (fieldName === 'paypal_email' && value && !isValidEmail(value)) {
                    showError(field, 'Please enter a valid PayPal email address');
                    return false;
                }
            }
            
            clearError(field);
            return true;
        }



        // Confirm before leaving page if form has data
        let formModified = false;
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, textarea');

        inputs.forEach(input => {
            input.addEventListener('input', function() {
                formModified = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (formModified) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Reset form modified flag when form is submitted
        form.addEventListener('submit', function() {
            formModified = false;
        });

        // Payment method selection functionality
        function selectPaymentMethod(methodId) {
            // Remove active class from all payment methods
            document.querySelectorAll('.payment-method-item').forEach(item => {
                item.classList.remove('active');
            });

            // Add active class to selected method
            const selectedItem = document.querySelector(`[onclick="selectPaymentMethod('${methodId}')"]`);
            if (selectedItem) {
                selectedItem.classList.add('active');
            }

            // Check the radio button
            const radio = document.getElementById(methodId);
            if (radio) {
                radio.checked = true;
            }

            // Show/hide extra payment details
            document.querySelectorAll('.payment-details-extra').forEach(extra => {
                extra.style.display = 'none';
            });

            if (methodId === 'bank_transfer') {
                document.getElementById('bank_transfer_details').style.display = 'block';
            } else if (methodId === 'check_payment') {
                document.getElementById('check_payment_details').style.display = 'block';
            } else if (methodId === 'paypal') {
                document.getElementById('paypal_details').style.display = 'block';
            }
            
            // Clear any payment method errors when user selects a method
            clearError('.payment-methods');
        }
    </script>
        // Auto-format Pakistani phone number with dashes
        const phoneInput = document.querySelector('input[name="mobile"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d+]/g, '');
                // Format for +92-3XX-XXXXXXX
                if (value.startsWith('+92')) {
                    value = value.replace(/^(\+92)(3\d{0,2})(\d{0,7})$/, function(_, p0, p1, p2) {
                        let out = p0;
                        if (p1) out += '-' + p1;
                        if (p2) out += '-' + p2;
                        return out;
                    });
                }
                // Format for 03XX-XXXXXXX
                else if (value.startsWith('03')) {
                    value = value.replace(/^(03\d{0,2})(\d{0,7})$/, function(_, p1, p2) {
                        let out = p1;
                        if (p2) out += '-' + p2;
                        return out;
                    });
                }
                e.target.value = value;
            });
        }
        
        // Initialize payment method selection
        // Set default payment method (Cash on Delivery)
        selectPaymentMethod('cash_on_delivery');
        
        // Add loading state to form submission
        const submitButton = document.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.addEventListener('click', function() {
                if (this.disabled) return;
                
                // Add loading state
                const originalText = this.textContent;
                this.textContent = 'Processing...';
                this.disabled = true;
                
                // Re-enable after 5 seconds if form doesn't submit
                setTimeout(() => {
                    this.textContent = originalText;
                    this.disabled = false;
                }, 5000);
            });
        }
        
        // Add visual feedback when payment method is selected
        document.querySelectorAll('.payment-method-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove selected class from all items
                document.querySelectorAll('.payment-method-item').forEach(i => {
                    i.classList.remove('selected');
                });
                
                // Add selected class to clicked item
                this.classList.add('selected');
            });
        });
    <style>
        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .payment-method-item {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .payment-method-item:hover {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
        }

        .payment-method-item.active {
            border-color: #007bff;
            background-color: #f8f9ff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
        }

        .payment-label {
            display: flex;
            align-items: center;
            padding: 15px;
            margin: 0;
            cursor: pointer;
            width: 100%;
        }

        .payment-icon {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
            color: #6c757d;
        }

        .payment-method-item.active .payment-icon {
            background: #007bff;
            color: white;
        }

        .payment-details {
            flex: 1;
        }

        .payment-details h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
            color: #333;
        }

        .payment-details p {
            margin: 0;
            font-size: 0.875rem;
        }

        .payment-check {
            width: 30px;
            height: 30px;
            border: 2px solid #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: transparent;
            transition: all 0.3s ease;
        }

        .payment-method-item.active .payment-check {
            border-color: #007bff;
            background: #007bff;
            color: white;
        }

        .payment-check i {
            font-size: 12px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .payment-label {
                padding: 12px;
            }

            .payment-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
                margin-right: 12px;
            }

            .payment-details h6 {
                font-size: 0.9rem;
            }

            .payment-details p {
                font-size: 0.8rem;
            }
        }
        
        /* Payment method improvements */
        .payment-method-item {
            position: relative;
        }
        
        .payment-method-item input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        
        .payment-details-extra {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }
        
        .payment-details-extra input {
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        .payment-details-extra input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        /* Error styling for payment fields */
        .payment-details-extra input.error {
            border-color: #dc3545;
            background-color: #fff5f5;
        }
        
        /* Success state for payment methods */
        .payment-method-item.selected {
            border-color: #28a745;
            background-color: #f8fff9;
        }
        
        .payment-method-item.selected .payment-icon {
            background: #28a745;
            color: white;
        }
        
        .payment-method-item.selected .payment-check {
            border-color: #28a745;
            background: #28a745;
            color: white;
        }
    </style>
</body>

</html>