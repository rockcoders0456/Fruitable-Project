<?php
session_start();
if (!isset($_SESSION['admin_name'])) {
?>
    <script>
        let a = confirm("Please Sign In First.");
        if (a) {
            window.location.href = "./signin.php";
        } else {
            alert("Please sign in to access admin panel")
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

    // Get orders from database (you'll need to create an orders table)
    $select = "SELECT * FROM orders ORDER BY order_date DESC";
    $result = mysqli_query($conn, $select);

    // Check if we need to highlight a specific order
    $highlight_order = $_GET['highlight'] ?? null;
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Orders Management</title>
        <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="../../assets/CSS/dashboad.css">
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
                            <img src="../../assets/Admin_img/<?php echo $admin_img; ?>" alt=""
                                style="width: 50px; height: 50px; border-radius: 50%;">
                        </div>
                        <div class="name">
                            <b><?php echo $admin_name; ?></b>
                            <p>Admin</p>
                        </div>
                    </div>
                    <ul>
                        <li><a href="./dashboad.php"><i class="fa-solid fa-gauge-high"></i>Dashboard</a></li>
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
                        <li class="active"><i class="fa-solid fa-shopping-cart"></i>Orders</li>
                        <li><i class="fa-solid fa-users"></i>Customers</li>
                        <li><i class="fa-solid fa-chart-simple"></i>Reports</li>
                        <li><a href="./logout.php"><i class="fa-solid fa-sign-out-alt"></i>Logout</a></li>
                    </ul>
                </div>
                <div class="col-10 main">
                    <div class="row">
                        <div class="col-12">
                            <h2>Orders Management</h2>
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Orders</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (mysqli_num_rows($result) > 0): ?>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Customer</th>
                                                    <th>Email</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                                                    <tr <?php echo ($highlight_order && $highlight_order == $order['id']) ? 'class="table-warning"' : ''; ?>
                                                        <?php echo ($highlight_order && $highlight_order == $order['id']) ? 'id="highlighted-order"' : ''; ?>>
                                                        <td>#<?php echo $order['id']; ?></td>
                                                        <td><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
                                                        <td><?php echo $order['email']; ?></td>
                                                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $order['status'] == 'pending' ? 'warning' : ($order['status'] == 'completed' ? 'success' : ($order['status'] == 'rejected' || $order['status'] == 'cancelled' ? 'danger' : 'info')); ?>">
                                                                <?php echo ucfirst($order['status']); ?>
                                                            </span>
                                                        </td>
                                                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary" onclick="viewOrder(<?php echo $order['id']; ?>)">
                                                                <i class="fa-solid fa-eye"></i> View
                                                            </button>
                                                            <button class="btn btn-sm btn-success" onclick="updateStatus(<?php echo $order['id']; ?>, 'completed')" <?php echo $order['status'] == 'completed' ? 'disabled' : ''; ?>>
                                                                <i class="fa-solid fa-check"></i> Complete
                                                            </button>
                                                            <button class="btn btn-sm btn-danger" onclick="updateStatus(<?php echo $order['id']; ?>, 'rejected')" <?php echo ($order['status'] == 'rejected' || $order['status'] == 'rejected' || $order['status'] == 'cancelled') ? 'disabled' : ''; ?>>
                                                                <i class="fa-solid fa-xmark"></i> Reject
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <div class="text-center py-5">
                                            <i class="fa-solid fa-shopping-cart fa-3x text-muted mb-3"></i>
                                            <h4>No Orders Yet</h4>
                                            <p class="text-muted">Orders will appear here when customers place them.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../assets/Laibraries/js/bootstrap.bundle.min.js"></script>
        <!-- Order Details Modal -->
        <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="orderDetailsBody">
                        <!-- Order details will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Scroll to highlighted order if exists
            document.addEventListener('DOMContentLoaded', function() {
                const highlightedOrder = document.getElementById('highlighted-order');
                if (highlightedOrder) {
                    highlightedOrder.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    // Remove highlight after 3 seconds
                    setTimeout(() => {
                        highlightedOrder.classList.remove('table-warning');
                    }, 3000);
                }
            });

            function viewOrder(orderId) {
                fetch('order_action.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'action=view&order_id=' + orderId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const order = data.order;
                            let html = `<h5>Order #${order.id}</h5>`;
                            html += `<p><strong>Customer:</strong> ${order.first_name} ${order.last_name}</p>`;
                            html += `<p><strong>Email:</strong> ${order.email}</p>`;
                            html += `<p><strong>Phone:</strong> ${order.phone}</p>`;
                            html += `<p><strong>Address:</strong> ${order.address}, ${order.city}, ${order.country}, ${order.postal_code}</p>`;
                            html += `<p><strong>Company:</strong> ${order.company_name || '-'}</p>`;
                            html += `<p><strong>Notes:</strong> ${order.notes || '-'}</p>`;
                            html += `<p><strong>Status:</strong> <span class="badge bg-${order.status == 'pending' ? 'warning' : (order.status == 'completed' ? 'success' : 'info')}">${order.status}</span></p>`;
                            html += `<p><strong>Order Date:</strong> ${order.order_date}</p>`;
                            html += `<hr><h6>Order Items</h6>`;
                            html += `<table class='table table-bordered'><thead><tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th></tr></thead><tbody>`;
                            order.items.forEach(item => {
                                html += `<tr><td>${item.product_name}</td><td>${item.quantity}</td><td>$${parseFloat(item.price).toFixed(2)}</td><td>$${parseFloat(item.total).toFixed(2)}</td></tr>`;
                            });
                            html += `</tbody></table>`;
                            html += `<p class='text-end'><strong>Order Total: $${parseFloat(order.total_amount).toFixed(2)}</strong></p>`;
                            document.getElementById('orderDetailsBody').innerHTML = html;
                            var modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
                            modal.show();
                        } else {
                            alert('Failed to load order details: ' + data.error);
                        }
                    });
            }

            function updateStatus(orderId, status) {
                if (status === 'rejected') {
                    if (!confirm('Are you sure you want to reject this order?')) return;
                } else if (status === 'completed') {
                    if (!confirm('Are you sure you want to mark this order as completed?')) return;
                }
                fetch('order_action.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'action=update_status&order_id=' + orderId + '&status=' + status
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Order status updated successfully!');
                            location.reload();
                        } else {
                            alert('Failed to update order status: ' + data.error);
                        }
                    });
            }
        </script>
    </body>

    </html>
<?php
}
?>