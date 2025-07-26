<?php
include_once '../conn.php';
$ids = $_GET['id'];
$select = $conn->prepare("SELECT * FROM `carousel` WHERE id = ?");
$select->bind_param("i", $ids);
$select->execute();
$get = $select->get_result();
$result = $get->fetch_assoc();
if (isset($_POST['submit'])) {
    if (!empty($_FILES['file']['name'])) {
        $name = $_FILES['file']['name'];
        $tempname = $_FILES['file']['tmp_name'];
        $newname = rand(1111, 9999) . '_' . $name;
        $target = './images';
        move_uploaded_file($tempname, $target . '/' . $newname);
    } else {
        $newname = $result['image'];
    };
    $update = $conn->prepare("UPDATE `carousel` SET `img`= ? WHERE id= ?");
    $update->bind_param('si', $newname, $ids);
    if ($update->execute()) {
        echo "<script>alert('Product updated successfully.'); window.location.href='view_carousel.php';</script>";
    } else {
        echo "<script>alert('Failed to update product.');</script>";
    }
};
$select = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
$select->bind_param("i", $ids);
$select->execute();
$get = $select->get_result();
$result = $get->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Carousel Img</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/Css/add_fruit.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col form">
                <form action="" method="post" enctype="multipart/form-data">
                    <h1 class="text-center text-warning">
                        Update Carousel Img
                    </h1>
                    <div class="">
                        <input type="file" name="file" required>
                    </div>
                    <div class="">
                        <button type="submit" name="submit">Update Carousel Img</button>
                    </div>
                    <div class="a">
                        <a href="">View Carousel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../../assets/Laibraries/js/bootstrap.bundle.min.js"></script>
</body>

</html>