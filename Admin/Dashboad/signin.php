<?php
include '../../assets/PHP/conn.php';
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $select = $conn->prepare("SELECT * FROM admin_data WHERE email = ?");
    $select->bind_param("s", $email);
    $select->execute();
    $result = $select->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        if ($password == $row['password']) {
            header("Location: ./dashboad.php");
        }else{
            echo '<script>alert("Password Not Match!");</script>';
        }
        session_start();
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_name'] = $row['username'];
        $_SESSION['admin_img'] = $row['img'];
        $_SESSION['admin_email'] = $row['email'];

    }else{
        echo '<script>alert("Email Not exist. Please enter a existing email.");</script>';

    };
};
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/Css/signup.css">

</head>

    <body>
        <div class="container">
            <h2>Signin</h2>
            <form action="" method="post" enctype="multipart/form-data" class="form">
                <div class="input-box">
                    <label>Email Address</label>
                    <input type="email" placeholder="Enter email" name="email" required>
                </div>
                <div class="input-box">
                    <label>Password</label>
                    <input type="password" placeholder="Enter password" name="password" required>
                </div>
                <button type="submit" name="submit" class="signin-btn">Signin</button>
                <p>Don't have an account? <a href="./signup.php" class="signup-link">Signup</a></p>
            </form>
        </div>
    </body>

</html>