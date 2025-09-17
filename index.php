<?php
include '../includes/db_connection.php';

// Get filter parameters
$price_filter = isset($_GET['price']) ? $_GET['price'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Modify query based on filters
$where_clause = "WHERE category = 'womens'";
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Women's Collection — Liora Fashion Store</title>
  <meta name="description" content="Elegant women's clothing and accessories." />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html { scroll-behavior: smooth; }
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: beige;
    }

    /* Header Styles - Consistent with other pages */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
    
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
        display: none !important;
        background: #000000;
        padding: 1rem 2rem;
        border-top: 1px solid #333333;
    }
    
    .mobile-menu.show {
        display: block !important;
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
    
    /* Women's Badge */
    .womens-badge {
        background: linear-gradient(135deg, #ed64a6, #ec4899);
        color: white;
        padding: 0.2rem 0.8rem;
        border-radius: 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(237, 100, 166, 0.3);
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
      position: relative;
      width: 100%;
      max-height: 400px;
      margin: 30px auto;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0px 5px 15px rgba(0,0,0,0.3);
    }
    .hero-banner img {
      width: 100%;
      height: 400px;
      object-fit: cover;
      display: block;
    }
    
    /* Add a dark overlay for better text readability */
    .hero-banner::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1;
    }
    
    .hero-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      color: white;
      text-shadow: 3px 3px 15px rgba(0,0,0,0.9), 1px 1px 5px rgba(0,0,0,0.8);
      z-index: 2;
      background: rgba(0, 0, 0, 0.3);
      padding: 2rem 3rem;
      border-radius: 15px;
      backdrop-filter: blur(5px);
    }
    .hero-text h1 {
      font-size: 42px;
      margin: 0;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .hero-text p {
      font-size: 20px;
      margin: 10px 0;
      font-weight: 500;
      opacity: 0.95;
    }

    /* Carousel */
    .carousel {
      position: relative;
      max-width: 1200px;
      width: 100%;
      margin: 20px auto;
      overflow: hidden;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .slides {
      display: flex;
      transition: transform 1s cubic-bezier(0.77,0,0.175,1);
      width: 100%;
    }
    .slides > div {
      min-width: 100%;
      position: relative;
    }
    .slides img {
      width: 100%;
      max-width: 1200px;
      height: 400px;
      max-height: 400px;
      object-fit: cover;
      display: block;
    }
    .carousel-text {
      position: absolute;
      top: 20px;
      left: 20px;
      color: white;
      background: rgba(0,0,0,0.5);
      padding: 18px 30px;
      border-radius: 8px;
      font-size: 18px;
      font-weight: bold;
      z-index: 2;
      pointer-events: none;
    }
    .carousel-buttons {
      position: absolute;
      top: 50%;
      width: 100%;
      display: flex;
      justify-content: space-between;
      transform: translateY(-50%);
      padding: 0 20px;
      box-sizing: border-box;
    }
    .carousel-buttons button {
      background: rgba(0,0,0,0.5);
      border: none;
      color: white;
      font-size: 24px;
      padding: 10px 15px;
      cursor: pointer;
      border-radius: 50%;
    }
    .carousel-buttons button:hover {
      background: rgba(0,0,0,0.8);
    }

    /* Discount Banner */
    .discount-banner {
      background: linear-gradient(90deg, #ff4081, #ff6f91);
      color: white;
      text-align: center;
      padding: 20px;
      font-size: 22px;
      font-weight: bold;
      border-radius: 10px;
      margin: 20px auto;
      max-width: 1200px;
      box-shadow: 0px 5px 15px rgba(0,0,0,0.3);
      cursor: pointer;
    }
    .discount-banner:hover {
      background: linear-gradient(90deg, #ff6f91, #ff4081);
    }

    /* Categories */
    .categories {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      max-width: 1300px;
      margin: 40px auto;
      padding: 0 20px;
    }
    .category-card {
      position: relative;
      cursor: pointer;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0px 3px 8px rgba(0,0,0,0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .category-card:hover {
      transform: scale(1.05);
      box-shadow: 0px 8px 20px rgba(0,0,0,0.3);
    }
    .category-card img {
      width: 100%;
      height: 325px;
      object-fit: cover;
      display: block;
    }
    .category-card h3 {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(0,0,0,0.6);
      color: white;
      margin: 0;
      padding: 10px;
      text-align: center;
      font-size: 20px;
    }

    /* Product Grid */
    .products {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      padding: 20px;
      max-width: 1200px;
      margin: auto;
    }
    .product-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
      text-align: center;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }
    .product-card:hover {
      transform: scale(1.05);
      box-shadow: 0px 10px 20px rgba(227, 2, 152, 0.3);
    }
    .product-card img {
      width: 100%;
      height: 400px;
      object-fit: contain;
      border-radius: 10px 10px 0 0;
      display: block;
    }
    .product-card h3 { margin: 10px 0; }
    .product-card p { color: #333; font-size: 14px; }
    .add-to-cart {
      background: #ff4081;
      color: white;
      border: none;
      padding: 10px 15px;
      cursor: pointer;
      border-radius: 5px;
      margin: 10px;
    }
    .add-to-cart:hover { background: #d6336c; }

    /* Form Styles */
    form { background: white; padding: 1.5rem; border-radius: 15px; margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center; justify-content: center; flex-wrap: wrap; box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1); }
    form label { font-weight: 600; color: #ff4081; white-space: nowrap; }
    form select { padding: 0.6rem 1rem; border-radius: 25px; border: 2px solid #e6e6e6; background: white; font-size: 1rem; transition: border-color 0.3s; }
    form select:focus { outline: none; border-color: #ff4081; }

    /* Footer Styles */
    footer { background: beige; padding: 3rem 0; border-top: 1px solid #ddd; margin-top: 3rem; }
    .footer-content { display: flex; flex-direction: column; align-items: center; justify-content: between; gap: 1rem; }
    .footer-content p { color: #666; font-size: 0.9rem; }
    .footer-links { display: flex; gap: 2rem; }
    .footer-links a { color: #333; text-decoration: none; font-size: 0.9rem; transition: color 0.3s; }
    .footer-links a:hover { color: #ff4081; }

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
      
      .hero-text h1 { font-size: 28px; }
      .hero-text p { font-size: 16px; }
      .hero-text { padding: 1.5rem 2rem; }
      .discount-banner { font-size: 18px; padding: 15px; }
      .products { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; }
      form { flex-direction: column; gap: 1rem; }
      .footer-content { flex-direction: column; gap: 1rem; }
      .footer-links { flex-direction: column; text-align: center; gap: 1rem; }
    }
    
    @media (max-width: 480px) {
      .container { padding: 0 1rem; }
      .hero-text h1 { font-size: 24px; }
      .hero-text p { font-size: 14px; }
      .hero-text { padding: 1rem 1.5rem; }
      .products { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <!-- Header / Nav -->
  <header class="site-header">
    <div class="header-inner">
      <a href="../index.php" class="logo">LIORA</a>
      <nav class="main-nav">
        <ul class="nav-links">
          <li><a href="../index.php">Home</a></li>
          <li><a href="../index.php#new">New Arrivals</a></li>
          <li><a href="../Mens Page/Men.php">Men</a></li>
          <li><a href="index.php">Women <span class="womens-badge">Fashion</span></a></li>
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
      <a href="../Mens Page/Men.php">Men</a>
      <a href="index.php">Women</a>
      <a href="../Kids_Page/kids.php">Kids</a>
      <a href="../Best Sellers Page/project.php">Best Sellers</a>
      <a href="../cart.php">Cart</a>
    </div>
  </header>

  <!-- Carousel -->
  <section class="carousel">
    <div class="slides">
      <div>
        <img src="../uploads/fashion1.jpg" alt="Fashion Slide 1">
        <div class="carousel-text medium">
          <h2>Trendy Dresses</h2>
          <p>Discover the latest summer styles and vibrant prints.</p>
        </div>
      </div>
      <div>
        <img src="../uploads/tops1.jpg" alt="Fashion Slide 2">
        <div class="carousel-text medium">
          <h2>Stylish Tops</h2>
          <p>Upgrade your wardrobe with chic and comfy tops.</p>
        </div>
      </div>
      <div>
        <img src="../uploads/jeanscollecction.jpg" alt="Fashion Slide 3">
        <div class="carousel-text medium">
          <h2>New Jeans Collection</h2>
          <p>Find your perfect fit in our latest denim arrivals.</p>
        </div>
      </div>
      <div>
        <img src="../uploads/shoesbag.jpg" alt="Fashion Slide 4">
        <div class="carousel-text medium">
          <h2>Accessories & Shoes</h2>
          <p>Complete your look with trendy accessories and shoes.</p>
        </div>
      </div>
    </div>
    <div class="carousel-buttons">
      <button id="prev">&#10094;</button>
      <button id="next">&#10095;</button>
    </div>
  </section>

  <!-- Discount Banner -->
  <div class="discount-banner">
    🔥 Limited Time Offer – <span style="color:yellow;">30% OFF</span> on Women's Collection!
  </div>

  <!-- Categories -->
  <section class="categories">
    <div class="category-card" onclick="filterProducts('all')">
      <img src="https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="All Products">
      <h3>All Products</h3>
    </div>
    <div class="category-card" onclick="filterProducts('dress')">
      <img src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Dresses">
      <h3>Dresses</h3>
    </div>
    <div class="category-card" onclick="filterProducts('tops')">
      <img src="https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Tops">
      <h3>Tops</h3>
    </div>
    <div class="category-card" onclick="filterProducts('jeans')">
      <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Jeans">
      <h3>Jeans</h3>
    </div>
  </section>

  <!-- Fashion Banner -->
  <section class="hero-banner">
    <img src="../uploads/summer.jpg" alt="New Season Arrivals">
    <div class="hero-text">
      <h1>New Season Arrivals</h1>
      <p>LIORA • Affordable Prices</p>
    </div>
  </section>

  <!-- Filter Section -->
  <div class="container">
    <form method="GET">
      <label>Price Range:</label>
      <select name="price" onchange="this.form.submit()">
        <option value="all" <?php echo $price_filter === 'all' ? 'selected' : ''; ?>>All Prices</option>
        <option value="under50" <?php echo $price_filter === 'under50' ? 'selected' : ''; ?>>Under $50</option>
        <option value="50to100" <?php echo $price_filter === '50to100' ? 'selected' : ''; ?>>$50 - $100</option>
        <option value="over100" <?php echo $price_filter === 'over100' ? 'selected' : ''; ?>>Over $100</option>
      </select>
      <label>Sort By:</label>
      <select name="sort" onchange="this.form.submit()">
        <option value="default" <?php echo $sort === 'default' ? 'selected' : ''; ?>>Latest</option>
        <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
        <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
        <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name: A to Z</option>
      </select>
    </form>
  </div>

  <!-- Products Section -->
  <section class="products" id="product-list">
    <?php while($product = $result->fetch_assoc()) { 
      // Determine category class based on product name
      $categoryClass = '';
      $productName = strtolower($product['name']);
      
      if (strpos($productName, 'dress') !== false) {
        $categoryClass = 'dress';
      } elseif (strpos($productName, 'top') !== false || strpos($productName, 'blouse') !== false || strpos($productName, 'shirt') !== false) {
        $categoryClass = 'tops';
      } elseif (strpos($productName, 'jean') !== false || strpos($productName, 'pant') !== false) {
        $categoryClass = 'jeans';
      }
    ?>
    <div class="product-card <?php echo $categoryClass; ?>">
      <img src="../<?php echo $product['image_path'] ?: 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
      <h3><?php echo htmlspecialchars($product['name']); ?></h3>
      <p>$<?php echo number_format($product['price'], 2); ?></p>
      <button class="add-to-cart" data-product-id="<?php echo $product['product_id']; ?>">Add to Cart</button>
    </div>
    <?php } ?>
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
    // Carousel functionality
    const slides = document.querySelector('.slides');
    const slideImages = document.querySelectorAll('.slides img');
    let index = 0;

    document.getElementById('next').addEventListener('click', () => {
      index = (index + 1) % slideImages.length;
      slides.style.transform = `translateX(${-index*100}%)`;
    });
    document.getElementById('prev').addEventListener('click', () => {
      index = (index -1 + slideImages.length) % slideImages.length;
      slides.style.transform = `translateX(${-index*100}%)`;
    });
    setInterval(() => {
      index = (index + 1) % slideImages.length;
      slides.style.transform = `translateX(${-index*100}%)`;
    }, 5000);

    // Filter Products function
    function filterProducts(category) {
      const products = document.querySelectorAll('.product-card');
      products.forEach(product => {
        if (category === 'all' || product.classList.contains(category)) {
          product.style.display = 'block';
        } else {
          product.style.display = 'none';
        }
      });
      
      // Scroll to products section
      document.getElementById('product-list').scrollIntoView({behavior: 'smooth'});
    }

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
      button.addEventListener('click', function() {
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
              this.style.backgroundColor = '#ec4899';
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
    
    // Initialize mobile menu as hidden
    if (mobileMenu) {
      mobileMenu.classList.remove('show');
    }
    
    menuBtn?.addEventListener('click', () => {
      mobileMenu.classList.toggle('show');
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      if (!menuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
        mobileMenu.classList.remove('show');
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
