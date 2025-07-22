<?php
include_once '../../assets/PHP/conn.php';
$ids = $_GET['id'];
$delete = $conn->prepare("DELETE FROM `products` WHERE id = ?");
$delete->bind_param("i", $ids);
$delete->execute();
if ($delete) {
    echo "<script>alert('Product Deleted Successfully');</script>";
    echo "<script>window.location.href = './dashboad.php';</script>";
} else {
    echo "<script>alert('Failed to Delete Product');</script>";
    echo "<script>window.location.href = './view_fruit.php';</script>";
}
?>