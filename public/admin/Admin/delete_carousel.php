<?php
include_once '../conn.php';
$ids = $_GET['id'];
$delete = $conn->prepare("DELETE FROM `carousel` WHERE id = ?");
$delete->bind_param("i", $ids);
$delete->execute();
if ($delete) {
    echo "<script>alert('Image Deleted Successfully');</script>";
    echo "<script>window.location.href = './view_carousel.php';</script>";
} else {
    echo "<script>alert('Failed to Delete Product');</script>";
    echo "<script>window.location.href = '../view_carousel.php';</script>";
}
?>