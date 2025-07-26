<?php
session_start();
include_once 'conn.php';
$category1 = "Fruit";
$category2 = "Vegetable";
$select = "SELECT * FROM `products` WHERE category = '$category1'";
$result = mysqli_query($conn, $select);
$select2 = "SELECT * FROM `products` WHERE category = '$category2'";
$result2 = mysqli_query($conn, $select2);
$cselect = "SELECT * FROM `carousel`";
$cresult = mysqli_query($conn, $cselect);
$select3 = "SELECT * FROM `products` WHERE category = '$category1'";
$result3 = mysqli_query($conn, $select);
$select4 = "SELECT * FROM `products` WHERE category = '$category2'";
$result4 = mysqli_query($conn, $select);
if (!$select || !$select2 || !$select) {
?>
    <script>
        alert('Query Failed');
    </script>
<?php
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruitables</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/Css/style.css">

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
                        <li><a href="./index.php">Home</a></li>
                        <li><a href="">Shop</a></li>
                        <li><a href="./shop_details.php">Shop Details</a></li>
                        <li><a href="#service">Our Services</a></li>
                        <li><a href="https://wa.me/923484693403?text=Assalamualaikum" target="_blank">Contact Us</a></li>
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
                                <li><a class="dropdown-item" href="./customer_logout.php"><i class="fas fa-sign-out-alt"></i></a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="./customer_login.php" class="text-decoration-none text-dark">
                            <i class="fa-solid fa-user"></i>
                            <span class="ms-1"></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container-fluid hero">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <p>100% Organic Foods</p>
                    <h1>Organic Veggies & Fruits Foods</h1>
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
                <div class="col-lg-5 col-md-6 col-sm-12 carasul ">
                    <div class="fruit">
                        <p>
                            Fruits
                        </p>
                    </div>
                    <div id="carouselExampleIndicators" class="carousel slide carousel-fade " data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                                class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"
                                aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="./assets/img/hero-img-1.png" class="d-block w-100 bg-warning rounded"
                                    alt="...">
                            </div>
                            <?php
                            while ($crow = mysqli_fetch_assoc($cresult)) {
                            ?>
                                <div class="carousel-item">
                                    <img src="./Admin/Carousel/images/<?php echo $crow['img']; ?>"
                                        class="d-block w-100 bg-warning rounded" alt=".........">
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>

                    </div>
                </div>
            </div>
    </section>
    <section>
        <div class="container my-5 service" id="service">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12 my-2">
                    <div class="card">
                        <div class="icon">
                            <i class="fa-solid fa-car-side fa-2xl"></i>
                        </div>
                        <div class="card-body">
                            <b class="card-title">Free Shopping</b>
                            <p class="card-text">Free on order over $300</p>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6 col-sm-12 my-2">
                    <div class="card">
                        <div class="icon">
                            <i class="fa-solid fa-shield-halved fa-2xl"></i>
                        </div>
                        <div class="card-body">
                            <b class="card-title">Security Payment</b>
                            <p class="card-text">100% Security Payment</p>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6 col-sm-12 my-2">
                    <div class="card">
                        <div class="icon">
                            <i class="fa-solid fa-right-left fa-2xl"></i>
                        </div>
                        <div class="card-body">
                            <b class="card-title">30 Days Return</b>
                            <p class="card-text">30 day money guarantee</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 my-2">
                    <div class="card">
                        <div class="icon">
                            <i class="fa-solid fa-phone fa-2xl"></i>
                        </div>
                        <div class="card-body">
                            <b class="card-title">24/7 Support</b>
                            <p class="card-text">Support every time fast</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="organic-products align-self-center">
                        <div class="heading">
                            <h3>Our Organic <br> Fruits</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-6 col-sm-6">
                    <div class="products-list">
                        <ul>
                            <li>All Products</li>
                            <li>Vegetables</li>
                            <li>Fruits</li>
                            <li>Bread</li>
                            <li>Meat</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="cards">
                <div class="row">
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <div class="col-lg-3 col-md-6 col-sm-12 my-2 text-center">
                            <div class="card p-0">
                                <div class="fruit">
                                    <p>
                                        Fruits
                                    </p>
                                </div>
                                <img src="./assets/Product_Images/<?php echo $row['image']; ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $row['tittle']; ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo $row['details']; ?>
                                    </p>
                                    <h5 class="card-title text-start">$
                                        <?php echo $row['price']; ?>/Kg
                                    </h5>
                                    <div class="buton mt-3">
                                        <a href="./shop_details.php?id=<?php echo $row['id']; ?>" class="btn"><i class="fa-solid fa-eye"></i>View Details</a>
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
    <section>
        <div class="container">
            <div class="catagories">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card-i p-0">
                            <img src="./assets/img/featur-1.jpg" class="card-img-top" alt="...">
                            <div class="card-body m-0 p-0">
                                <div class="btm">
                                    <div class="name">
                                        <p>
                                            Fresh Apples
                                        </p>
                                        <h3>
                                            20% Off
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card-ii p-0">
                            <img src="./assets/img/featur-2.jpg" class="card-img-top" alt="...">
                            <div class="card-body m-0 p-0">
                                <div class="btm">
                                    <div class="name">
                                        <p>
                                            Tasty Fruits
                                        </p>
                                        <h3>
                                            Free delivery
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card-iii p-0">
                            <img src="./assets/img/featur-3.jpg" class="card-img-top" alt="...">
                            <div class="card-body m-0 p-0">
                                <div class="btm">
                                    <div class="name">
                                        <p>
                                            Exotic Vegetables
                                        </p>
                                        <h3>
                                            Discount 30<sup>$</sup>
                                        </h3>
                                    </div>
                                </div>
                            </div>
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
                    <h1>Fresh Organic Vegetables</h1>
                </div>
                <div class="row">
                    <?php
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                    ?>
                        <div class="col-lg-3 col-md-6 col-sm-12 my-2 text-center">
                            <div class="card p-0">
                                <div class="fruit">
                                    <p>
                                        Vegetable
                                    </p>
                                </div>
                                <img src="./assets/Product_Images/<?php echo $row2['image']; ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $row2['tittle']; ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo $row2['details']; ?>
                                    </p>
                                    <h5 class="card-title text-start">$
                                        <?php echo $row2['price']; ?>/Kg
                                    </h5>
                                    <div class="buton mt-3">
                                    <a href="./shop_details.php?id=<?php echo $row2['id']; ?>" class="btn"><i class="fa-solid fa-eye"></i>View Details</a>
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
        </div>
    </section>
    <section>
        <div class="store">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 text">
                        <h1>Fresh Exotic <br> Fruits</h1>
                        <h3>in Our Store</h3>
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsam quas nihil, repellat
                            possimus facilis animi!
                        </p>
                        <a href="#" class="btn">BUY</a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 img">
                        <div class="price">
                            <h1>1<sub>Kg</sub></h1>
                            <h2> <sup>50 $</sup></h2>
                        </div>
                        <img class="img-fluid" src="./assets/img/baner-1.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="bestseller my-3">
            <div class="container">
                <div class="row">
                    <div class="text">
                        <h1>Bestseller Products</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit voluptates <br> cum
                            magni
                            consequuntur facere.</p>
                    </div>
                </div>
                <div class="best-product">
                    <div class="owl-carousel owl-theme my-4">
                        <?php
                        while ($row3 = mysqli_fetch_assoc($result3)) {
                        ?>
                            <div class="item">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-6 img item">
                                            <img src="./assets/Product_Images/<?php echo $row3['image']; ?>" class="card-img-top" alt="...">
                                        </div>
                                        <div class="col-6 text-start">
                                            <b><?php echo $row3['tittle']; ?></b>
                                            <div class="icon">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star" style="color: gray;"></i>
                                            </div>
                                            <b><?php echo $row3['price']; ?>$/Kg</b>
                                            <div class="butn mt-3">
                                            <a href="./shop_details.php?id=<?php echo $row3['id']; ?>" class="btn"><i class="fa-solid fa-eye"></i>View Details</a>
                                            </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="last-fruit">
                        <div class="owl-carousel owl-theme">
                            <?php
                            while ($row4 = mysqli_fetch_assoc($result4)) {
                            ?>
                                <div class="item">
                                    <div class="card p-0">
                                        <img src="./assets/Product_Images/<?php echo $row4['image']; ?>" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <b><?php echo $row4['tittle'] ?></b><br>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star" style="color: gray;"></i>
                                            <br>
                                            <b><?php echo $row4['price'] ?>$/Kg</b>
                                            <div class="butn mt-3">
                                            <a href="./shop_details.php?id=<?php echo $row4['id']; ?>" class="btn"><i class="fa-solid fa-eye"></i>View Details</a>
                                                </a>
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
            </div>
        </div>
    </section>




    <section>
        <div class="container">
            <div class="last p-4 my-5">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12 my-2">
                        <div class="card">
                            <i class="fa-solid fa-people-group fa-2xl py-5 py-5" style="color: #FFD43B;"></i>
                            <p>SATISFIED CUSTOMERS</p>
                            <h1>1963</h1>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12 my-2">
                        <div class="card">
                            <i class="fa-solid fa-people-group fa-2xl py-5" style="color: #FFD43B;"></i>
                            <p>QUALITY OF SERVICE</p>
                            <h1>99%</h1>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12 my-2">
                        <div class="card">
                            <i class="fa-solid fa-people-group fa-2xl py-5" style="color: #FFD43B;"></i>
                            <p>QUALITY CERTIFICTES</p>
                            <h1>33</h1>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12 my-2">
                        <div class="card">
                            <i class="fa-solid fa-people-group fa-2xl py-5" style="color: #FFD43B;"></i>
                            <p>AVAILABLE PRODUCTS</p>
                            <h1>789</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row mt-5 mb-3 comments-heading">
                <div class="col-12 text-center">
                    <b>Our Testimonial</b>
                    <h1>Our client Saying!</h1>
                </div>
            </div>
            <div class="comments mb-5">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 my-2">
                        <div class="card">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quia dignissimos est dolore
                                dolor ipsam iure illum, nemo sequi dolorum.</p>
                            <hr>
                            <div class="d-flex justify-content-left flex my-3">
                                <div class="">
                                    <img src="./assets/img/testimonial-1.jpg" class="img-fluid me-3 rounded" alt="">
                                </div>
                                <div class="text-start">
                                    <h3>Client Name</h3>
                                    <p>Profession</p>
                                    <div class="comment-icon">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star" style="color: gray;"></i>
                                    </div>
                                </div>
                                <div class="thumb text-end">
                                    <i class="fa-solid fa-thumbs-up fa-2xl" style="color: #FFD43B;"></i>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-6 col-md-12 col-sm-12 my-2">
                        <div class="card">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quia dignissimos est dolore
                                dolor ipsam iure illum, nemo sequi dolorum.</p>
                            <hr>
                            <div class="d-flex justify-content-left flex my-3">
                                <div class="">
                                    <img src="./assets/img/testimonial-1.jpg" class="img-fluid me-3 rounded" alt="">
                                </div>
                                <div class="text-start">
                                    <h3>Client Name</h3>
                                    <p>Profession</p>
                                    <div class="comment-icon">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star" style="color: gray;"></i>
                                    </div>
                                </div>
                                <div class="thumb text-end">
                                    <i class="fa-solid fa-thumbs-up fa-2xl" style="color: #FFD43B;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container-fluid main-footer">
            <div class="row mb-3">
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
    <script src="assets/Laibraries/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>