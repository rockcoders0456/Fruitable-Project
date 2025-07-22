<?php
session_start();
if (!isset($_SESSION['admin_name'])) {
?>
<script>
    let a = confirm("Please Sign In First.");
    if (a) {
        window.location.href = "./signin.php";
    } else {
        alert("Chal pir dafa hoja")
    }
</script>
<?php
    exit();
} else {
    $admin_id = $_SESSION['admin_id'];
    $admin_name = $_SESSION['admin_name'];
    $admin_email = $_SESSION['admin_email'];
    $admin_img = $_SESSION['admin_img'];
    include '../../assets/PHP/conn.php';
    $select = $conn->prepare("SELECT * FROM `products`");
    $select->execute();
    $get1 = $select->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboad</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/Css/dashboad.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 nav">
                <div class="col-12 logo">
                    <h1># Fruitable</h1>
                </div>
                <div class="info">
                    <div class="img">
                        <img src="../../Admin/Admin_img/<?php echo $admin_img; ?>" alt=""
                            style="width: 50px; height: 50px; border-radius: 50%;">
                    </div>
                    <div class="name">
                        <b>
                            <?php echo $admin_name; ?>
                        </b>
                        <p>Admin</p>
                    </div>
                </div>
                <ul>
                    <li class="active"><i class="fa-solid fa-gauge-high"></i>Dashboard</li>
                    <li>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        <i class="fa-solid fa-laptop"></i> Products
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse hide"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <a href="./add_Product.php">Add New Product</a>
                                        <a href="./dashboad.php">View all Products</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><i class="fa-solid fa-ellipsis"></i>Widgets</li>
                    <li> <i class="fa-brands fa-wpforms"></i>Forms</li>
                    <li><i class="fa-solid fa-table"></i>Tables</li>
                    <li><i class="fa-solid fa-chart-simple"></i>Charts</li>
                    <li>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapsetwo" aria-expanded="false" aria-controls="collapsetwo">
                                        <i class="fa-solid fa-file-lines"></i> Pages
                                    </button>
                                </h2>
                                <div id="collapsetwo" class="accordion-collapse collapse hide"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <a href="">Fill</a>
                                        <a href="">Blank</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-10">
                <main>

                    <nav>
                        <div class="search">
                            <i class="fa-solid fa-bars"></i><input type="search" name="search" placeholder="Search">
                        </div>
                        <div class="details">
                            <ul>
                                <li>
                                    <i class="fa-solid fa-envelope"></i>
                                    <select name="" id="">
                                        <option value="">Message </option>
                                        <option value="">Call</option>

                                    </select>
                                </li>
                                <li>
                                    <i class="fa-solid fa-bell"></i>
                                    <select name="" id="">
                                        <option value="">Notification </option>
                                        <option value="">Empty</option>

                                    </select>
                                </li>
                                <li>
                                    <img src="../../Admin/Admin_img/<?php echo $admin_img; ?>" alt=""
                                        style="width: 40px; height: 40px; border-radius: 50%;">
                                    <select name="" id="">
                                        <option value="">
                                            <?php echo $admin_name ?>
                                        </option>
                                        <option value="">
                                            <?php echo $admin_email ?>
                                        </option>
                                    </select>
                                </li>
                                <li><a href="./logout.php"><i class="fa-solid fa-right-from-bracket"></i></a></li>
                            </ul>
                        </div>
                    </nav>
                    <h1 class="text-center m-4">View All Products</h1>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product Title</th>
                                <th scope="col">Product Price</th>
                                <th scope="col">Categories</th>
                                <th scope="col">Product Image</th>
                                <th scope="col">Update Product</th>
                                <th scope="col">Delete Product</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($result = $get1->fetch_assoc()) {
                            ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $result['id']; ?>
                                </th>
                                <td>
                                    <?php echo $result['tittle']; ?>
                                </td>
                                <td>
                                    <?php echo $result['price'];?>
                                    $/Kg
                                </td>
                                <td><?php echo $result['category'];?></td>
                                <td><img src="../../assets/Images/<?php echo $result['image']; ?>"
                                        style="width:50px; height: 50px; border-radius: 10px;"></td>
                                <td><a href="./update_Product.php?id=<?php echo $result['id'] ?>">Update</a>
                                </td>
                                <td><a href="./delete_Product.php?id=<?php echo $result['id'] ?>">Delete</a>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </main>
            </div>
        </div>
    </div>
    <script src="../../assets/Laibraries/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
}
?>