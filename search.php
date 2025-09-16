<?php
include 'includes/db_connection.php';

$search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$products = [];

if ($search_term) {
    // Search for products
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_term%' OR description LIKE '%$search_term%'";
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
        
        // Track search
        $product_id = $row['product_id'];
        $check_search = $conn->query("SELECT * FROM product_searches WHERE product_id = $product_id");
        
        if ($check_search->num_rows > 0) {
            $conn->query("UPDATE product_searches SET search_count = search_count + 1, last_searched = NOW() WHERE product_id = $product_id");
        } else {
            $conn->query("INSERT INTO product_searches (product_id, search_count, last_searched) VALUES ($product_id, 1, NOW())");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Liora Store</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="search-container">
        <h1>Search Results</h1>
        <?php if ($search_term) { ?>
            <p>Showing results for: "<strong><?php echo htmlspecialchars($search_term); ?></strong>"</p>
            
            <?php if (empty($products)) { ?>
                <p>No products found matching your search.</p>
            <?php } else { ?>
                <div class="products-grid">
                    <?php foreach ($products as $product) { ?>
                        <div class="product-card">
                            <img src="<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>">
                            <h3><?php echo $product['name']; ?></h3>
                            <p class="price">$<?php echo $product['price']; ?></p>
                            <button class="add-to-cart" data-product-id="<?php echo $product['product_id']; ?>">Add to Cart</button>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>Please enter a search term.</p>
        <?php } ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + productId
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        document.querySelector('.cart-count').textContent = data.cartCount;
                    }
                });
            });
        });
    </script>

    <style>
        .search-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .product-card h3 {
            margin: 10px 0;
        }

        .price {
            font-weight: bold;
            font-size: 18px;
            margin: 10px 0;
        }

        .add-to-cart {
            background-color: #000;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .add-to-cart:hover {
            background-color: #333;
        }
    </style>

    <!-- Enhanced Visual Effects Scripts -->
    <script src="Scripts/cursor.js"></script>
    <script src="Scripts/particles.js"></script>
</body>
</html>