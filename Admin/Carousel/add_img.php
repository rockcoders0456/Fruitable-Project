<?php

if (isset($_POST['submit'])) {
    $name = $_FILES['file']['name'];
    $tempname = $_FILES['file']['tmp_name'];
    $newname = rand(1111, 9999) . '_' . $name;
    $target = './images';
    $upload = move_uploaded_file($tempname, $target . '/' . $newname);
    if ($upload) {
        include_once './conn_carousel.php';
        $insert = $conn->prepare("INSERT INTO `carousel`(`img`) VALUES (?)");
        $insert->bind_param("s", $newname);
        $insert->execute();
        if ($insert->affected_rows > 0) {
            echo "<script>alert('Image added successfully')</script>";
        } else {
            echo "<script>alert('Query Error!!!!!')</script>";
        }
    } else {
        echo "<script>alert('Image Uploading Error!!!!!')</script>";
    };
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Carousel Img</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/Css/add_fruit.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col form">
                <form action="" method="post" enctype="multipart/form-data">
                    <h1 class="text-center text-warning">
                        Add Carousel Img
                    </h1>
                    <div class="">
                        <input type="file" name="file" required>
                    </div>
                    <div class="">
                        <button type="submit" name="submit">Add Carousel Img</button>
                    </div>
                    <div class="a">
                        <a href="./view_carousel.php">View Carousel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../../assets/Laibraries/js/bootstrap.bundle.min.js"></script>
</body>

</html>