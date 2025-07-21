<?php
include_once '../../assets/PHP/conn.php';
$select = $conn->prepare("SELECT * FROM `products`");
$select->execute();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Fruits</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/Css/style.css">
</head>

<body>
    <div class="container">
        <div class="cards">
            <div class="row">
                <?php
                $get = $select->get_result();
                while ($result = $get->fetch_assoc()) {
                ?>
                    <div class="col-lg-3 col-md-6 col-sm-12 my-2 text-center">
                        <div class="card p-0">
                            <div class="fruit">
                                <p>
                                    Fruits
                                </p>
                            </div>
                            <img src="../../assets/Images/<?php echo $result['image']; ?>" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $result['tittle']; ?></h5>
                                <p class="card-text"><?php echo $result['details']; ?></p>
                                <h5 class="card-title text-start">$ <?php echo $result['price']; ?>/Kg</h5>
                                <div class="buton mt-3">
                                    <a href="./delete_fruit.php?id=<?php echo $result['id'] ?>" class="btn m-3"><i class="fa-solid fa-bag-shopping bag"></i>Delete</a>
                                    <a href="./update_fruit.php?id=<?php echo $result['id'] ?>" class="btn m-3"><i class="fa-solid fa-bag-shopping bag"></i>Update</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <a href="./add_fruit.php" class="btn btn-info m-3 text-light">Add Fruits</a>
    </div>
    <script src="../../assets/Laibraries/js/bootstrap.bundle.min.js"></script>
</body>

</html>