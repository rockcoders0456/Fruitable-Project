<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../Laibraries/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> -->
    <link rel="stylesheet" href="../Css/checkout.css">
</head>

<body>
<div class="container nv">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6 .location">
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
                        <li><a href="../../index.php">Home</a></li>
                        <li><a href="">Shop</a></li>
                        <li><a href="./assets/PHP/shop_details.php">Shop Details</a></li>
                        <li><a href="../../index.php#service">Our Services</a></li>
                        <li><a href="https://wa.me/923484693403?text=Assalamualaikum" target="_blank"">Contact Us</a></li>
                    </ul>
                </div>
                <div class=" col-lg-2 col-md-12 col-sm-12 icon">
                                <i class="fa-solid fa-magnifying-glass srch"></i>
                                <i class="fa-solid fa-bag-shopping bag">
                                    <span class="position-absolute translate-middle badge  num">
                                        3
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </i>
                                <i class="fa-solid fa-user"></i>
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
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="billing">
        <div class="container billing">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-7 col-md-6 col-sm-12">
                            <div class="form">
                                <h1>Billing details</h1>
                                <form action="">
                                    <div class="half">
                                        <div class="frist">
                                            <label for="">Frist Name*</label>
                                            <input type="text" name="fName">
                                        </div>
                                        <div class="second">
                                            <label for="">Last Name*</label>
                                            <input type="text" name="lName">
                                        </div>
                                    </div>
                                    <label for="">Company Name*</label>
                                    <input type="text" name="companyName">
                                    <label for="">Address</label>
                                    <input type="text" name="address" placeholder="House Number Street Name">
                                    <label for="">Town/City*</label>
                                    <input type="text" name="city">
                                    <label for="">Country</label>
                                    <input type="text" name="country">
                                    <label for="">Postalcode/Zip*</label>
                                    <input type="number" name="postalcode">
                                    <label for="">Email Address*</label>
                                    <input type="email" name="email" id="email">
                                    <label for="">Mobile*</label>
                                    <input type="tel" name="mobile">
                                    <div class="chk">
                                        <input type="checkbox" name="createAccount" id="createAccount">
                                        <label for="createAccount">Create an account ?</label>
                                        <br>
                                        <input type="checkbox" name="diffAddress" id="diffAddress">
                                        <label for="diffAddress">Ship to a different address ?</label>
                                    </div>
                                    <textarea name="notes" cols="30" rows="7"
                                        placeholder="Order Notes (Optional)"></textarea>
                                    <button>PLACE ORDER</button>
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
                                    <tr>
                                        <td><img src="../../assets/img/vegetable-item-2.jpg" alt=""></td>
                                        <td>Awesome Brocoli</td>
                                        <td>$69.00</td>
                                        <td>2</td>
                                        <td>$138.00</td>
                                    </tr>
                                    <tr>
                                        <td><img src="../../assets/img/vegetable-item-5.jpg" alt=""></td>
                                        <td>Potatoes</td>
                                        <td>$69.00</td>
                                        <td>2</td>
                                        <td>$138.00</td>
                                    </tr>
                                    <tr>
                                        <td><img src="../../assets/img/vegetable-item-3.png" alt=""></td>
                                        <td>Big Banana</td>
                                        <td>$69.00</td>
                                        <td>2</td>
                                        <td>$138.00</td>
                                    </tr>
                                    <tr>
                                        <td><img src="../../assets/img/white.png" alt=""></td>
                                        <td></td>
                                        <td></td>
                                        <td>Subtotal</td>
                                        <td>$414.00</td>
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
                                        <li>Flat rate: $15.00</li>
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
                                    <p>$135.00</p>
                                    <hr>
                                </div>
                            </div>
                            <div class="method">
                                <ul>
                                    <hr>
                                    <li>
                                        Direct Bank Transfer
                                    </li>
                                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Distinctio asperiores accusantium tempora vitae quisquam. Minus atque nihil et aliquid laboriosam!</p>
                                    <hr>
                                    <li>
                                        Check Payment
                                    </li>
                                    <hr>
                                    <li>
                                        Cash on Delivery
                                    </li>
                                    <hr>
                                    <li>
                                        Paypal
                                    </li>
                                    </hr>
                                </ul>
                            </div>
                            <hr>
                        </div>
                    </div>
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
            <div class=" list my-3">
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
                            <li><img src="../../assets/img/payment.png" class="img-fluid" alt=""></li>
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


    <script src="../Laibraries/js/bootstrap.bundle.min.js"></script>
</body>

</html>