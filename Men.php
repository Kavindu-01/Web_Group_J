
<?php
include '../includes/db_connection.php';
// ...existing PHP logic for filters and products...
$price_filter = isset($_GET['price']) ? $_GET['price'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$where_clause = "WHERE category = 'mens'";
if ($price_filter !== 'all') {
    $price_ranges = [
        'under50' => 'price < 50',
        '50to100' => 'price BETWEEN 50 AND 100',
        'over100' => 'price > 100'
    ];
    if (isset($price_ranges[$price_filter])) {
        $where_clause .= " AND " . $price_ranges[$price_filter];
    }
}
$order_clause = "ORDER BY ";
switch ($sort) {
    case 'price_asc':
        $order_clause .= "price ASC";
        break;
    case 'price_desc':
        $order_clause .= "price DESC";
        break;
    case 'name_asc':
        $order_clause .= "name ASC";
        break;
    default:
        $order_clause .= "created_at DESC";
}
$sql = "SELECT * FROM products $where_clause $order_clause";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mens Clothing - Liora</title>
    <link rel="stylesheet" href="Men.css">
    <link href="https://fonts.cdnfonts.com/css/neue-haas-grotesk-display-pro" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Neue Haas Grotesk Display Pro', Arial, sans-serif; background: beige; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
        
        /* Header Styles - Consistent with other pages */
        .site-header {
            background: #000000;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            letter-spacing: 2px;
        }
        
        .main-nav {
            display: flex;
            gap: 2rem;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-links li {
            margin: 0;
        }
        
        .main-nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            padding: 0.5rem 0;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .main-nav a:hover {
            color: #a0aec0;
        }
        
        .auth-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .cart-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.3s ease;
        }
        
        .cart-link:hover {
            color: #a0aec0;
        }
        
        .cart-count {
            background: #e53e3e;
            color: white;
            border-radius: 50%;
            padding: 0.2rem 0.6rem;
            font-size: 0.8rem;
            min-width: 1.5rem;
            text-align: center;
        }
        
        .auth-btn {
            background: white;
            color: #000000;
            padding: 0.5rem 1.5rem;
            border-radius: 2rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .auth-btn:hover {
            background: #f7fafc;
            transform: translateY(-1px);
        }
        
        .menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 0.5rem;
        }
        
        .mobile-menu {
            display: none;
            background: #000000;
            padding: 1rem 2rem;
            border-top: 1px solid #333333;
        }
        
        .mobile-menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 0.8rem 0;
            border-bottom: 1px solid #333333;
        }
        
        .mobile-menu a:last-child {
            border-bottom: none;
        }
        
        /* Men's Badge */
        .mens-badge {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 0.2rem 0.8rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .header-inner {
                padding: 0 1rem;
            }
            
            .main-nav {
                display: none;
            }
            
            .menu-btn {
                display: block;
            }
        }
        
        @media (max-width: 480px) {
            .header-inner {
                padding: 0 1rem;
            }
            
            .logo {
                font-size: 1.5rem;
            }
        }
        .cart-icon { display: flex; align-items: center; gap: 0.5rem; background: #333; border-radius: 2rem; padding: 0.5rem 1rem; cursor: pointer; transition: background 0.3s; color: white; }
        .cart-icon:hover { background: #555; }
        .cart-count { background: red; color: white; border-radius: 50%; padding: 0.2rem 0.7rem; font-size: 0.9rem; margin-left: 0.3rem; min-width: 1.5rem; text-align: center; }
        .auth-section { display: flex; align-items: center; gap: 1rem; }
        .auth-btn { background: white; color: black; padding: 0.5rem 1rem; border-radius: 25px; text-decoration: none; font-weight: 500; transition: all 0.3s; }
        .auth-btn:hover { background: #f0f0f0; color: black; }
        
        /* Hero Section */
        .hero { 
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80'); 
            background-size: cover; 
            background-position: center; 
            color: white; 
            padding: 4rem 0; 
            text-align: center; 
        }
        .hero h1 { font-size: 3.5rem; font-weight: 700; letter-spacing: 1px; margin: 0; color: white; }
        
        /* Marquee */
        .marquee { background: black; color: white; overflow: hidden; white-space: nowrap; font-size: 1.1rem; padding: 0.8rem 0; }
        .marquee__group { display: inline-block; animation: marquee 20s linear infinite; }
        .marquee__group span { margin-right: 3rem; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-100%); } }
        
        /* Category Section */
        .category-section { background: beige; padding: 3rem 0; }
        .filters { margin-bottom: 3rem; }
        .filter-title { font-size: 1.4rem; font-weight: 600; margin-bottom: 1.5rem; color: #333; text-align: center; }
        .filter-options { display: flex; gap: 3rem; justify-content: center; flex-wrap: wrap; }
        .filter-group { text-align: center; }
        .filter-group h4 { font-size: 1.1rem; margin-bottom: 0.8rem; color: #333; font-weight: 600; }
        .filter-buttons { display: flex; gap: 0.8rem; justify-content: center; }
        .filter-btn { background: #f0f0f0; color: #333; border: none; border-radius: 25px; padding: 0.6rem 1.5rem; font-size: 1rem; cursor: pointer; transition: all 0.3s; font-weight: 500; }
        .filter-btn.active, .filter-btn:hover { background: dimgrey; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(105, 105, 105, 0.3); }
        
        /* Products Grid */
        .products { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 2rem; }
        .product-card { background: bisque; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); padding: 1.5rem; text-align: center; transition: all 0.3s; overflow: hidden; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .product-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 12px; background: #f0f0f0; margin-bottom: 1rem; }
        .product-card h3 { font-size: 1.2rem; font-weight: 600; margin: 0.8rem 0 0.5rem 0; color: #333; }
        .product-card p { color: #333; font-weight: 600; font-size: 1.1rem; margin-bottom: 1rem; }
        .add-to-cart { background: dimgrey; color: white; border: none; border-radius: 25px; padding: 0.7rem 1.5rem; font-size: 1rem; cursor: pointer; transition: all 0.3s; font-weight: 500; width: 100%; }
        .add-to-cart:hover { background: #555; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(85, 85, 85, 0.3); }
        
        /* Form Styles */
        form { background: bisque; padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center; justify-content: center; flex-wrap: wrap; }
        form label { font-weight: 600; color: #333; white-space: nowrap; }
        form select { padding: 0.6rem 1rem; border-radius: 25px; border: 2px solid #e6e6e6; background: white; font-size: 1rem; transition: border-color 0.3s; }
        form select:focus { outline: none; border-color: dimgrey; }
        
        /* Footer Styles */
        footer { background: beige; padding: 3rem 0; border-top: 1px solid #ddd; margin-top: 3rem; }
        .footer-content { display: flex; flex-direction: column; align-items: center; justify-content: between; gap: 1rem; }
        .footer-content p { color: #666; font-size: 0.9rem; }
        .footer-links { display: flex; gap: 2rem; }
        .footer-links a { color: #333; text-decoration: none; font-size: 0.9rem; transition: color 0.3s; }
        .footer-links a:hover { color: dimgrey; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                padding: 1rem 0;
            }
            
            nav {
                display: none;
            }
            
            .menu-btn {
                display: block;
            }
            
            .hero h1 { font-size: 2.5rem; }
            .filter-options { gap: 1.5rem; flex-direction: column; }
            .filter-buttons { justify-content: center; }
            .products { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; }
            form { flex-direction: column; gap: 1rem; }
            .footer-content { flex-direction: column; gap: 1rem; }
            .footer-links { flex-direction: column; text-align: center; gap: 1rem; }
        }
        
        @media (max-width: 480px) {
            .container { padding: 0 1rem; }
            .hero h1 { font-size: 2rem; }
            .products { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="site-header">
        <div class="header-inner">
            <a href="../index.php" class="logo">LIORA</a>
            <nav class="main-nav">
                <ul class="nav-links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../index.php#new">New Arrivals</a></li>
                    <li><a href="Men.php">Men <span class="mens-badge">Collection</span></a></li>
                    <li><a href="../Women Page/index.php">Women</a></li>
                    <li><a href="../Kids_Page/kids.php">Kids</a></li>
                    <li><a href="../Best Sellers Page/project.php">Best Sellers</a></li>
                </ul>
            </nav>
            <div class="auth-section">
                <a href="../cart.php" class="cart-link">
                    Cart
                    <span class="cart-count">
                        <?php
                        $count = 0;
                        if (isset($_SESSION['user_id'])) {
                            $user_id = $_SESSION['user_id'];
                            $count_sql = "SELECT SUM(quantity) as count FROM cart_items ci JOIN cart c ON ci.cart_id = c.cart_id WHERE c.user_id = $user_id";
                            $count_result = $conn->query($count_sql);
                            if ($count_result && $row = $count_result->fetch_assoc()) {
                                $count = $row['count'] ?: 0;
                            }
                        }
                        echo $count;
                        ?>
                    </span>
                </a>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <a href="../profile.php" class="auth-btn">Profile</a>
                <?php } else { ?>
                    <a href="../signin.php" class="auth-btn">Login</a>
                <?php } ?>
            </div>
            <button class="menu-btn" id="menuBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <a href="../index.php">Home</a>
            <a href="../index.php#new">New Arrivals</a>
            <a href="Men.php">Men's Collection</a>
            <a href="../Women Page/index.php">Women</a>
            <a href="../Kids_Page/kids.php">Kids</a>
            <a href="../Best Sellers Page/project.php">Best Sellers</a>
            <a href="../cart.php">Cart</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <h1>Liora Mens Collection</h1>
        </div>
    </section>

    <!-- Marquee -->
    <div class="marquee">
        <div class="marquee__group">
            <span>Free delivery up to $100 ➺</span>
            <span>Free delivery up to $100 ➺</span>
            <span>Free delivery up to $100 ➺</span>
            <span>Free delivery up to $100 ➺</span>
            <span>Free delivery up to $100 ➺</span>
            <span>Free delivery up to $100 ➺</span>
        </div>
        <div class="marquee__group" aria-hidden="true">
            <span>Free delivery up to $100 ➺</span>
            <span>Free delivery up to $100 ➺</span>
            <span>Free delivery up to $100 ➺</span>
            <span>Free delivery up to $100 ➺</span>
            <span>Free delivery up to $100 ➺</span>
            <span>Free delivery up to $100 ➺</span>
        </div>
    </div>

    <!-- Mens Section -->
    <section id="boys" class="category-section active">
        <div class="container">
            <div class="filters">
                <h3 class="filter-title">Filter Products</h3>
                <div class="filter-options">
                    <!-- Only price filter remains -->
                </div>
            </div>

            <!-- PHP Filter Form -->
            <form method="GET" style="margin-bottom:2rem;">
                <label style="font-weight:600;color:#0a5cff;margin-right:1rem;">Price Range:</label>
                <select name="price" onchange="this.form.submit()" style="padding:0.5rem 1rem;border-radius:1rem;border:1px solid #e6e6e6;margin-right:1rem;">
                    <option value="all" <?php echo $price_filter === 'all' ? 'selected' : ''; ?>>All Prices</option>
                    <option value="under50" <?php echo $price_filter === 'under50' ? 'selected' : ''; ?>>Under $50</option>
                    <option value="50to100" <?php echo $price_filter === '50to100' ? 'selected' : ''; ?>>$50 - $100</option>
                    <option value="over100" <?php echo $price_filter === 'over100' ? 'selected' : ''; ?>>Over $100</option>
                </select>
                <label style="font-weight:600;color:#0a5cff;margin-right:1rem;">Sort By:</label>
                <select name="sort" onchange="this.form.submit()" style="padding:0.5rem 1rem;border-radius:1rem;border:1px solid #e6e6e6;">
                    <option value="default" <?php echo $sort === 'default' ? 'selected' : ''; ?>>Latest</option>
                    <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name: A to Z</option>
                </select>
            </form>

            <div class="products" id="boysProducts">
                <?php while($product = $result->fetch_assoc()) { ?>
                <div class="product-card">
                    <img src="../<?php echo $product['image_path'] ?: 'https://images.unsplash.com/photo-1521334884684-d80222895322?q=80&w=800'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>$<?php echo number_format($product['price'], 2); ?></p>
                    <button class="add-to-cart" data-product-id="<?php echo $product['product_id']; ?>">Add to Cart</button>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <script>
        // Add to cart functionality
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                
                if (!productId) {
                    console.error('No product ID found');
                    alert('Error: Product ID not found');
                    return;
                }
                
                fetch('../add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + encodeURIComponent(productId) + '&quantity=1'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Cart response:', data);
                    if(data.success) {
                        // Update cart count
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount) {
                            cartCount.textContent = data.cartCount;
                        }
                        
                        // Visual feedback
                        this.style.backgroundColor = '#10b981';
                        this.textContent = 'Added!';
                        setTimeout(() => {
                            this.style.backgroundColor = 'dimgrey';
                            this.textContent = 'Add to Cart';
                        }, 1500);
                    } else {
                        console.error('Cart error:', data.message);
                        alert('Error adding product to cart: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Network error adding product to cart: ' + error.message);
                });
            });
        });
        
        // Mobile menu toggle
        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        
        menuBtn?.addEventListener('click', () => {
            if (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') {
                mobileMenu.style.display = 'block';
            } else {
                mobileMenu.style.display = 'none';
            }
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!menuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.style.display = 'none';
            }
        });
    </script>

    <!-- Floating Contact Buttons -->
    <div class="floating-contact-buttons">
        <a href="https://wa.me/94773768984" target="_blank" class="whatsapp-btn" title="Chat on WhatsApp">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.473 3.516"/>
            </svg>
        </a>
        <a href="mailto:kavindugimhan206@gmail.com" class="email-btn" title="Send Email">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
        </a>
    </div>

    <style>
        .floating-contact-buttons {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1000;
        }

        .whatsapp-btn, .email-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            animation: pulse 2s infinite;
        }

        .whatsapp-btn {
            background: #25D366;
        }

        .whatsapp-btn:hover {
            background: #128C7E;
            transform: scale(1.1);
        }

        .email-btn {
            background: #EA4335;
        }

        .email-btn:hover {
            background: #D33B2C;
            transform: scale(1.1);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }
            50% {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            }
            100% {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }
        }

        @media (max-width: 768px) {
            .floating-contact-buttons {
                bottom: 20px;
                right: 20px;
            }
            
            .whatsapp-btn, .email-btn {
                width: 50px;
                height: 50px;
            }
        }
    </style>

    <!-- Footer -->
    <footer>
        <div class="container footer-content">
            <p>© 2025, Liora Online Store. All rights reserved.</p>
            <div class="footer-links">
                <a href="../aboutus.html">About Us</a>
                <a href="../contactus.html">Contact Us</a>
                <a href="../terms.html">Terms & Conditions</a>
            </div>
        </div>
    </footer>

    <!-- Enhanced Visual Effects Scripts -->
    <script src="../Scripts/cursor.js"></script>
    <script src="../Scripts/particles.js"></script>
</body>
</html>