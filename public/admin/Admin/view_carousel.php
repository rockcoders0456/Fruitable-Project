<?php
include_once '../conn.php';
$cselect = $conn->prepare("SELECT * FROM `carousel`");
if (!$cselect) {
    die("Carousel query prepare failed: " . $conn->error);
}
$cselect->execute();
$cget = $cselect->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Carousel</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/Css/style.css">
</head>

<body>

    <style>
        .carousel-inner a {
            margin-left: 200px;
            margin-top: 20px;
        }
    </style>
    <section>
        <div class="container-fluid hero">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-12 carasul ">
                    <div class="fruit">
                        <p>
                            Fruits
                        </p>
                    </div>
                    <div id="carouselExampleIndicators" class="carousel slide carousel-fade " data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="../../assets/img/hero-img-1.png" class="d-block w-100 bg-warning rounded"
                                    alt="...">
                            </div>
                            <?php
                            while ($cresult = $cget->fetch_assoc()) {
                            ?>
                                <div class="carousel-item">
                                    <img src="./images/<?php echo $cresult['img']; ?>"
                                        class="d-block w-100 bg-warning rounded" alt=".........">
                                    <a href="./delete_carousel.php?id=<?php echo $cresult['id']; ?>" class="btn btn-info">Delete</a>
                                    <a href="./update_carousel.php?id=<?php echo $cresult['id']; ?>" class="btn btn-info">Update</a>
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
    <script src="../../assets/Laibraries/js/bootstrap.bundle.min.js"></script>
</body>

</html>