<?php
include '../includes/db_connection.php';

// Get filter parameters
$price_filter = isset($_GET['price']) ? $_GET['price'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Modify query based on filters
$where_clause = "WHERE category = 'kids'";
if ($price_filter !== 'all') {
    $price_ranges = [
        'under30' => 'price < 30',
        '30to60' => 'price BETWEEN 30 AND 60',
        'over60' => 'price > 60'
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
    <title>Kids Collection - Liora</title>
    <meta name="description" content="Adorable and comfortable clothing for kids of all ages." />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; background-color: beige; line-height: 1.6; }
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
        
        /* Kids Badge */
        .kids-badge {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
            padding: 0.2rem 0.8rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
            position: relative;
        }
        
        .kids-badge::after {
            content: "🌟";
            position: absolute;
            right: -8px;
            top: -2px;
            font-size: 0.7rem;
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
        
        /* Hero Section */
        .hero { background: linear-gradient(135deg, bisque, dimgrey); color: black; padding: 4rem 0; text-align: center; }
        .hero h1 { font-size: 3.5rem; font-weight: bold; margin-bottom: 1rem; }
        .hero p { font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9; }
        .btn { background: dimgrey; color: white; padding: 1rem 2rem; border: none; border-radius: 30px; font-size: 1.1rem; font-weight: bold; cursor: pointer; transition: all 0.3s; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); background: #555; }
        
        /* Category Tabs */
        .category-tabs { background: bisque; padding: 2rem 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .tabs { display: flex; justify-content: center; gap: 1rem; }
        .tab-btn { background: white; color: #333; padding: 1rem 2rem; border: none; border-radius: 25px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .tab-btn.active { background: dimgrey; color: white; }
        .tab-btn:hover { background: #f0f0f0; color: #333; }
        
        /* Filters */
        .filters { background: bisque; padding: 2rem; margin: 2rem 0; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .filter-title { font-size: 1.3rem; font-weight: bold; color: #333; margin-bottom: 1.5rem; }
        .filter-options { display: flex; flex-wrap: wrap; gap: 2rem; }
        .filter-group h4 { color: #666; margin-bottom: 0.5rem; font-weight: 600; }
        .filter-buttons { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .filter-btn { background: #f8f9fa; color: #333; padding: 0.5rem 1rem; border: none; border-radius: 20px; font-size: 0.9rem; cursor: pointer; transition: all 0.3s; }
        .filter-btn.active, .filter-btn:hover { background: #4ecdc4; color: white; }
        
        form { display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: center; }
        form select { padding: 0.6rem 1rem; border-radius: 25px; border: 2px solid #e6e6e6; background: white; font-size: 1rem; transition: border-color 0.3s; }
        form select:focus { outline: none; border-color: #ff6b6b; }
        
        /* Products Section */
        .products-section { padding: 3rem 0; }
        .section-title { text-align: center; font-size: 2.5rem; font-weight: bold; color: #333; margin-bottom: 1rem; }
        .section-description { text-align: center; font-size: 1.1rem; color: #666; margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto; }
        .products { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; padding: 2rem 0; }
        
        .product-card { 
            background: white; 
            border-radius: 0.8rem; 
            overflow: hidden; 
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1); 
            transition: all 0.3s ease; 
        }
        
        .product-card:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15); 
        }
        
        .product-card img { 
            width: 100%; 
            height: 220px; 
            object-fit: cover; 
        }
        
        .product-card h3 { 
            font-size: 0.9rem; 
            font-weight: 600; 
            margin: 0.6rem 0.8rem 0.2rem; 
            color: #2d3748; 
            line-height: 1.2;
        }
        
        .product-card p { 
            font-size: 0.9rem; 
            font-weight: 600; 
            color: #4a5568; 
            margin: 0 0.8rem 0.6rem; 
        }
        
        .add-to-cart { 
            margin: 0 0.8rem 0.8rem; 
            width: calc(100% - 1.6rem); 
            padding: 0.3rem 0.6rem; 
            background: #2d3748; 
            color: white; 
            border: none; 
            border-radius: 0.3rem; 
            cursor: pointer; 
            font-weight: 500; 
            font-size: 0.8rem;
            transition: background 0.3s ease; 
        }
        
        .add-to-cart:hover { 
            background: #1a202c; 
        }
        
        /* Category Sections */
        .category-section { display: none; padding: 2rem 0; }
        .category-section.active { display: block; }
        
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
            .tabs { flex-direction: column; gap: 0.5rem; }
            .filter-options { flex-direction: column; gap: 1rem; }
            .products { grid-template-columns: repeat(3, 1fr); gap: 0.8rem; }
            .product-card img { height: 180px; }
            .product-card h3 { font-size: 0.8rem; }
            .product-card p { font-size: 0.75rem; margin: 0.5rem 0; }
            .product-card .price { font-size: 0.85rem; margin: 0.6rem 0; }
            .product-card button { font-size: 0.75rem; padding: 0.4rem 0.8rem; }
            .footer-content { flex-direction: column; gap: 1rem; }
            .footer-links { flex-direction: column; text-align: center; gap: 1rem; }
        }
        
        @media (max-width: 480px) {
            .container { padding: 0 1rem; }
            .hero h1 { font-size: 2rem; }
            .products { grid-template-columns: repeat(2, 1fr); gap: 0.5rem; }
            .product-card { margin: 0; }
            .product-card img { height: 160px; }
            .product-card h3 { font-size: 0.75rem; margin: 0.5rem 0.6rem 0.2rem; line-height: 1.2; }
            .product-card p { font-size: 0.7rem; margin: 0 0.6rem 0.5rem; }
            .product-card .price { font-size: 0.8rem; margin: 0.5rem 0; }
            .add-to-cart { margin: 0 0.6rem 0.6rem; font-size: 0.7rem; padding: 0.3rem 0.6rem; }
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
                    <li><a href="../Mens Page/Men.php">Men</a></li>
                    <li><a href="../Women Page/index.php">Women</a></li>
                    <li><a href="kids.php">Kids <span class="kids-badge">Zone</span></a></li>
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
            <a href="../Mens Page/Men.php">Men</a>
            <a href="../Women Page/index.php">Women</a>
            <a href="kids.php">Kids Zone</a>
            <a href="../Best Sellers Page/project.php">Best Sellers</a>
            <a href="../cart.php">Cart</a>
        </div>
    </header>


    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Liora Kids Collection</h1>
            <p>Discover our adorable and comfortable clothing for kids of all ages. From everyday wear to special occasions, we have everything you need!</p>
            <button class="btn" onclick="document.querySelector('.category-tabs').scrollIntoView({behavior: 'smooth'})">Shop New Arrivals</button>
        </div>
    </section>

    <!-- Navigation Tabs -->
    <section class="category-tabs">
        <div class="container">
            <div class="tabs">
                <button class="tab-btn active" data-target="boys">Boys Collection</button>
                <button class="tab-btn" data-target="girls">Girls Collection</button>
                <button class="tab-btn" data-target="baby">Baby Collection</button>
            </div>
        </div>
    </section>

    <!-- Boys Section -->
    <section id="boys" class="category-section active">
        <div class="container">
            <h2 class="section-title">Boys Collection</h2>
            <p class="section-description">Discover our cool and comfortable clothing for boys of all ages. From casual wear to formal outfits, we have everything for your little man!</p>
            
            <div class="filters">
                <h3 class="filter-title">Filter Products</h3>
                <form class="filter-form" data-section="boys">
                    <div>
                        <label>Price Range:</label>
                        <select name="price" class="price-filter">
                            <option value="all" <?php echo $price_filter === 'all' ? 'selected' : ''; ?>>All Prices</option>
                            <option value="under30" <?php echo $price_filter === 'under30' ? 'selected' : ''; ?>>Under $30</option>
                            <option value="30to60" <?php echo $price_filter === '30to60' ? 'selected' : ''; ?>>$30 - $60</option>
                            <option value="over60" <?php echo $price_filter === 'over60' ? 'selected' : ''; ?>>Over $60</option>
                        </select>
                    </div>
                    <div>
                        <label>Sort By:</label>
                        <select name="sort" class="sort-filter">
                            <option value="default" <?php echo $sort === 'default' ? 'selected' : ''; ?>>Latest</option>
                            <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name: A to Z</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="products">
                <?php 
                // Reset the result pointer
                $result->data_seek(0);
                while($product = $result->fetch_assoc()) { 
                    // Determine category class based on product name
                    $categoryClass = '';
                    $productName = strtolower($product['name']);
                    
                    // Girls keywords - expanded list
                    if (strpos($productName, 'dress') !== false || strpos($productName, 'skirt') !== false || 
                        strpos($productName, 'blouse') !== false || strpos($productName, 'legging') !== false ||
                        strpos($productName, 'girl') !== false || strpos($productName, 'girls') !== false ||
                        strpos($productName, 'princess') !== false || strpos($productName, 'tutu') !== false ||
                        strpos($productName, 'flower') !== false || strpos($productName, 'floral') !== false ||
                        strpos($productName, 'pink') !== false || strpos($productName, 'sparkle') !== false ||
                        strpos($productName, 'bow') !== false || strpos($productName, 'ruffle') !== false ||
                        strpos($productName, 'unicorn') !== false || strpos($productName, 'fairy') !== false ||
                        strpos($productName, 'polka') !== false || strpos($productName, 'hearts') !== false) {
                        $categoryClass = 'girls';
                    } 
                    // Baby keywords - expanded list  
                    elseif (strpos($productName, 'bodysuit') !== false || strpos($productName, 'onesie') !== false || 
                              strpos($productName, 'sleep') !== false || strpos($productName, 'pajama') !== false ||
                              strpos($productName, 'baby') !== false || strpos($productName, 'infant') !== false ||
                              strpos($productName, 'newborn') !== false || strpos($productName, 'toddler') !== false ||
                              strpos($productName, 'romper') !== false || strpos($productName, 'bib') !== false ||
                              strpos($productName, 'diaper') !== false || strpos($productName, 'crawler') !== false ||
                              strpos($productName, 'tiny') !== false || strpos($productName, 'little') !== false ||
                              strpos($productName, 'soft') !== false || strpos($productName, 'cute') !== false ||
                              strpos($productName, 'teddy') !== false || strpos($productName, 'bear') !== false ||
                              strpos($productName, 'duckling') !== false || strpos($productName, 'bunny') !== false) {
                        $categoryClass = 'baby';
                    } 
                    // Boys keywords - expanded list (default for remaining items)
                    else {
                        // Additional boys-specific keywords for better classification
                        if (strpos($productName, 'boy') !== false || strpos($productName, 'boys') !== false ||
                            strpos($productName, 'shirt') !== false || strpos($productName, 'pant') !== false ||
                            strpos($productName, 'short') !== false || strpos($productName, 'trouser') !== false ||
                            strpos($productName, 'hoodie') !== false || strpos($productName, 'sweater') !== false ||
                            strpos($productName, 'jacket') !== false || strpos($productName, 'blazer') !== false ||
                            strpos($productName, 'polo') !== false || strpos($productName, 'cargo') !== false ||
                            strpos($productName, 'denim') !== false || strpos($productName, 'jeans') !== false ||
                            strpos($productName, 'sport') !== false || strpos($productName, 'athletic') !== false ||
                            strpos($productName, 'superhero') !== false || strpos($productName, 'batman') !== false ||
                            strpos($productName, 'superman') !== false || strpos($productName, 'spiderman') !== false ||
                            strpos($productName, 'button') !== false || strpos($productName, 'casual') !== false) {
                            $categoryClass = 'boys';
                        } else {
                            $categoryClass = 'boys'; // Default to boys for remaining items
                        }
                    }
                ?>
                <div class="product-card <?php echo $categoryClass; ?>">
                    <img src="../<?php echo $product['image_path'] ?: 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>$<?php echo number_format($product['price'], 2); ?></p>
                    <button class="add-to-cart" data-product-id="<?php echo $product['product_id']; ?>">Add to Cart</button>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Girls Section -->
    <section id="girls" class="category-section">
        <div class="container">
            <h2 class="section-title">Girls Collection</h2>
            <p class="section-description">Discover our adorable and stylish clothing for girls of all ages. From playful dresses to comfy everyday wear, we have everything for your little fashionista!</p>
            
            <div class="filters">
                <h3 class="filter-title">Filter Products</h3>
                <form class="filter-form" data-section="girls">
                    <div>
                        <label>Price Range:</label>
                        <select name="price" class="price-filter">
                            <option value="all">All Prices</option>
                            <option value="under30">Under $30</option>
                            <option value="30to60">$30 - $60</option>
                            <option value="over60">Over $60</option>
                        </select>
                    </div>
                    <div>
                        <label>Sort By:</label>
                        <select name="sort" class="sort-filter">
                            <option value="default">Latest</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="products" id="girlsProducts">
                <!-- Girls products will be shown here by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Baby Section -->
    <section id="baby" class="category-section">
        <div class="container">
            <h2 class="section-title">Baby Collection</h2>
            <p class="section-description">Discover our soft and adorable clothing for the littlest ones. From newborn essentials to toddler outfits, we have everything for your baby's comfort and style!</p>
            
            <div class="filters">
                <h3 class="filter-title">Filter Products</h3>
                <form class="filter-form" data-section="baby">
                    <div>
                        <label>Price Range:</label>
                        <select name="price" class="price-filter">
                            <option value="all">All Prices</option>
                            <option value="under30">Under $30</option>
                            <option value="30to60">$30 - $60</option>
                            <option value="over60">Over $60</option>
                        </select>
                    </div>
                    <div>
                        <label>Sort By:</label>
                        <select name="sort" class="sort-filter">
                            <option value="default">Latest</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="products" id="babyProducts">
                <!-- Baby products will be shown here by JavaScript -->
            </div>
        </div>
    </section>

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

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                
                // Remove active class from all tabs and sections
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.category-section').forEach(s => s.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding section
                this.classList.add('active');
                document.getElementById(target).classList.add('active');
                
                // Update products display based on category
                updateProductsDisplay(target);
                
                // Reset filters for the new tab
                const targetForm = document.querySelector(`#${target} .filter-form`);
                if (targetForm) {
                    const priceSelect = targetForm.querySelector('.price-filter');
                    const sortSelect = targetForm.querySelector('.sort-filter');
                    priceSelect.value = 'all';
                    sortSelect.value = 'default';
                }
            });
        });

        // Filter products by category
        function filterProducts(category, section) {
            const products = document.querySelectorAll(`#${section}Products .product-card`);
            
            products.forEach(product => {
                if (category === 'all' || product.classList.contains(category)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        // Update products display for each section
        function updateProductsDisplay(target) {
            const allProducts = document.querySelectorAll('.products .product-card');
            const targetSection = document.querySelector(`#${target}Products`);
            
            // Clear target section if it exists
            if (targetSection) {
                targetSection.innerHTML = '';
            }
            
            // Show/hide products based on category
            allProducts.forEach(product => {
                if (target === 'boys') {
                    // For boys section, show products in the main .products container
                    if (product.classList.contains('boys')) {
                        product.style.display = 'block';
                    } else {
                        product.style.display = 'none';
                    }
                } else {
                    // For girls and baby sections, hide all products in main container
                    product.style.display = 'none';
                    
                    // Clone matching products to the target section
                    if ((target === 'girls' && product.classList.contains('girls')) ||
                        (target === 'baby' && product.classList.contains('baby'))) {
                        if (targetSection) {
                            const clonedProduct = product.cloneNode(true);
                            clonedProduct.style.display = 'block';
                            targetSection.appendChild(clonedProduct);
                        }
                    }
                }
            });
            
            // Re-attach event listeners to cloned products
            if (targetSection) {
                targetSection.querySelectorAll('.add-to-cart').forEach(button => {
                    button.addEventListener('click', addToCartHandler);
                });
            }
        }

        // Filter and sort products within a section
        function filterAndSortProducts(section, priceFilter, sortFilter) {
            let products;
            let container;
            
            // Get products and container for the specific section
            if (section === 'boys') {
                products = Array.from(document.querySelectorAll('.products .product-card.boys'));
                container = document.querySelector('.products');
            } else if (section === 'girls') {
                products = Array.from(document.querySelectorAll('#girlsProducts .product-card'));
                container = document.querySelector('#girlsProducts');
            } else if (section === 'baby') {
                products = Array.from(document.querySelectorAll('#babyProducts .product-card'));
                container = document.querySelector('#babyProducts');
            }
            
            if (!container || products.length === 0) return;
            
            // Store all products for restoration
            const allProducts = Array.from(container.querySelectorAll('.product-card'));
            
            // Filter by price
            const filteredProducts = products.filter(product => {
                if (priceFilter === 'all') return true;
                
                // Find the price paragraph (the one with $ symbol)
                const priceParagraphs = product.querySelectorAll('p');
                let priceParagraph = null;
                for (let p of priceParagraphs) {
                    if (p.textContent.includes('$')) {
                        priceParagraph = p;
                        break;
                    }
                }
                
                if (!priceParagraph) return true;
                
                const price = parseFloat(priceParagraph.textContent.replace(/[$,]/g, ''));
                
                switch (priceFilter) {
                    case 'under30': return price < 30;
                    case '30to60': return price >= 30 && price <= 60;
                    case 'over60': return price > 60;
                    default: return true;
                }
            });
            
            // Sort products
            if (sortFilter !== 'default') {
                filteredProducts.sort((a, b) => {
                    // Get price elements safely
                    const aPriceParagraphs = a.querySelectorAll('p');
                    const bPriceParagraphs = b.querySelectorAll('p');
                    
                    let aPriceParagraph = null;
                    let bPriceParagraph = null;
                    
                    for (let p of aPriceParagraphs) {
                        if (p.textContent.includes('$')) {
                            aPriceParagraph = p;
                            break;
                        }
                    }
                    
                    for (let p of bPriceParagraphs) {
                        if (p.textContent.includes('$')) {
                            bPriceParagraph = p;
                            break;
                        }
                    }
                    
                    const aPrice = aPriceParagraph ? parseFloat(aPriceParagraph.textContent.replace(/[$,]/g, '')) : 0;
                    const bPrice = bPriceParagraph ? parseFloat(bPriceParagraph.textContent.replace(/[$,]/g, '')) : 0;
                    
                    const aNameElement = a.querySelector('h3');
                    const bNameElement = b.querySelector('h3');
                    const aName = aNameElement ? aNameElement.textContent.toLowerCase().trim() : '';
                    const bName = bNameElement ? bNameElement.textContent.toLowerCase().trim() : '';
                    
                    switch (sortFilter) {
                        case 'price_asc': return aPrice - bPrice;
                        case 'price_desc': return bPrice - aPrice;
                        case 'name_asc': return aName.localeCompare(bName);
                        case 'name_desc': return bName.localeCompare(aName);
                        default: return 0;
                    }
                });
            }
            
            // Hide all products first
            allProducts.forEach(product => {
                product.style.display = 'none';
            });
            
            // Show and reorder filtered products
            filteredProducts.forEach((product, index) => {
                product.style.display = 'block';
                // Ensure correct positioning
                container.appendChild(product);
            });
            
            // Re-attach event listeners to visible products only
            filteredProducts.forEach(product => {
                const button = product.querySelector('.add-to-cart');
                if (button) {
                    // Remove existing listener to prevent duplicates
                    button.removeEventListener('click', addToCartHandler);
                    button.addEventListener('click', addToCartHandler);
                }
            });
        }

        // Filter button functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                const section = this.closest('.category-section').id;
                
                // Remove active class from filter buttons in this section
                this.closest('.filter-buttons').querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Filter products
                filterProducts(filter, section);
            });
        });

        // Add to cart handler
        function addToCartHandler() {
            const productId = this.getAttribute('data-product-id');
            
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
                if (data.success) {
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
        }

        // Initial setup
        document.addEventListener('DOMContentLoaded', function() {
            // Attach event listeners to all add-to-cart buttons
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', addToCartHandler);
            });
            
            // Initialize by showing only boys products
            const allProducts = document.querySelectorAll('.products .product-card');
            allProducts.forEach(product => {
                if (product.classList.contains('boys')) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });

            // Add event listeners to filter forms
            document.querySelectorAll('.filter-form').forEach(form => {
                const section = form.getAttribute('data-section');
                const priceSelect = form.querySelector('.price-filter');
                const sortSelect = form.querySelector('.sort-filter');
                
                priceSelect.addEventListener('change', function() {
                    filterAndSortProducts(section, this.value, sortSelect.value);
                });
                
                sortSelect.addEventListener('change', function() {
                    filterAndSortProducts(section, priceSelect.value, this.value);
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

    <!-- Enhanced Visual Effects Scripts -->
    <script src="../Scripts/cursor.js"></script>
    <script src="../Scripts/particles.js"></script>
</body>
</html>