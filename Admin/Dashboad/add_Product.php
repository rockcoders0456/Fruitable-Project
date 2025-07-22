<?php

if (isset($_POST['submit'])) {
    $name = $_FILES['file']['name'];
    $tempname = $_FILES['file']['tmp_name'];
    $newname = rand(1111, 9999).'_'.$name;
    $target = '../../assets/Images';
    $upload = move_uploaded_file($tempname, $target . '/' . $newname);
    $tittle = $_POST['tittle'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $details = $_POST['detail'];
    include_once '../../assets/PHP/conn.php';
    $insert = $conn->prepare("INSERT INTO `products`(`tittle`, `details`, `price`,`category`,`image`)
     VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("ssiss", $tittle, $details, $price, $category, $newname);
    $insert->execute();
    if ($insert) {
        echo "<script>alert('Product added successfully')</script>";
        echo "<script>window.location.href = './dashboad.php';</script>";     
    } else {
        echo "<script>alert('Product Adding Error!!!!!')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/Css/add_Product.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col form">
                <form action="" method="post" enctype="multipart/form-data">
                    <h1 class="text-center text-warning">
                        Add Product
                    </h1>
                    <div class="">
                        <input type="file" name="file" required>
                    </div>
                    <div class="">
                        <input type="text" placeholder="Product Tittle" name="tittle" required>
                    </div>
                    <div class="">
                        <input type="number" placeholder="Product Price" name="price" required>
                    </div>
                    <div class="">
                        <select name="category" id="">
                            <option value="Fruit">Fruit</option>
                            <option value="Vegetable">Vegetable</option>
                        </select>
                    </div>
                    <div class="">
                        <textarea name="detail" id="" cols="30" rows="5" required></textarea>
                    </div>
                    <div class="">
                        <button type="submit" name="submit">Add Product</button>
                    </div>
                    <div class="a">
                        <a href="./dashboad.php">View Products</a>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <script src="../../assets/Laibraries/js/bootstrap.bundle.min.js"></script>
</body>

</html>