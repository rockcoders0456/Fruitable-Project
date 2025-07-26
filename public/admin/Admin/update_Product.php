<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_name'])) {
    header("Location: ./signin.php");
    exit();
}

include '../conn.php';

$error = '';
$success = '';

// Get product ID from URL
$ids = $_GET['id'] ?? '';

if (empty($ids)) {
    header("Location: ./dashboad.php");
    exit();
}

// Fetch existing product data
$select = "SELECT * FROM products WHERE id = '$ids'";
$query = mysqli_query($conn, $select);

if (!$query || mysqli_num_rows($query) == 0) {
    header("Location: ./dashboad.php");
    exit();
}

$result = mysqli_fetch_assoc($query);

if (isset($_POST['submit'])) {
    $tittle = trim($_POST['tittle']);
    $price = $_POST['price'];
    $category = $_POST['category'];
    $details = trim($_POST['detail']);
    
    // Validation
    if (empty($tittle) || empty($price) || empty($category) || empty($details)) {
        $error = "Please fill in all fields";
    } elseif ($price <= 0) {
        $error = "Price must be greater than 0";
    } else {
        // Handle file upload
        $img = $result['image']; // Keep existing image by default
        
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $file_type = $_FILES['file']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $name = $_FILES['file']['name'];
                $tempname = $_FILES['file']['tmp_name'];
                $newname = rand(1111, 9999) . '_' . $name;
                $target = '../../assets/Product_Images/';
                
                // Create directory if it doesn't exist
                if (!is_dir($target)) {
                    mkdir($target, 0777, true);
    }
                
                if (move_uploaded_file($tempname, $target . $newname)) {
                    $img = $newname;
                } else {
                    $error = "Image upload failed. Please try again.";
                }
            } else {
                $error = "Please upload a valid image file (JPEG, PNG, GIF, WebP)";
            }
        }
        
        if (empty($error)) {
            // Update product using core PHP
            $update = "UPDATE products SET tittle = '$tittle', details = '$details', price = $price, category = '$category', image = '$img' WHERE id = '$ids'";
            
            $update_query = mysqli_query($conn, $update);
            
            if ($update_query) {
                $success = "Product updated successfully!";
                // Refresh product data
                $select = "SELECT * FROM products WHERE id = '$ids'";
                $query = mysqli_query($conn, $select);
                $result = mysqli_fetch_assoc($query);
            } else {
                $error = "Error updating product: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product - Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-top: 20px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            color: #333;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .current-image {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .current-image img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .current-image p {
            margin-top: 10px;
            color: #666;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control.file-input {
            padding: 12px 20px;
            background: #fff;
            border: 2px dashed #667eea;
            cursor: pointer;
        }

        .form-control.file-input:hover {
            border-color: #5a6fd8;
            background: #f8f9ff;
        }

        .form-select {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 10px 5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
            color: #212529;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-actions {
            text-align: center;
            margin-top: 30px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #5a6fd8;
        }

        .file-preview {
            margin-top: 10px;
            text-align: center;
        }

        .file-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .price-input {
            position: relative;
        }

        .price-input::after {
            content: "$/Kg";
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-weight: 500;
        }

        .price-input input {
            padding-right: 60px;
        }

        .product-info {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .product-info h4 {
            color: #1976d2;
            margin-bottom: 10px;
        }

        .product-info p {
            color: #424242;
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .form-container {
                margin: 10px;
                padding: 30px 20px;
            }

            .form-header h1 {
                font-size: 2rem;
            }

            .btn {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1><i class="fa-solid fa-edit"></i> Update Product</h1>
                <p>Modify the details below to update the product information</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="product-info">
                <h4><i class="fa-solid fa-info-circle"></i> Product Information</h4>
                <p><strong>Product ID:</strong> #<?php echo $result['id']; ?></p>
                <p><strong>Current Image:</strong> <?php echo $result['image']; ?></p>
                <p><strong>Last Updated:</strong> <?php echo $result['created_at']; ?></p>
            </div>

            <form action="" method="post" enctype="multipart/form-data" id="updateProductForm">
                <div class="current-image">
                    <h5><i class="fa-solid fa-image"></i> Current Product Image</h5>
                    <img src="../../assets/Product_Images/<?php echo $result['image']; ?>" alt="Current Product Image">
                    <p>Upload a new image below to replace the current one</p>
                </div>

                <div class="form-group">
                    <label for="file" class="form-label">
                        <i class="fa-solid fa-image"></i> New Product Image (Optional)
                    </label>
                    <input type="file" id="file" name="file" class="form-control file-input" accept="image/*">
                    <div class="file-preview" id="filePreview"></div>
                </div>

                <div class="form-group">
                    <label for="tittle" class="form-label">
                        <i class="fa-solid fa-tag"></i> Product Title
                    </label>
                    <input type="text" id="tittle" name="tittle" class="form-control" 
                           placeholder="Enter product title" required 
                           value="<?php echo htmlspecialchars($result['tittle']); ?>">
                    </div>

                <div class="form-group">
                    <label for="price" class="form-label">
                        <i class="fa-solid fa-dollar-sign"></i> Product Price
                    </label>
                    <div class="price-input">
                        <input type="number" id="price" name="price" class="form-control" 
                               placeholder="0.00" step="0.01" min="0" required 
                               value="<?php echo htmlspecialchars($result['price']); ?>">
                    </div>
                    </div>

                <div class="form-group">
                    <label for="category" class="form-label">
                        <i class="fa-solid fa-list"></i> Category
                    </label>
                    <select id="category" name="category" class="form-select" required>
                        <option value="Fruit" <?php echo ($result['category'] == 'Fruit') ? 'selected' : ''; ?>>Fruit</option>
                        <option value="Vegetable" <?php echo ($result['category'] == 'Vegetable') ? 'selected' : ''; ?>>Vegetable</option>
                        </select>
                    </div>

                <div class="form-group">
                    <label for="detail" class="form-label">
                        <i class="fa-solid fa-align-left"></i> Product Details
                    </label>
                    <textarea id="detail" name="detail" class="form-textarea" 
                              placeholder="Enter product description..." required><?php echo htmlspecialchars($result['details']); ?></textarea>
                    </div>

                <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-save"></i> Update Product
                    </button>
                    <a href="./dashboad.php" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                    </a>
                    <a href="./delete_Product.php?id=<?php echo $result['id']; ?>" class="btn btn-warning" 
                       onclick="return confirm('Are you sure you want to delete this product?')">
                        <i class="fa-solid fa-trash"></i> Delete Product
                    </a>
                    </div>
                </form>

            <div class="text-center">
                <a href="./dashboad.php" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> View All Products
                </a>
            </div>
        </div>
    </div>

    <script src="../../assets/Laibraries/js/bootstrap.bundle.min.js"></script>
    <script>
        // File preview functionality
        document.getElementById('file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('filePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<h6>New Image Preview:</h6><img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Form validation and enhancement
        document.getElementById('updateProductForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
        });

        // Price input validation
        document.getElementById('price').addEventListener('input', function() {
            if (this.value < 0) {
                this.value = 0;
            }
        });

        // Character counter for textarea
        document.getElementById('detail').addEventListener('input', function() {
            const maxLength = 500;
            const currentLength = this.value.length;
            
            if (currentLength > maxLength) {
                this.value = this.value.substring(0, maxLength);
            }
        });

        // Confirm before leaving page if form is modified
        let formModified = false;
        const form = document.getElementById('updateProductForm');
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                formModified = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (formModified) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Reset form modified flag when form is submitted
        form.addEventListener('submit', function() {
            formModified = false;
        });
    </script>
</body>

</html>