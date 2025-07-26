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
        $img = '';
        
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
        } else {
            $error = "Please select an image file";
        }
        
        if (empty($error)) {
            // Insert product using core PHP
            $insert = "INSERT INTO products (tittle, details, price, category, image) VALUES ('$tittle', '$details', $price, '$category', '$img')";
            
            $insert_query = mysqli_query($conn, $insert);
            
            if ($insert_query) {
                $success = "Product added successfully!";
                // Clear form data
                $_POST = array();
            } else {
                $error = "Error adding product: " . mysqli_error($conn);
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
    <title>Add Product - Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/Laibraries/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="./assets/CSS/add_Product.css">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1><i class="fa-solid fa-plus-circle"></i> Add New Product</h1>
                <p>Fill in the details below to add a new product to your store</p>
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

            <form action="" method="post" enctype="multipart/form-data" id="addProductForm">
                <div class="form-group">
                    <label for="file" class="form-label">
                        <i class="fa-solid fa-image"></i> Product Image
                    </label>
                    <input type="file" id="file" name="file" class="form-control file-input" accept="image/*" required>
                    <div class="file-preview" id="filePreview"></div>
                </div>

                <div class="form-group">
                    <label for="tittle" class="form-label">
                        <i class="fa-solid fa-tag"></i> Product Title
                    </label>
                    <input type="text" id="tittle" name="tittle" class="form-control" 
                           placeholder="Enter product title" required 
                           value="<?php echo isset($_POST['tittle']) ? htmlspecialchars($_POST['tittle']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="price" class="form-label">
                        <i class="fa-solid fa-dollar-sign"></i> Product Price
                    </label>
                    <div class="price-input">
                        <input type="number" id="price" name="price" class="form-control" 
                               placeholder="0.00" step="0.01" min="0" required 
                               value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">
                        <i class="fa-solid fa-list"></i> Category
                    </label>
                    <select id="category" name="category" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="Fruit" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Fruit') ? 'selected' : ''; ?>>Fruit</option>
                        <option value="Vegetable" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Vegetable') ? 'selected' : ''; ?>>Vegetable</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="detail" class="form-label">
                        <i class="fa-solid fa-align-left"></i> Product Details
                    </label>
                    <textarea id="detail" name="detail" class="form-textarea" 
                              placeholder="Enter product description..." required><?php echo isset($_POST['detail']) ? htmlspecialchars($_POST['detail']) : ''; ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-plus"></i> Add Product
                    </button>
                    <a href="./dashboad.php" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
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
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Form validation and enhancement
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
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
    </script>
</body>

</html>