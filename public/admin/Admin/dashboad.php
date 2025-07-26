<?php
session_start();
if (!isset($_SESSION['admin_name'])) {
?>
<script>
    let a = confirm("Please Sign In First.");
    if (a) {
        window.location.href = "./signin.php";
    } else {
        alert("Chal pir nikal")
    }
</script>
<?php
    exit();
} else {
    $admin_id = $_SESSION['admin_id'];
    $admin_name = $_SESSION['admin_name'];
    $admin_email = $_SESSION['admin_email'];
    $admin_img = $_SESSION['admin_img'];
    include '../conn.php';
include './notification_helper.php';
    
    // Get notification count
    $notification_count = getAdminNotificationCount($conn, $admin_id);
    $select = "SELECT * FROM `products`";
    $get1 = mysqli_query($conn, $select);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboad</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="./assets/CSS/dashboad.css">
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
                        <img src="./assets/Admin_img/<?php echo $admin_img; ?>" alt=""
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
                    <li class="active"><a href="./dashboad.php"><i class="fa-solid fa-gauge-high"></i>Dashboard</a></li>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><a href="./orders.php"><i class="fa-solid fa-shopping-cart"></i>Orders</a></li>
                    <li><i class="fa-solid fa-users"></i>Customers</li>
                    <li><i class="fa-solid fa-chart-simple"></i>Reports</li>
                    <li><a href="./logout.php"><i class="fa-solid fa-sign-out-alt"></i>Logout</a></li>
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
                                <li class="position-relative">
                                    <i class="fa-solid fa-bell" id="notificationIcon" style="cursor: pointer;"></i>
                                    <?php if ($notification_count > 0): ?>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                            <?php echo $notification_count; ?>
                                        </span>
                                    <?php endif; ?>
                                    <div class="dropdown-menu notification-dropdown" id="notificationDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                                        <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                            <h6 class="mb-0">Notifications</h6>
                                            <?php if ($notification_count > 0): ?>
                                                <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">Mark all read</button>
                                            <?php endif; ?>
                                        </div>
                                        <div id="notificationList">
                                            <?php
                                            $notifications = getUnreadNotifications($conn, 10);
                                            if (mysqli_num_rows($notifications) > 0):
                                                while ($notification = mysqli_fetch_assoc($notifications)):
                                                    $isMessage = $notification['type'] === 'message';
                                            ?>
                                                <div class="notification-item p-2 border-bottom" data-id="<?php echo $notification['id']; ?>" data-type="<?php echo $notification['type']; ?>" data-message="<?php echo htmlspecialchars($notification['message']); ?>">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <strong>
                                                            <?php if ($isMessage): ?>
                                                                <i class="fa-solid fa-envelope text-primary"></i>
                                                            <?php else: ?>
                                                                <i class="fa-solid fa-cart-shopping text-success"></i>
                                                            <?php endif; ?>
                                                            <?php echo htmlspecialchars($notification['title']); ?>
                                                        </strong>
                                                        <small class="text-muted"><?php echo formatNotificationTime($notification['created_at']); ?></small>
                                                    </div>
                                                    <p class="mb-1 small">
                                                        <?php echo htmlspecialchars($notification['message']); ?>
                                                    </p>
                                                    <?php if ($isMessage): ?>
                                                        <button class="btn btn-sm btn-outline-info view-message-btn" data-message="<?php echo htmlspecialchars($notification['message']); ?>">View Message</button>
                                                    <?php elseif ($notification['order_id']): ?>
                                                        <a href="./orders.php?highlight=<?php echo $notification['order_id']; ?>" class="btn btn-sm btn-outline-success">View Order</a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php 
                                                endwhile;
                                            else:
                                            ?>
                                                <div class="p-3 text-center text-muted">
                                                    <i class="fas fa-bell-slash"></i>
                                                    <p class="mb-0">No new notifications</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <img src="./assets/Admin_img/<?php echo $admin_img; ?>" alt=""
                                        style="width: 40px; height: 40px; border-radius: 50%;">
                                    <select name="" id="">
                                        <option value="">
                                            <?php echo $admin_email ?>
                                        </option>
                                        <option value="">
                                            <?php echo $admin_name ?>
                                        </option>
                                    </select>
                                </li>
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
                                <td>
                                    <?php echo $result['category'];?>
                                </td>
                                <td><img src="../../assets/Product_Images/<?php echo $result['image']; ?>"
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
    <script>
        // Notification functionality
        document.addEventListener('DOMContentLoaded', function() {
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            // Toggle notification dropdown
            notificationIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.classList.toggle('show');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!notificationIcon.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
            });
            
            // Mark notification as read when clicked
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const notificationId = this.dataset.id;
                    markNotificationAsRead(notificationId);
                });
            });
            
            // Auto-refresh notifications every 30 seconds
            setInterval(refreshNotifications, 30000);

            document.querySelectorAll('.view-message-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const message = this.getAttribute('data-message');
                    document.getElementById('messageModalBody').textContent = message;
                    var modal = new bootstrap.Modal(document.getElementById('messageModal'));
                    modal.show();
                });
            });
        });
        
        function markNotificationAsRead(notificationId) {
            fetch('./notification_actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=mark_read&id=' + notificationId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the notification item
                    const item = document.querySelector(`[data-id="${notificationId}"]`);
                    if (item) {
                        item.remove();
                    }
                    updateNotificationCount();
                }
            });
        }
        
        function markAllAsRead() {
            fetch('./notification_actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=mark_all_read'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear all notifications
                    document.getElementById('notificationList').innerHTML = 
                        '<div class="p-3 text-center text-muted"><i class="fas fa-bell-slash"></i><p class="mb-0">No new notifications</p></div>';
                    updateNotificationCount();
                }
            });
        }
        
        function refreshNotifications() {
            fetch('./notification_actions.php?action=get_count')
            .then(response => response.json())
            .then(data => {
                if (data.count !== undefined) {
                    updateNotificationBadge(data.count);
                }
            });
        }
        
        function updateNotificationCount() {
            fetch('./notification_actions.php?action=get_count')
            .then(response => response.json())
            .then(data => {
                updateNotificationBadge(data.count);
            });
        }
        
        function updateNotificationBadge(count) {
            const badge = document.querySelector('.badge');
            if (count > 0) {
                if (badge) {
                    badge.textContent = count;
                } else {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                    newBadge.style.fontSize = '0.6rem';
                    newBadge.textContent = count;
                    document.getElementById('notificationIcon').appendChild(newBadge);
                }
            } else {
                if (badge) {
                    badge.remove();
                }
            }
        }
    </script>
    <style>
        .notification-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            z-index: 1000;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .notification-dropdown.show {
            display: block;
        }
        
        .notification-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        
        .notification-item:last-child {
            border-bottom: none !important;
        }
    </style>
    <!-- Add this modal at the end of the file before </body> -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="messageModalLabel">Contact Message</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="messageModalBody">
            <!-- Message content will be loaded here -->
          </div>
        </div>
      </div>
    </div>
</body>

</html>
<?php

}
?>
