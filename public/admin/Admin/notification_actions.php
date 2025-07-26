<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

include '../conn.php';
include './notification_helper.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'mark_read':
            $notification_id = $_POST['id'] ?? 0;
            if ($notification_id > 0) {
                if (markNotificationAsRead($conn, $notification_id)) {
                    $response['success'] = true;
                    $response['message'] = 'Notification marked as read';
                }
            }
            break;
            
        case 'mark_all_read':
            if (markAllNotificationsAsRead($conn)) {
                $response['success'] = true;
                $response['message'] = 'All notifications marked as read';
            }
            break;
            
        default:
            $response['error'] = 'Invalid action';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'get_count':
            $count = getNotificationCount($conn);
            $response['success'] = true;
            $response['count'] = $count;
            break;
            
        case 'get_notifications':
            $notifications = [];
            $result = getUnreadNotifications($conn, 10);
            while ($row = mysqli_fetch_assoc($result)) {
                $notifications[] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'message' => $row['message'],
                    'order_id' => $row['order_id'],
                    'created_at' => formatNotificationTime($row['created_at'])
                ];
            }
            $response['success'] = true;
            $response['notifications'] = $notifications;
            break;
            
        default:
            $response['error'] = 'Invalid action';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?> 