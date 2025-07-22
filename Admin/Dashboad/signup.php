<?php
include '../../assets/PHP/conn.php';

if (isset($_POST['submit'])) {
    $username = $_POST['userName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $img = $_FILES['img']['name'];
    $tmpImg = $_FILES['img']['tmp_name'];
    $newImg = rand(1111, 9999) . '_' . $img;
    $target = '../Admin_img';
    $upload = move_uploaded_file($tmpImg, $target . '/' . $newImg);
    if ($upload) {
        $insert = $conn->prepare("INSERT INTO `admin_data`(`username`, `email`, `password`, `img`) VALUES (?,?,?,?)");
        $insert->bind_param('ssss', $username, $email, $password, $newImg);
        $insert->execute();
        if ($insert) {
            echo '<script>alert("Signup Successfully");</script>';
            echo '<script>window.location.href = "./signin.php";</script>';
        }
        else{
            echo '<script>alert("Signup Failed. Please try again later.");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/Css/signup.css">
</head>

<body>
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data" class="form">
            <h2>Signup</h2>
            <div class="input-box">
                <label>Username</label>
                <input type="text" name="userName" placeholder="Enter username" required>
            </div>
            <div class="input-box">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="input-box">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <div class="input-box">
                <label>Profile Image</label>
                <input type="file" name="img" required>
            </div>
            <button type="submit" name="submit" class="signup-btn">Signup</button>
            <p>Already have an account? <a href="./signin.php" class="signin-link">Signin</a></p>
        </form>
    </div>

</body>

</html>