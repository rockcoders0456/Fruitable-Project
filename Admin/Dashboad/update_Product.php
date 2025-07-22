<?php
include_once '../../assets/PHP/conn.php';
$ids = $_GET['id'];
$select = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
$select->bind_param("i", $ids);
$select->execute();
$get = $select->get_result();
$result = $get->fetch_assoc();
if (isset($_POST['submit'])) {
    if (!empty($_FILES['file']['name'])) {
        $name = $_FILES['file']['name'];
        $tempname = $_FILES['file']['tmp_name'];
        $newname = rand(1111, 9999).'_' . $name;
        $target = '../../assets/Images/';
        move_uploaded_file($tempname, $target . '/' . $newname);
    } else {
        $newname = $result['image'];
    };
    $tittle = $_POST['tittle'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $details = $_POST['detail'];
    $update = $conn->prepare("UPDATE `products` SET `tittle`= ?,`details`= ?,`price`= ?,`category`= ?,`image`= ? WHERE id = ?");
    $update->bind_param("ssissi", $tittle, $details, $price, $category, $newname, $ids);
    if ($update->execute()) {
        echo "<script>alert('Product updated successfully.'); window.location.href = './dashboad.php';</script>";
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
    <title>Update Product</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/Css/add_Product.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col form">
                <form action="" method="post" enctype="multipart/form-data">
                    <h1 class="text-center text-warning">
                        Update Product
                    </h1>
                    <div class="">
                        <input type="file" name="file">
                    </div>
                    <div class="">
                        <input type="text" placeholder="Product Tittle" name="tittle"
                            value="<?php echo $result['tittle'] ?>" required>
                    </div>
                    <div class="">
                        <input type="number" placeholder="Product Price" name="price"
                            value="<?php echo $result['price']; ?>" required>
                    </div>
                    <div class="">
                        <select name="category" id="">
                            <option value="<?php echo $result['category']; ?>"><?php echo $result['category']; ?></option>
                            <option value="Fruit">Fruit</option>
                            <option value="Vegetable">Vegetable</option>
                        </select>
                    </div>
                    <div class="">
                        <textarea name="detail" id="" cols="30" rows="5"
                            required><?php echo $result['details'] ?></textarea>
                    </div>
                    <div class="">
                        <button type="submit" name="submit">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <script src="../../assets/Laibraries/js/bootstrap.bundle.min.js"></script>
</body>

</html>