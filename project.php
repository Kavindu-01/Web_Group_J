<?php
include '../includes/db_connection.php';

// Get filter parameters
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'best_sellers';

// Check if is_best_seller column exists, if not use the old method
$check_column = $conn->query("SHOW COLUMNS FROM products LIKE 'is_best_seller'");
if ($check_column->num_rows > 0) {
    // New method: Use is_best_seller column
    $where_clause = "WHERE is_best_seller = 1";
} else {
    // Old method: Use category = 'best_sellers'
    $where_clause = "WHERE category = 'best_sellers'";
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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Best Sellers — Liora Fashion Store</title>
  <meta name="description" content="Our most popular and best-selling fashion items." />
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: system-ui, Arial, sans-serif;
      background: beige;
      color: #222;
      line-height: 1.4;
    }
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
    
    /* Best Sellers Badge */
    .bestsellers-badge {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
        padding: 0.2rem 0.8rem;
        border-radius: 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(245, 101, 101, 0.3);
        position: relative;
    }
    
    .bestsellers-badge::after {
        content: "🔥";
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
    
    /* Hero Banner */
    .hero-banner {
      width: 100vw;
      margin-left: 50%;
      transform: translateX(-50%);
      background-color: beige;
      padding: 48px 0 32px 0;
      text-align: center;
      margin-bottom: 0;
    }
    .hero-banner h1 {
      font-size: 2.7rem;
      font-weight: 800;
      color: #222;
      margin-bottom: 12px;
      letter-spacing: 1px;
    }
    .hero-banner p {
      font-size: 1.3rem;
      color: #444;
      margin-bottom: 0;
      font-weight: 500;
    }
    
    /* Carousel - Bubbly, Centered, Large */
    .carousel-section {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      background: none;
      padding: 0;
      margin-bottom: 0;
    }
    .carousel-container {
      position: relative;
      width: 800px;
      height: 500px;
      max-width: 95vw;
      margin: 32px auto 0 auto;
      overflow: hidden;
      border-radius: 48px 48px 48px 48px/60px 60px 60px 60px;
      box-shadow: 0 8px 32px 0 rgba(0,0,0,0.13), 0 1.5px 8px 0 rgba(0,0,0,0.08);
      background: #fff;
      transition: box-shadow 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .carousel-slide {
      position: absolute;
      width: 100%;
      height: 100%;
      opacity: 0;
      transition: opacity 0.7s cubic-bezier(.4,0,.2,1);
      display: flex;
      align-items: center;
      justify-content: center;
      left: 0;
      top: 0;
    }
    .carousel-slide.active {
      opacity: 1;
      z-index: 1;
    }
    .carousel-slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 48px 48px 48px 48px/60px 60px 60px 60px;
      display: block;
    }
    .carousel-caption {
      position: absolute;
      left: 0; right: 0; bottom: 30px;
      text-align: center;
      color: #fff;
      font-size: 1.3rem;
      font-weight: 700;
      text-shadow: 0 2px 16px #000b;
      letter-spacing: 1px;
      width: 100%;
      pointer-events: none;
      z-index: 2;
    }
    .carousel-controls {
      position: absolute;
      top: 50%;
      left: 0; right: 0;
      display: flex;
      justify-content: space-between;
      transform: translateY(-50%);
      z-index: 3;
      pointer-events: none;
    }
    .carousel-btn {
      background: #fff9;
      border: none;
      border-radius: 50%;
      width: 44px;
      height: 44px;
      font-size: 1.7rem;
      color: #222;
      cursor: pointer;
      margin: 0 18px;
      pointer-events: all;
      transition: background 0.2s;
      box-shadow: 0 2px 8px #0002;
    }
    .carousel-btn:hover { background: #f7cac9; }
    .carousel-indicators {
      position: absolute;
      left: 0; right: 0; bottom: 10px;
      display: flex;
      justify-content: center;
      gap: 10px;
      z-index: 4;
    }
    .carousel-dot {
      width: 12px; height: 12px;
      border-radius: 50%;
      background: #fff7;
      border: 2px solid #fff;
      cursor: pointer;
      transition: background 0.2s;
    }
    .carousel-dot.active { background: #f7cac9; }
    
    /* Search + Filter */
    .search-filter {
      max-width: 1100px;
      margin: 20px auto;
      display: flex;
      gap: 12px;
      padding: 0 18px;
      align-items: center;
      justify-content: center;
    }
    .search-wrapper { flex: 1 1 500px; }
    .search-wrapper input {
      width: 100%;
      padding: 12px 14px;
      border-radius: 8px;
      border: 1px solid #ddd;
      font-size: 15px;
    }
    .filter-wrapper select {
      padding: 11px 12px;
      border-radius: 8px;
      border: 1px solid #ddd;
      font-size: 15px;
    }
    
    /* Filter Buttons */
    .filter-buttons {
      margin-bottom: 24px;
      display: flex;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
    }
    .filter-btn {
      padding: 10px 20px;
      border: 1px solid #ddd;
      background: #fff;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      transition: background-color 0.2s, color 0.2s, border-color 0.2s;
    }
    .filter-btn:hover {
      background-color: #f0f0f0;
      border-color: #ccc;
    }
    .filter-btn.active {
      background-color: dimgrey;
      color: white;
      border-color: dimgrey;
    }
    
    /* Best Sellers Section */
    .best-sellers {
      max-width: 1100px;
      margin: 6px auto 40px auto;
      padding: 0 18px;
      text-align: center;
    }
    .best-sellers h2 {
      font-size: 28px;
      color: #111;
      margin: 12px 0;
      display: inline-block;
      position: relative;
    }
    .best-sellers h2::after {
      content: "";
      display: block;
      width: 60%;
      height: 4px;
      background: bisque;
      margin: 8px auto 0;
      border-radius: 3px;
    }
    .intro-text {
      font-size: 16px;
      color: #3b3b3b;
      max-width: 800px;
      margin: 18px auto 26px auto;
      line-height: 1.6;
      text-align: center;
      background: #f7f5f2;
      padding: 16px 18px;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }
    
    /* Products Grid */
    .products {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 18px;
      margin-top: 18px;
    }
    .card {
      background: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 14px rgba(28,28,28,0.06);
      display: flex;
      flex-direction: column;
      transition: transform 220ms ease, box-shadow 220ms ease;
      position: relative;
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 14px 28px rgba(28,28,28,0.09);
    }
    .img-wrap {
      width: 100%;
      height: 200px;
      background: #f2f2f2;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }
    .img-wrap img {
      max-width: 100%;
      max-height: 100%;
      object-fit: cover;
    }
    .best-seller-label {
      position: absolute;
      top: 10px;
      left: 10px;
      background: #c92b2b;
      color: #fff;
      font-size: 13px;
      font-weight: 700;
      padding: 5px 12px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      z-index: 2;
      letter-spacing: 0.5px;
    }
    .card-body {
      padding: 14px;
      display: flex;
      flex-direction: column;
      gap: 8px;
      flex: 1 0 auto;
    }
    .product-name { font-size: 16px; font-weight: 700; color: #111; }
    .category { font-size: 13px; color: #7a7a7a; }
    .price { font-size: 15px; color: #c92b2b; font-weight: 700; margin-top: auto; }
    .btn {
      margin-top: 10px;
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      background: dimgrey;
      color: white;
      border: none;
      cursor: pointer;
      font-weight: 700;
      transition: all 0.2s;
    }
    .btn:hover { background: #666; }
    
    /* Footer */
    footer { background: beige; padding: 3rem 0; border-top: 1px solid #ddd; margin-top: 3rem; }
    .footer-content { display: flex; flex-direction: column; align-items: center; justify-content: between; gap: 1rem; }
    .footer-content p { color: #666; font-size: 0.9rem; }
    .footer-links { display: flex; gap: 2rem; }
    .footer-links a { color: #333; text-decoration: none; font-size: 0.9rem; transition: color 0.3s; }
    .footer-links a:hover { color: dimgrey; }
    
    /* Responsive */
    @media (max-width: 820px) {
      .carousel-container, .carousel-slide, .carousel-slide img { height: 400px; }
    }
    @media (max-width: 768px) {
      .header-inner { 
        padding: 1rem;
      }
      .main-nav { 
        display: none;
      }
      .menu-btn {
        display: block;
      }
      .search-filter { flex-direction: column; gap: 10px; padding: 0 12px; }
      .filter-wrapper form { flex-direction: column; gap: 8px; }
      .filter-buttons { padding: 0 12px; }
      .footer-content { flex-direction: column; gap: 1rem; }
      .footer-links { flex-direction: column; text-align: center; gap: 1rem; }
      .hero-banner h1 { font-size: 2rem; }
      .hero-banner p { font-size: 1.1rem; }
      .best-sellers h2 { font-size: 22px; }
      .products { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; }
    }
    @media (max-width: 700px) {
      .carousel-container, .carousel-slide, .carousel-slide img { height: 300px; width: 95vw; border-radius: 32px/40px; }
      .carousel-caption { font-size: 1.1rem; }
      .carousel-btn { width: 36px; height: 36px; font-size: 1.3rem; }
      .carousel-dot { width: 10px; height: 10px; }
    }
    @media (max-width: 480px) {
      .best-sellers h2 { font-size: 22px; }
      .search-filter { flex-direction: column; gap: 10px; padding: 0 12px; }
      .carousel-container, .carousel-slide, .carousel-slide img { height: 250px; width: 98vw; border-radius: 22px/28px; }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="site-header">
    <div class="header-inner">
      <a href="../index.php" class="logo">LIORA</a>
      <nav class="main-nav">
        <ul class="nav-links">
          <li><a href="../index.php">Home</a></li>
          <li><a href="../index.php#new">New Arrivals</a></li>
          <li><a href="../Mens Page/Men.php">Men</a></li>
          <li><a href="../Women Page/index.php">Women</a></li>
          <li><a href="../Kids_Page/kids.php">Kids</a></li>
          <li><a href="project.php">Best Sellers <span class="bestsellers-badge">Hot</span></a></li>
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
      <a href="../Kids_Page/kids.php">Kids</a>
      <a href="project.php">Best Sellers</a>
      <a href="../cart.php">Cart</a>
    </div>
  </header>

  <!-- Search + Filter -->
  <section class="search-filter">
    <div class="search-wrapper">
      <input type="text" id="searchBox" placeholder="🔍 Search best sellers..." onkeyup="searchProducts()">
    </div>
    <div class="filter-wrapper">
      <form method="GET" style="display: flex; gap: 12px;">
        <select name="price" id="priceFilter" onchange="filterAndSortProducts()">
          <option value="all">All Prices</option>
          <option value="under50">Under $50</option>
          <option value="50to100">$50 - $100</option>
          <option value="over100">Over $100</option>
        </select>
        <select name="sort" id="sortFilter" onchange="filterAndSortProducts()">
          <option value="best_sellers" <?php echo $sort === 'best_sellers' ? 'selected' : ''; ?>>Best Sellers</option>
          <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
          <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
          <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name: A to Z</option>
        </select>
      </form>
    </div>
  </section>

  <!-- Banner -->
  <section class="hero-banner">
    <h1>Our Best Sellers</h1>
    <p>Discover our most popular and loved products, handpicked by our customers!</p>
  </section>

  <!-- Carousel Section -->
  <section class="carousel-section">
    <div class="carousel-container">
      <div class="carousel-slide active">
        <img src="https://images.unsplash.com/photo-1558769132-cb1aea458c5e?q=80&w=1200" alt="Neutral Fashion Collection">
        <div class="carousel-caption">Timeless Neutral Collection</div>
      </div>
      <div class="carousel-slide">
        <img src="https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?q=80&w=1200" alt="Minimalist Wardrobe">
        <div class="carousel-caption">Curated Minimalist Essentials</div>
      </div>
      <div class="carousel-slide">
        <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?q=80&w=1200" alt="Summer Style Collection">
        <div class="carousel-caption">Vibrant Summer Favorites</div>
      </div>
      <div class="carousel-controls">
        <button class="carousel-btn" id="prevBtn">&#8592;</button>
        <button class="carousel-btn" id="nextBtn">&#8594;</button>
      </div>
      <div class="carousel-indicators">
        <span class="carousel-dot active" data-slide="0"></span>
        <span class="carousel-dot" data-slide="1"></span>
        <span class="carousel-dot" data-slide="2"></span>
      </div>
    </div>
  </section>

  <!-- Best Sellers Section -->
  <main>
    <section class="best-sellers">
      <h2>Top Picks for You</h2>
      <p class="intro-text">
        Discover our most loved fashion pieces! Our best-selling clothes are designed to keep you comfortable and fashionable all year round. Don't miss out—shop the favorites that define today's style.
      </p>
      <div class="filter-buttons">
        <button class="filter-btn active" onclick="filterProducts('All', this)">All</button>
        <button class="filter-btn" onclick="filterProducts('mens', this)">Men</button>
        <button class="filter-btn" onclick="filterProducts('womens', this)">Women</button>
        <button class="filter-btn" onclick="filterProducts('kids', this)">Kids</button>
      </div>
      <div class="products" id="productList">
        <?php $rank = 1; while($product = $result->fetch_assoc()) { ?>
        <article class="card" data-category="<?php echo htmlspecialchars($product['category']); ?>" data-name="<?php echo strtolower(htmlspecialchars($product['name'])); ?>">
          <div class="img-wrap">
            <?php if ($rank <= 3) { ?>
            <span class="best-seller-label">#<?php echo $rank; ?> Best Seller</span>
            <?php } else { ?>
            <span class="best-seller-label">Best Seller</span>
            <?php } ?>
            <img src="../<?php echo $product['image_path'] ?: 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
          </div>
          <div class="card-body">
            <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
            <p class="category"><?php echo ucfirst(htmlspecialchars($product['category'])); ?></p>
            <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
            <button class="btn add-to-cart" data-product-id="<?php echo $product['product_id']; ?>">Add to Cart</button>
          </div>
        </article>
        <?php $rank++; } ?>
      </div>
    </section>
  </main>

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
    // Carousel logic
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    let currentSlide = 0;
    let carouselInterval = null;

    function showCarouselSlide(idx) {
      slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === idx);
        dots[i].classList.toggle('active', i === idx);
      });
      currentSlide = idx;
    }

    function nextCarouselSlide() {
      let idx = (currentSlide + 1) % slides.length;
      showCarouselSlide(idx);
    }

    function prevCarouselSlide() {
      let idx = (currentSlide - 1 + slides.length) % slides.length;
      showCarouselSlide(idx);
    }

    document.getElementById('nextBtn').onclick = () => {
      nextCarouselSlide();
      resetCarouselInterval();
    };
    document.getElementById('prevBtn').onclick = () => {
      prevCarouselSlide();
      resetCarouselInterval();
    };
    dots.forEach((dot, i) => {
      dot.onclick = () => {
        showCarouselSlide(i);
        resetCarouselInterval();
      };
    });

    function startCarouselInterval() {
      if (carouselInterval) clearInterval(carouselInterval);
      carouselInterval = setInterval(nextCarouselSlide, 5000);
    }
    function resetCarouselInterval() {
      startCarouselInterval();
    }
    startCarouselInterval();

    // Enhanced filtering and sorting (like Kids page)
    let currentFilter = 'All';

    function filterProducts(category, clickedButton) {
      currentFilter = category;
      
      // Update active button
      document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
      clickedButton.classList.add('active');

      // Trigger filter and sort
      filterAndSortProducts();
    }

    function searchProducts() {
      const input = document.getElementById("searchBox").value.toLowerCase().trim();
      filterAndSortProducts(input);
    }

    // Filter and sort products (adapted from Kids page)
    function filterAndSortProducts(searchTerm = '') {
      const priceFilter = document.getElementById('priceFilter').value;
      const sortFilter = document.getElementById('sortFilter').value;
      const container = document.querySelector('#productList');
      
      if (!container) return;
      
      let products = Array.from(container.querySelectorAll('.card'));
      
      // Apply search filter
      if (searchTerm) {
        products = products.filter(product => {
          const name = product.getAttribute('data-name') || '';
          return name.includes(searchTerm);
        });
      }
      
      // Apply category filter
      if (currentFilter !== 'All') {
        products = products.filter(product => {
          const category = product.getAttribute('data-category') || '';
          return category === currentFilter;
        });
      }
      
      // Apply price filter
      const filteredProducts = products.filter(product => {
        if (priceFilter === 'all') return true;
        
        // Find the price element
        const priceElement = product.querySelector('.price');
        if (!priceElement) return true;
        
        const price = parseFloat(priceElement.textContent.replace(/[$,]/g, ''));
        
        switch (priceFilter) {
          case 'under50': return price < 50;
          case '50to100': return price >= 50 && price <= 100;
          case 'over100': return price > 100;
          default: return true;
        }
      });
      
      // Sort products
      if (sortFilter !== 'best_sellers') {
        filteredProducts.sort((a, b) => {
          const aPrice = parseFloat(a.querySelector('.price').textContent.replace(/[$,]/g, '')) || 0;
          const bPrice = parseFloat(b.querySelector('.price').textContent.replace(/[$,]/g, '')) || 0;
          
          const aName = a.querySelector('.product-name').textContent.toLowerCase().trim();
          const bName = b.querySelector('.product-name').textContent.toLowerCase().trim();
          
          switch (sortFilter) {
            case 'price_asc': return aPrice - bPrice;
            case 'price_desc': return bPrice - aPrice;
            case 'name_asc': return aName.localeCompare(bName);
            default: return 0;
          }
        });
      }
      
      // Hide all products first
      const allProducts = container.querySelectorAll('.card');
      allProducts.forEach(product => {
        product.style.display = 'none';
      });
      
      // Show and reorder filtered products
      filteredProducts.forEach((product) => {
        product.style.display = 'flex';
        container.appendChild(product);
      });
      
      // Re-attach event listeners to visible products
      filteredProducts.forEach(product => {
        const button = product.querySelector('.add-to-cart');
        if (button) {
          // Remove existing listener to prevent duplicates
          button.removeEventListener('click', addToCartHandler);
          button.addEventListener('click', addToCartHandler);
        }
      });
    }

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
    }

    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', function() {
      // Attach event listeners to all add-to-cart buttons
      document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', addToCartHandler);
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