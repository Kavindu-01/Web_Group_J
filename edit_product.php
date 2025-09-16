<?php
include '../includes/db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$product_id = (int)$_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE product_id = $product_id")->fetch_assoc();

if (!$product) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;
    
    $image_path = $product['image_path'];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $image_name = time() . '_' . $_FILES['image']['name'];
        $new_image_path = 'uploads/' . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
            // Delete old image if it exists
            if ($image_path && file_exists('../' . $image_path)) {
                unlink('../' . $image_path);
            }
            $image_path = $new_image_path;
        }
    }
    
    // Check if is_best_seller column exists
    $check_column = $conn->query("SHOW COLUMNS FROM products LIKE 'is_best_seller'");
    if ($check_column->num_rows > 0) {
        // New method: Use is_best_seller column
        $sql = "UPDATE products SET 
                name = '$name', 
                category = '$category', 
                price = $price, 
                stock = $stock, 
                description = '$description', 
                image_path = '$image_path', 
                is_new_arrival = $is_new_arrival,
                is_best_seller = $is_best_seller
                WHERE product_id = $product_id";
    } else {
        // Old method: Use category field only
        $sql = "UPDATE products SET 
                name = '$name', 
                category = '$category', 
                price = $price, 
                stock = $stock, 
                description = '$description', 
                image_path = '$image_path', 
                is_new_arrival = $is_new_arrival 
                WHERE product_id = $product_id";
    }
    
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="admin-nav">
        <h2>Edit Product</h2>
        <div class="admin-user">
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="admin-form-container">
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="mens" <?php echo $product['category'] === 'mens' ? 'selected' : ''; ?>>Men's</option>
                    <option value="womens" <?php echo $product['category'] === 'womens' ? 'selected' : ''; ?>>Women's</option>
                    <option value="kids" <?php echo $product['category'] === 'kids' ? 'selected' : ''; ?>>Kids</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Current Image</label>
                <?php if ($product['image_path']) { ?>
                    <img src="../<?php echo $product['image_path']; ?>" alt="Current Image" style="max-width: 200px;">
                <?php } ?>
            </div>
            
            <div class="form-group">
                <label>New Product Image (optional)</label>
                <input type="file" name="image" accept="image/*">
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_new_arrival" <?php echo $product['is_new_arrival'] ? 'checked' : ''; ?>>
                    Mark as New Arrival
                </label>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_best_seller" <?php 
                    // Check if is_best_seller column exists
                    $check_column = $conn->query("SHOW COLUMNS FROM products LIKE 'is_best_seller'");
                    if ($check_column->num_rows > 0) {
                        echo isset($product['is_best_seller']) && $product['is_best_seller'] ? 'checked' : '';
                    } else {
                        echo $product['category'] === 'best_sellers' ? 'checked' : '';
                    }
                    ?>>
                    Mark as Best Seller
                </label>
            </div>
            
            <button type="submit" class="btn-primary">Update Product</button>
        </form>
    </div>
</body>
</html>