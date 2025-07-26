<?php
session_start();

// Handle adding products to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['id'] ?? '';
    $quantity = $_POST['quantity'] ?? 1;
    
    if ($product_id && $quantity > 0) {
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Add or update product in cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        
        echo "success";
        exit();
    }
}

// Handle removing products from cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header('Location: add_to_cart.php');
    exit();
}

// Handle updating quantities
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    
    if ($new_quantity > 0) {
        $_SESSION['cart'][$product_id] = $new_quantity;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
    
    header('Location: add_to_cart.php');
    exit();
}

// Get cart items from database
$cart_items = [];
$total = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    include 'conn.php';
    
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
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="./assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="./assets/Css/add_to_cart.css">
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
                    <h1 class="text-center text-light">Cart</h1>
                    <div class="crumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Cart</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-6 col-sm-12 order-summary">
                    <?php if (empty($cart_items)): ?>
                        <div class="text-center py-5">
                            <h3>Your cart is empty</h3>
                            <p>Add some products to your cart to continue shopping.</p>
                            <a href="./index.php" class="btn btn-primary">Continue Shopping</a>
                        </div>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td><img src="./assets/Product_Images/<?php echo $item['image']; ?>" alt="" style="width: 80px; height: 80px; object-fit: cover;"></td>
                                        <td><?php echo $item['tittle']; ?></td>
                                        <td>$<?php echo $item['price']; ?></td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="update_quantity" value="1">
                                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                <div class="count">
                                                    <i class="fa-solid fa-plus" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)"></i>
                                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="10" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                                                    <i class="fa-solid fa-minus" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo max(1, $item['quantity'] - 1); ?>)"></i>
                                                </div>
                                            </form>
                                        </td>
                                        <td>$<?php echo number_format($item['total'], 2); ?></td>
                                        <td><a href="?remove=<?php echo $item['id']; ?>" onclick="return confirm('Remove this item?')"><i class="fa-solid fa-xmark"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <div class="btm-tr">
                            <td><button class="btm-i">Coupon Code</button></td>
                            <td><button class="btm-ii">Apply Coupon</button></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </div>
                        
                        <div class="main-cart">
                            <div class="cart">
                                <h1 class="text-start">Cart <span>Total</span></h1>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 had">
                                        <p class="text-start">Subtotal:</p>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <p class="text-end">$<?php echo number_format($total, 2); ?></p>
                                    </div>
                                </div>
                                <div class="row shipping">
                                    <div class="col-lg-6 col-md-6 col-sm-12 had">
                                        <p class="text-start">Shipping</p>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <p class="text-end" style="width: 100%;">Flat rate: $3.00 Shipping to Pakistan</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="total">
                                    <h2>Total</h2>
                                    <p class="text-end">$<?php echo number_format($total + 3, 2); ?></p>
                                </div>
                                <hr>
                                <div class="btm">
                                    <a href="./checkout.php" class="btm-i">PROCEED TO CHECKOUT</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

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

    <script src="./assets/Laibraries/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateQuantity(productId, quantity) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="update_quantity" value="1">
                <input type="hidden" name="product_id" value="${productId}">
                <input type="hidden" name="quantity" value="${quantity}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>