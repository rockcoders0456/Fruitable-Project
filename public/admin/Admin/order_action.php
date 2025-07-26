<?php
session_start();
include '../conn.php';
include './notification_helper.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_name'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

$action = $_POST['action'] ?? '';
$order_id = intval($_POST['order_id'] ?? 0);

if ($action === 'view' && $order_id > 0) {
    // Fetch order details
    $order_sql = "SELECT * FROM orders WHERE id = $order_id";
    $order_result = mysqli_query($conn, $order_sql);
    if ($order_result && $order = mysqli_fetch_assoc($order_result)) {
        // Fetch order items
        $items_sql = "SELECT * FROM order_items WHERE order_id = $order_id";
        $items_result = mysqli_query($conn, $items_sql);
        $items = [];
        while ($item = mysqli_fetch_assoc($items_result)) {
            $items[] = $item;
        }
        $order['items'] = $items;
        echo json_encode(['success' => true, 'order' => $order]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Order not found']);
    }
    exit();
}

if ($action === 'complete' && $order_id > 0) {
    $update_sql = "UPDATE orders SET status = 'completed' WHERE id = $order_id";
    if (mysqli_query($conn, $update_sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update order status']);
    }
    exit();
}

if ($action === 'update_status' && $order_id > 0) {
    $status = $_POST['status'] ?? '';
    // Map custom statuses to DB statuses first
    if ($status === 'confirm') $status = 'completed';
    if ($status === 'reject') $status = 'rejected';
    if (!in_array($status, ['completed', 'rejected', 'cancelled', 'pending', 'processing'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid status']);
        exit();
    }
    $update_sql = "UPDATE orders SET status = '$status' WHERE id = $order_id";
    if (mysqli_query($conn, $update_sql)) {
        // Fetch user email and name
        $order_sql = "SELECT * FROM orders WHERE id = $order_id";
        $order_result = mysqli_query($conn, $order_sql);
        if ($order_result && $order = mysqli_fetch_assoc($order_result)) {
            $user_email = $order['email'];
            $user_name = $order['first_name'] . ' ' . $order['last_name'];
            sendOrderStatusEmail($user_email, $user_name, $order_id, $status);
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update order status']);
    }
    exit();
}

echo json_encode(['success' => false, 'error' => 'Invalid request']); 