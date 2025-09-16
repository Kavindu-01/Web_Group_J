<?php
include '../includes/db_connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $product_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM products WHERE product_id = $product_id");
    header("Location: dashboard.php");
    exit();
}

// Get all products
$products_sql = "SELECT * FROM products ORDER BY created_at DESC";
$products = $conn->query($products_sql);

// Get statistics
$stats_sql = "SELECT 
    (SELECT COUNT(*) FROM products) as total_products,
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT COUNT(*) FROM orders) as total_orders,
    (SELECT SUM(total_amount) FROM orders) as total_revenue";
$stats = $conn->query($stats_sql)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Liora Store</title>
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
            font-weight: 500;
        }
        
        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .admin-nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }
        
        .admin-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: #64748b;
            font-size: 1.1rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        }
        
        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }
        
        .stat-card:nth-child(1) .stat-icon { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; }
        .stat-card:nth-child(2) .stat-icon { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #16a34a; }
        .stat-card:nth-child(3) .stat-icon { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; }
        .stat-card:nth-child(4) .stat-icon { background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #db2777; }
        
        .stat-card h3 {
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 0.5rem;
        }
        
        .stat-card p {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
        }
        
        .actions {
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }
        
        .products-section {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        
        .products-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        
        .products-header h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .products-table {
            overflow-x: auto;
        }
        
        .products-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .products-table th {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .products-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        
        .products-table tr:hover {
            background: #f8fafc;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 0.5rem;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }
        
        .product-name {
            font-weight: 600;
            color: #1e293b;
        }
        
        .category-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .category-mens { background: #dbeafe; color: #1e40af; }
        .category-womens { background: #fce7f3; color: #be185d; }
        .category-kids { background: #fef3c7; color: #92400e; }
        .category-best_sellers { background: #dcfce7; color: #166534; }
        
        .price {
            font-weight: 700;
            color: #059669;
            font-size: 1.1rem;
        }
        
        .stock {
            font-weight: 600;
        }
        
        .stock.low { color: #dc2626; }
        .stock.medium { color: #d97706; }
        .stock.high { color: #059669; }
        
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-secondary { background: #f1f5f9; color: #475569; }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-edit, .btn-delete {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .btn-edit {
            background: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        
        .btn-edit:hover {
            background: #0369a1;
            color: white;
        }
        
        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .btn-delete:hover {
            background: #dc2626;
            color: white;
        }
        
        @media (max-width: 768px) {
            .admin-nav {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-container {
                padding: 0 1rem;
            }
            
            .products-table {
                font-size: 0.875rem;
            }
            
            .products-table th,
            .products-table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
        <div class="admin-user">
            <span>Welcome, <?php echo $_SESSION['admin_username']; ?></span>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>

    <div class="admin-container">
        <div class="page-header">
            <h1 class="page-title">Dashboard Overview</h1>
            <p class="page-subtitle">Monitor your store performance and manage products efficiently</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h3>Total Products</h3>
                <p><?php echo $stats['total_products']; ?></p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Total Users</h3>
                <p><?php echo $stats['total_users']; ?></p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Total Orders</h3>
                <p><?php echo $stats['total_orders']; ?></p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h3>Total Revenue</h3>
                <p>$<?php echo number_format($stats['total_revenue'] ?: 0, 2); ?></p>
            </div>
        </div>

        <div class="actions">
            <a href="add_product.php" class="btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>

        <div class="products-section">
            <div class="products-header">
                <h3><i class="fas fa-list"></i> Product Management</h3>
            </div>
            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>New Arrival</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($product = $products->fetch_assoc()) { 
                            $stock_class = '';
                            if ($product['stock'] <= 5) $stock_class = 'low';
                            elseif ($product['stock'] <= 20) $stock_class = 'medium';
                            else $stock_class = 'high';
                        ?>
                            <tr>
                                <td><strong>#<?php echo $product['product_id']; ?></strong></td>
                                <td>
                                    <img src="../<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                                </td>
                                <td class="product-name"><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>
                                    <span class="category-badge category-<?php echo $product['category']; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $product['category'])); ?>
                                    </span>
                                </td>
                                <td class="price">$<?php echo number_format($product['price'], 2); ?></td>
                                <td class="stock <?php echo $stock_class; ?>"><?php echo $product['stock']; ?></td>
                                <td>
                                    <span class="badge <?php echo $product['is_new_arrival'] ? 'badge-success' : 'badge-secondary'; ?>">
                                        <?php echo $product['is_new_arrival'] ? 'Yes' : 'No'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="?delete=<?php echo $product['product_id']; ?>" 
                                           class="btn-delete"
                                           onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>