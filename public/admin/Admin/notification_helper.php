<?php
// Notification Helper Functions

function createNotification($conn, $type, $title, $message, $order_id = null, $user_id = null) {
    // Handle null values for order_id and user_id
    $order_id = $order_id ?: null;
    $user_id = $user_id ?: null;
    
    $insert_notification = "INSERT INTO notifications (type, title, message, order_id, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_notification);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssii", $type, $title, $message, $order_id, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            // Update notification count for all admin users
            updateAdminNotificationCount($conn);
            return mysqli_insert_id($conn);
        } else {
            error_log("Notification creation failed: " . mysqli_stmt_error($stmt));
        }
    } else {
        error_log("Failed to prepare notification statement: " . mysqli_error($conn));
    }
    return false;
}

function getUnreadNotifications($conn, $limit = 10) {
    $query = "SELECT * FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $limit);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function getNotificationCount($conn) {
    $query = "SELECT COUNT(*) as count FROM notifications WHERE is_read = 0";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function markNotificationAsRead($conn, $notification_id) {
    $update = "UPDATE notifications SET is_read = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param($stmt, "i", $notification_id);
    return mysqli_stmt_execute($stmt);
}

function markAllNotificationsAsRead($conn) {
    $update = "UPDATE notifications SET is_read = 1 WHERE is_read = 0";
    $result = mysqli_query($conn, $update);
    if ($result) {
        updateAdminNotificationCount($conn);
    }
    return $result;
}

function updateAdminNotificationCount($conn) {
    $count = getNotificationCount($conn);
    $update = "UPDATE admin_users SET notification_count = ?";
    $stmt = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param($stmt, "i", $count);
    return mysqli_stmt_execute($stmt);
}

function getAdminNotificationCount($conn, $admin_id) {
    $query = "SELECT notification_count FROM admin_users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['notification_count'] ?? 0;
}

function formatNotificationTime($timestamp) {
    $time = strtotime($timestamp);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return "Just now";
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
    } else {
        $days = floor($diff / 86400);
        return $days . " day" . ($days > 1 ? "s" : "") . " ago";
    }
}

function notifyAdminOfContactMessage($conn, $name, $email, $message) {
    $notification_title = "New Contact Message";
    $notification_message = "New message from $name ($email): " . substr($message, 0, 100) . (strlen($message) > 100 ? "..." : "");
    return createNotification($conn, 'message', $notification_title, $notification_message, null, null);
}

function sendOrderStatusEmail($to, $name, $orderId, $status) {
    $subject = "Your Order #$orderId Status Update";
    if ($status == 'completed') {
        $message = "Dear $name,\n\nYour order #$orderId has been completed! Thank you for shopping with us.\n\nBest regards,\nFruitables Team";
    } elseif ($status == 'rejected' || $status == 'cancelled') {
        $message = "Dear $name,\n\nWe regret to inform you that your order #$orderId has been rejected. Please contact support for more information.\n\nBest regards,\nFruitables Team";
    } else {
        return;
    }
    $headers = "From: no-reply@fruitables.com\r\n";
    mail($to, $subject, $message, $headers);
}
?> 