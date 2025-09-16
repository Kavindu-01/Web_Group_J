<?php
include '../includes/db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
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
    
    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_path = 'uploads/' . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
            // Image uploaded successfully
        } else {
            $error = "Failed to upload image";
        }
    }
    
    if (!isset($error)) {
        // Check if is_best_seller column exists
        $check_column = $conn->query("SHOW COLUMNS FROM products LIKE 'is_best_seller'");
        if ($check_column->num_rows > 0) {
            // New method: Use is_best_seller column
            $sql = "INSERT INTO products (name, category, price, stock, description, image_path, is_new_arrival, is_best_seller) 
                    VALUES ('$name', '$category', $price, $stock, '$description', '$image_path', $is_new_arrival, $is_best_seller)";
        } else {
            // Old method: Use category field only
            $sql = "INSERT INTO products (name, category, price, stock, description, image_path, is_new_arrival) 
                    VALUES ('$name', '$category', $price, $stock, '$description', '$image_path', $is_new_arrival)";
        }
        
        if ($conn->query($sql) === TRUE) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #334155;
            line-height: 1.6;
        }
        
        .admin-nav {
            background: linear-gradient(135deg, #1e293b, #334155);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-nav h2 {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .admin-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .admin-nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }
        
        .admin-form-container {
            max-width: 700px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .form-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            padding: 2rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-subtitle {
            color: #64748b;
            font-size: 1rem;
        }
        
        .form-body {
            padding: 2rem;
        }
        
        .error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 0.75rem;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .checkbox-group:hover {
            border-color: #3b82f6;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin: 0;
            transform: scale(1.2);
        }
        
        .checkbox-group label {
            margin: 0;
            font-weight: 500;
            cursor: pointer;
        }
        
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        
        .file-input {
            position: absolute;
            left: -9999px;
        }
        
        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 2rem;
            border: 2px dashed #d1d5db;
            border-radius: 0.75rem;
            background: #f9fafb;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6b7280;
            font-weight: 500;
        }
        
        .file-input-label:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #3b82f6;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            justify-content: center;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }
        
        @media (max-width: 768px) {
            .admin-nav {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .admin-form-container {
                padding: 0 1rem;
            }
            
            .form-body {
                padding: 1.5rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <h2><i class="fas fa-plus-circle"></i> Add New Product</h2>
        <div class="admin-user">
            <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>

    <div class="admin-form-container">
        <div class="form-card">
            <div class="form-header">
                <h1 class="form-title">
                    <i class="fas fa-box-open"></i>
                    Create New Product
                </h1>
                <p class="form-subtitle">Add a new product to your store inventory</p>
            </div>
            
            <div class="form-body">
                <?php if (isset($error)) { ?>
                    <div class="error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label><i class="fas fa-tag"></i> Product Name</label>
                            <input type="text" name="name" placeholder="Enter product name" required>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-list"></i> Category</label>
                            <select name="category" required>
                                <option value="">Select category</option>
                                <option value="mens">Men's Fashion</option>
                                <option value="womens">Women's Fashion</option>
                                <option value="kids">Kids Collection</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-dollar-sign"></i> Price</label>
                            <input type="number" name="price" step="0.01" placeholder="0.00" required>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-boxes"></i> Stock Quantity</label>
                            <input type="number" name="stock" placeholder="Enter quantity" required>
                        </div>
                        
                        <div class="form-group full-width">
                            <label><i class="fas fa-align-left"></i> Product Description</label>
                            <textarea name="description" rows="4" placeholder="Enter detailed product description..."></textarea>
                        </div>
                        
                        <div class="form-group full-width">
                            <label><i class="fas fa-image"></i> Product Image</label>
                            <div class="file-input-wrapper">
                                <input type="file" name="image" accept="image/*" required class="file-input" id="image-upload">
                                <label for="image-upload" class="file-input-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    Click to upload image or drag and drop
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group full-width">
                            <div class="checkbox-group">
                                <input type="checkbox" name="is_new_arrival" id="new-arrival">
                                <label for="new-arrival">
                                    <i class="fas fa-star"></i>
                                    Mark as New Arrival
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group full-width">
                            <div class="checkbox-group">
                                <input type="checkbox" name="is_best_seller" id="best-seller">
                                <label for="best-seller">
                                    <i class="fas fa-fire"></i>
                                    Mark as Best Seller
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Add Product to Store
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>