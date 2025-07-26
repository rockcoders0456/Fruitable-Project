<?php
session_start();
$ids = $_GET['id'] ?? '';
if (!$ids) {
    echo "Please select product frist";
?>
    <a href="./index.php">Continue Shopping</a>
<?php
    exit();
} else {
    include './conn.php';
    $select = "SELECT * FROM `products` WHERE id = '$ids'";
    $result = mysqli_query($conn, $select);
    $row = mysqli_fetch_assoc($result);
    $category = $row['category'];
    $select2 = "SELECT * FROM `products` WHERE category = '$category'";
    $result2 = mysqli_query($conn, $select2);
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $review = $_POST['review'];
        $insert = "INSERT INTO `comments`(`name`, `email`, `review`) VALUES ('$name','$email','$review')";
        $result = mysqli_query($conn, $insert);
        if ($result) {
            echo "<script> alert('Your Comment is posted. Thanks for your review')</script>";
        }
    }
?>




    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shop Details</title>
        <link rel="stylesheet" href="assets/Laibraries/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="assets/Css/shop_details.css">
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
                            <li><a href="https://wa.me/923484693403?text=Assalamualaikum" target="_blank"">Contact Us</a></li>
                    </ul>
                </div>
                <div class=" col-lg-2 col-md-12 col-sm-12 icon">
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
                        <h1 class="text-center text-light">Shop Detail</h1>
                        <div class="crumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Shop Detail</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="item" class="item">
            <div class="container mb-5 mt-5">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 card">
                        <img src="./assets/Product_Images/<?php echo $row['image']; ?>" alt="">
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-12 details">
                        <h2>
                            <?php echo $row['tittle'] ?>
                        </h2>
                        <p>Catagory:
                            <?php echo $row['category'] ?>
                        </p>
                        <h2>
                            <?php echo $row['price'] ?><sup>$</sup>/Kg
                        </h2>
                        <div class="rating">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star" style="color: gray;"></i>
                        </div>
                        <p>
                            <?php echo $row['details'] ?>
                        </p>
                        <p>
                            <?php echo $row['details'] ?>
                        </p>


                        <div class="count d-flex">
                            <i class="fa-solid fa-plus" onclick="increase()"></i>
                            <p id="quintity" class="m-3">0</p>
                            <i class="fa-solid fa-minus" onclick="decrease()"></i>

                        </div>
                        <div class="butn mt-3">
                            <a href="#" class="btn" onclick="addToCart(<?php echo $ids; ?>)"><i class="fa-solid fa-bag-shopping bag my-2"></i>
                                Add
                                to cart
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mt-3 keywords">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-lg" placeholder="Keywords">
                            <span class="input-group-text" id="inputGroup-sizing-lg"><i
                                    class="fa-solid fa-magnifying-glass srch"></i></span>

                        </div>
                        <h3>Categories</h3>
                        <div class="main">
                            <div class="icon">
                                <i class="fa-brands fa-apple mt-2"></i>
                                <p class="ms-2">Apples</p>
                            </div>
                            <div class="text">
                                <p>(3)</p>
                            </div>
                        </div>
                        <div class="main">
                            <div class="icon">
                                <i class="fa-brands fa-apple mt-2"></i>
                                <p class="ms-2">Oranges</p>
                            </div>
                            <div class="text">
                                <p>(5)</p>
                            </div>
                        </div>
                        <div class="main">
                            <div class="icon">
                                <i class="fa-brands fa-apple mt-2"></i>
                                <p class="ms-2">Strawbery</p>
                            </div>
                            <div class="text">
                                <p>(2)</p>
                            </div>
                        </div>
                        <div class="main">
                            <div class="icon">
                                <i class="fa-brands fa-apple mt-2"></i>
                                <p class="ms-2">Banana</p>
                            </div>
                            <div class="text">
                                <p>(8)</p>
                            </div>
                        </div>
                        <div class="main">
                            <div class="icon">
                                <i class="fa-brands fa-apple mt-2"></i>
                                <p class="ms-2">Pumpkin</p>
                            </div>
                            <div class="text">
                                <p>(5)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="featured">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-6 col-sm-12 review">
                        <ul>
                            <li>Description</li>
                            <li>Reviews</li>
                        </ul>
                        <hr>
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia adipisci nulla et nisi voluptatem
                            sed, sint rerum maiores veniam pariatur tempora quibusdam vitae non culpa quis! Alias,
                            explicabo!
                        </p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam debitis laudantium sit vitae et?
                            Quis pariatur non distinctio nostrum eligendi? Accusantium fuga commodi eveniet ullam aut ipsam
                            quisquam illum? Recusandae esse
                            corporis, explicabo nisi nesciunt maxime enim reprehenderit quaerat numquam alias doloremque?
                        </p>
                        <table class="table table-striped rounded">
                            <tbody>
                                <tr>
                                    <td>Weight</td>
                                    <td>1kg</td>
                                </tr>
                                <tr>
                                    <td>Countory of Origin</td>
                                    <td>Agro Farm</td>
                                </tr>
                                <tr>
                                    <td>Quality</td>
                                    <td>Organic</td>
                                </tr>
                                <tr>
                                    <td>Check</td>
                                    <td>Healthy</td>
                                </tr>
                                <tr>
                                    <td>Min Weight</td>
                                    <td>250Kg</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12 fthr">
                        <h2>Featured products</h2>
                        <div class="main-featured">
                            <div class="img">
                                <img src="./assets/img/featur-1.jpg" class="img-fluid" alt="">
                            </div>
                            <div class="text">
                                <p>Big Banana</p>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star" style="color: gray;"></i>
                                </div>
                                <h3>2.93 $ <s>4.11 $</s></h3>
                            </div>
                        </div>
                        <div class="main-featured">
                            <div class="img">
                                <img src="./assets/img/featur-2.jpg" class="img-fluid" alt="">
                            </div>
                            <div class="text">
                                <p>Big Banana</p>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star" style="color: gray;"></i>
                                </div>
                                <h3>2.93 $ <s>4.11 $</s></h3>
                            </div>
                        </div>
                        <div class="main-featured">
                            <div class="img">
                                <img src="./assets/img/featur-3.jpg" class="img-fluid" alt="">
                            </div>
                            <div class="text">
                                <p>Big Banana</p>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star" style="color: gray;"></i>
                                </div>
                                <h3>2.93 $ <s>4.11 $</s></h3>
                            </div>
                        </div>
                        <div class="main-featured">
                            <div class="img">
                                <img src="./assets/img/vegetable-item-4.jpg" class="img-fluid" alt="">
                            </div>
                            <div class="text">
                                <p>Big Banana</p>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star" style="color: gray;"></i>
                                </div>
                                <h3>2.93 $ <s>4.11 $</s></h3>
                            </div>
                        </div>
                        <div class="main-featured">
                            <div class="img">
                                <img src="./assets/img/vegetable-item-5.jpg" class="img-fluid" alt="">
                            </div>
                            <div class="text">
                                <p>Big Banana</p>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star" style="color: gray;"></i>
                                </div>
                                <h3>2.93 $ <s>4.11 $</s></h3>
                            </div>
                        </div>
                        <div class="main-featured">
                            <div class="img">
                                <img src="./assets/img/vegetable-item-6.jpg" class="img-fluid" alt="">
                            </div>
                            <div class="text">
                                <p>Big Banana</p>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star" style="color: gray;"></i>
                                </div>
                                <h3>2.93 $ <s>4.11 $</s></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="comment">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-6 col-sm-12 comment">
                        <h1>Leave a Reply</h1>
                        <div class="form">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="inputs">
                                    <input type="text" placeholder="Your name*" name="name">
                                    <input type="email" placeholder="Your Email*" name="email">
                                </div>
                                <textarea name="review" id="" cols="30" rows="5" placeholder="Your Review*"></textarea>
                                <div class="post_comment">
                                    <div class="rating">
                                        <p style="color: gray;">Please Rate:</p>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                    <div class="bttn">
                                        <button type="submit" name="submit" class="btn">Post Comment</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 last">
                        <div class="butn mt-3">
                            <a href="" class="btn">Add to cart</a>
                        </div>
                        <div class="img">
                            <img src="./assets/img/banner-fruits.jpg" class="img-fluid" alt="">
                            <div class="text">
                                <h1>Fresh Fruit Banner</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="container">
                <div class="vages">
                    <div class="heading">
                        <h1>Related products</h1>
                    </div>
                    <div class="owl-carousel owl-theme">
                        <?php
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                        ?>
                            <div class="item">
                                <div class="card p-0">
                                    <div class="fruit">
                                        <p>
                                            <?php echo $row2['category']; ?>
                                        </p>
                                    </div>
                                    <img src="./assets/Product_Images/<?php echo $row2['image']; ?>" class="card-img-top"
                                        alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title text-start ">
                                            <?php echo $row2['tittle']; ?>
                                        </h5>
                                        <p class="card-text text-start">
                                            <?php echo $row2['details']; ?>
                                        </p>
                                        <h5 class="card-title text-start">
                                            <?php echo $row2['price']; ?> / Kg
                                        </h5>
                                                                                    <div class="buton mt-3">
                                                <a href="./shop_details.php?id=<?php echo $row2['id']; ?>" class="btn"><i class="fa-solid fa-eye"></i> View Details</a>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
        <script>
            $('.owl-carousel').owlCarousel({
                loop: true,
                nav: false,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                margin: 10,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 4
                    }
                }
            });
        </script>
        <script src="assets/JS/shop_details.js"></script>
    </body>

    </html>
<?php
}
?>