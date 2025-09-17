<?php
include 'includes/db_connection.php';

// Fetch new arrivals
$new_arrivals_sql = "SELECT * FROM products WHERE is_new_arrival = TRUE ORDER BY created_at DESC LIMIT 8";
$new_arrivals = $conn->query($new_arrivals_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Liora — Fashion Store</title>
  <meta name="description" content="Trendy and stylish clothing for everyone." />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html { 
      scroll-behavior: smooth;
      scroll-padding-top: 100px; /* Account for fixed header */
    }
    body { 
      font-family: 'Inter', sans-serif; 
      background-color: #f5f5dc;
      margin: 0;
      padding: 0;
    }
    
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
      align-items: center;
    }
    
    .nav-links {
      display: flex;
      list-style: none;
      gap: 2rem;
      margin: 0;
      padding: 0;
    }
    
    .nav-links a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      padding: 0.5rem 0;
      transition: color 0.3s ease;
    }
    
    .nav-links a:hover {
      color: #a0aec0;
    }
    
    .auth-section {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .cart-link {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: white;
      text-decoration: none;
      background: #4a5568;
      padding: 0.5rem 1rem;
      border-radius: 2rem;
      transition: background-color 0.3s ease;
    }
    
    .cart-link:hover {
      background: #2d3748;
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
      color: #2d3748;
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
    
    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, #2d3748, #4a5568);
      color: white;
      padding: 6rem 0;
      text-align: center;
    }
    
    .hero-section h1 {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .hero-section p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      opacity: 0.9;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }
    
    .hero-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }
    
    .hero-btn {
      padding: 1rem 2rem;
      border-radius: 2rem;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .hero-btn-primary {
      background: white;
      color: #2d3748;
    }
    
    .hero-btn-primary:hover {
      background: #f7fafc;
      transform: translateY(-2px);
    }
    
    .hero-btn-secondary {
      background: transparent;
      color: white;
      border: 2px solid white;
    }
    
    .hero-btn-secondary:hover {
      background: white;
      color: #2d3748;
    }
    
    /* Container */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
    }
    
    /* Sections */
    .section {
      padding: 4rem 0;
    }
    
    .section-title {
      font-size: 2.5rem;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 1rem;
      text-align: center;
    }
    
    .section-subtitle {
      font-size: 1.1rem;
      color: #718096;
      text-align: center;
      max-width: 600px;
      margin: 0 auto 3rem;
    }
    
    /* Products Grid */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
    }
    
    .product-card {
      background: white;
      border-radius: 1rem;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }
    
    .product-card img {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }
    
    .product-card h3 {
      font-size: 1.2rem;
      font-weight: 600;
      margin: 1rem 1.5rem 0.5rem;
      color: #2d3748;
    }
    
    .product-card p {
      font-size: 1.1rem;
      font-weight: 600;
      color: #4a5568;
      margin: 0 1.5rem 1.5rem;
    }
    
    /* Footer */
    .footer {
      background: #2d3748;
      color: white;
      padding: 3rem 0;
    }
    
    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 2rem;
    }
    
    .footer-links {
      display: flex;
      gap: 2rem;
    }
    
    .footer-links a {
      color: white;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    
    .footer-links a:hover {
      color: #a0aec0;
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
      
      .hero-section h1 {
        font-size: 2.5rem;
      }
      
      .hero-section p {
        font-size: 1rem;
      }
      
      .hero-buttons {
        flex-direction: column;
        align-items: center;
      }
      
      .carousel-wrapper {
        height: 50vh !important;
      }
      
      .carousel-img {
        height: 50vh !important;
      }
      
      .carousel-caption div {
        padding: 1rem 1.5rem !important;
        margin: 0 1rem !important;
      }
      
      .carousel-caption h2 {
        font-size: 2rem !important;
      }
      
      .carousel-caption p {
        font-size: 1rem !important;
        padding: 0 1rem;
      }
      
      #prevBtn, #nextBtn {
        left: 1rem !important;
        right: 1rem !important;
        padding: 0.75rem !important;
        font-size: 1.2rem !important;
      }
      
      #nextBtn {
        left: auto !important;
        right: 1rem !important;
      }
      
      .section-title {
        font-size: 2rem;
      }
      
      .products-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
      }
      
      .footer-content {
        flex-direction: column;
        text-align: center;
      }
      
      .footer-links {
        justify-content: center;
      }
    }
    
    @media (max-width: 480px) {
      .container {
        padding: 0 1rem;
      }
      
      .hero-section {
        padding: 4rem 0;
      }
      
      .hero-section h1 {
        font-size: 2rem;
      }
      
      .carousel-wrapper {
        height: 40vh !important;
      }
      
      .carousel-img {
        height: 40vh !important;
      }
      
      .carousel-caption div {
        padding: 0.8rem 1rem !important;
        margin: 0 0.5rem !important;
      }
      
      .carousel-caption h2 {
        font-size: 1.5rem !important;
      }
      
      .carousel-caption p {
        font-size: 0.9rem !important;
      }
      
      .products-grid {
        grid-template-columns: 1fr;
      }
      
      .footer-links {
        flex-direction: column;
        gap: 1rem;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="site-header">
    <div class="header-inner">
      <a href="index.php" class="logo">LIORA</a>
      <nav class="main-nav">
        <ul class="nav-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="#new">New Arrivals</a></li>
          <li><a href="Mens Page/Men.php">Men</a></li>
          <li><a href="Women Page/index.php">Women</a></li>
          <li><a href="Kids_Page/kids.php">Kids</a></li>
          <li><a href="Best Sellers Page/project.php">Best Sellers</a></li>
        </ul>
      </nav>
      <div class="auth-section">
        <a href="cart.php" class="cart-link">
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
          <a href="profile.php" class="auth-btn">Profile</a>
        <?php } else { ?>
          <a href="signin.php" class="auth-btn">Login</a>
        <?php } ?>
      </div>
      <button class="menu-btn" id="menuBtn">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
    <div class="mobile-menu" id="mobileMenu">
      <a href="index.php">Home</a>
      <a href="#new">New Arrivals</a>
      <a href="Mens Page/Men.php">Men</a>
      <a href="Women Page/index.php">Women</a>
      <a href="Kids_Page/kids.php">Kids</a>
      <a href="Best Sellers Page/project.php">Best Sellers</a>
      <a href="cart.php">Cart</a>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <h1>Discover Your Style</h1>
      <p>Trendy clothing, premium quality, and affordable prices — shop the latest fashion today!</p>
      <div class="hero-buttons">
        <a href="#new" class="hero-btn hero-btn-primary">Shop Now</a>
        <a href="Best Sellers Page/project.php" class="hero-btn hero-btn-secondary">Best Sellers</a>
      </div>
    </div>
  </section>
  </section>

  <!-- Carousel Section -->
  <section class="carousel-section section" style="background: white; padding: 0; position: relative;">
    <div class="container" style="max-width: 100%; padding: 0;">
      <div class="carousel-wrapper" style="position: relative; width: 100%; height: 70vh;">
        <!-- Beautiful text overlays for each slide with custom animations -->
        <div class="carousel-captions" style="position: absolute; z-index: 20; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; pointer-events: none;">
          <div class="carousel-caption" data-caption="0" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.7s ease; opacity: 1; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(5px);">
            <div style="background: rgba(0, 0, 0, 0.6); padding: 2rem 3rem; border-radius: 15px; backdrop-filter: blur(10px); text-align: center; max-width: 700px;">
              <h2 style="font-size: 3.5rem; font-weight: 700; color: white; text-shadow: 3px 3px 15px rgba(0, 0, 0, 0.8), 1px 1px 5px rgba(0, 0, 0, 0.9); text-align: center; margin-bottom: 1rem; letter-spacing: 1px;">
                Curated Fashion Collection
              </h2>
              <p style="font-size: 1.5rem; color: white; font-weight: 500; text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8), 1px 1px 3px rgba(0, 0, 0, 0.9); text-align: center; line-height: 1.6;">
                Discover carefully selected pieces that define modern style and timeless elegance.
              </p>
            </div>
          </div>
          <div class="carousel-caption" data-caption="1" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.7s ease; opacity: 0; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(5px);">
            <div style="background: rgba(0, 0, 0, 0.6); padding: 2rem 3rem; border-radius: 15px; backdrop-filter: blur(10px); text-align: center; max-width: 700px;">
              <h2 style="font-size: 3.5rem; font-weight: 700; color: white; text-shadow: 3px 3px 15px rgba(0, 0, 0, 0.8), 1px 1px 5px rgba(0, 0, 0, 0.9); text-align: center; margin-bottom: 1rem; letter-spacing: 1px;">
                Vibrant Style Selection
              </h2>
              <p style="font-size: 1.5rem; color: white; font-weight: 500; text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8), 1px 1px 3px rgba(0, 0, 0, 0.9); text-align: center; line-height: 1.6;">
                Explore our colorful collection where every piece tells a story of individuality and flair.
              </p>
            </div>
          </div>
          <div class="carousel-caption" data-caption="2" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.7s ease; opacity: 0; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(5px);">
            <div style="background: rgba(0, 0, 0, 0.6); padding: 2rem 3rem; border-radius: 15px; backdrop-filter: blur(10px); text-align: center; max-width: 700px;">
              <h2 style="font-size: 3.5rem; font-weight: 700; color: white; text-shadow: 3px 3px 15px rgba(0, 0, 0, 0.8), 1px 1px 5px rgba(0, 0, 0, 0.9); text-align: center; margin-bottom: 1rem; letter-spacing: 1px;">
                Pattern & Print Paradise
              </h2>
              <p style="font-size: 1.5rem; color: white; font-weight: 500; text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8), 1px 1px 3px rgba(0, 0, 0, 0.9); text-align: center; line-height: 1.6;">
                Find your perfect match in our diverse selection of prints, patterns, and unique designs.
              </p>
            </div>
          </div>
        </div>
        <div class="carousel-slides" style="overflow: hidden; border-radius: 0; box-shadow: none; width: 100%; height: 100%;">
          <div class="carousel-slide" style="display: flex; transition: transform 0.7s ease-in-out; width: 100%; height: 100%;">
            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1920" alt="Curated Fashion Collection" class="carousel-img" style="width: 100%; height: 70vh; object-fit: cover; position: absolute; top: 0; left: 0; transition: opacity 0.7s ease; opacity: 0;">
            <img src="https://images.unsplash.com/photo-1558769132-cb1aea458c5e?q=80&w=1920" alt="Vibrant Style Selection" class="carousel-img" style="width: 100%; height: 70vh; object-fit: cover; position: absolute; top: 0; left: 0; transition: opacity 0.7s ease; opacity: 0;">
            <img src="https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?q=80&w=1920" alt="Pattern & Print Paradise" class="carousel-img" style="width: 100%; height: 70vh; object-fit: cover; position: absolute; top: 0; left: 0; transition: opacity 0.7s ease; opacity: 0;">
          </div>
        </div>
        <!-- Carousel controls -->
        <button id="prevBtn" style="position: absolute; left: 2rem; top: 50%; transform: translateY(-50%); background: rgba(0, 0, 0, 0.6); color: white; border-radius: 50%; padding: 1.25rem; border: none; cursor: pointer; z-index: 30; font-size: 1.5rem; transition: background 0.3s ease;">
          &#8592;
        </button>
        <button id="nextBtn" style="position: absolute; right: 2rem; top: 50%; transform: translateY(-50%); background: rgba(0, 0, 0, 0.6); color: white; border-radius: 50%; padding: 1.25rem; border: none; cursor: pointer; z-index: 30; font-size: 1.5rem; transition: background 0.3s ease;">
          &#8594;
        </button>
      </div>
      <!-- Carousel indicators -->
      <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 1.5rem; position: absolute; left: 50%; transform: translateX(-50%); bottom: 2.5rem; z-index: 30;">
        <button class="carousel-indicator" data-slide="0" style="width: 1.25rem; height: 1.25rem; border-radius: 50%; background: #9ca3af; border: none; cursor: pointer;"></button>
        <button class="carousel-indicator" data-slide="1" style="width: 1.25rem; height: 1.25rem; border-radius: 50%; background: #e5e7eb; border: none; cursor: pointer;"></button>
        <button class="carousel-indicator" data-slide="2" style="width: 1.25rem; height: 1.25rem; border-radius: 50%; background: #e5e7eb; border: none; cursor: pointer;"></button>
      </div>
    </div>
  </section>

  <!-- Categories -->
  <section class="section" style="background-color: #f5f5dc;">
    <div class="container">
      <h2 class="section-title">Shop by Category</h2>
      <div class="products-grid">
        <a href="Mens Page/Men.php" class="product-card" style="position: relative; overflow: hidden;">
          <img src="https://images.unsplash.com/photo-1516826957135-700dedea698c?q=80&w=1200" alt="Men's Fashion" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
          <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.3)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.8rem; font-weight: 700; text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8); letter-spacing: 1px;">Men's Collection</div>
        </a>
        <a href="Women Page/index.php" class="product-card" style="position: relative; overflow: hidden;">
          <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=1200" alt="Women's Fashion" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
          <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.3)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.8rem; font-weight: 700; text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8); letter-spacing: 1px;">Women's Fashion</div>
        </a>
        <a href="Kids_Page/kids.php" class="product-card" style="position: relative; overflow: hidden;">
          <img src="https://images.unsplash.com/photo-1503919545889-aef636e10ad4?q=80&w=1200" alt="Kids Fashion" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
          <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.3)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.8rem; font-weight: 700; text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8); letter-spacing: 1px;">Kids Zone</div>
        </a>
        <a href="Best Sellers Page/project.php" class="product-card" style="position: relative; overflow: hidden;">
          <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1200" alt="Best Sellers" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
          <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.3)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.8rem; font-weight: 700; text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8); letter-spacing: 1px;">Best Sellers</div>
        </a>
      </div>
    </div>
  </section>

  <!-- Featured Products -->
  <section id="new" class="section scroll-reveal" style="background-color: #f5f5dc;">
    <div class="container">
      <h2 class="section-title">New Arrivals</h2>
      <div class="products-grid">
        <?php while($product = $new_arrivals->fetch_assoc()) { ?>
        <div class="product-card">
          <img src="<?php echo $product['image_path'] ?: 'https://images.unsplash.com/photo-1521334884684-d80222895322?q=80&w=800'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 250px; object-fit: cover;">
          <h3 style="font-size: 1.2rem; font-weight: 600; margin: 1rem 1.5rem 0.5rem; color: #2d3748;"><?php echo htmlspecialchars($product['name']); ?></h3>
          <p style="font-size: 1.1rem; font-weight: 600; color: #4a5568; margin: 0 1.5rem 1rem;">$<?php echo number_format($product['price'], 2); ?></p>
          <button class="add-to-cart" data-product-id="<?php echo $product['product_id']; ?>" style="margin: 0 1.5rem 1.5rem; width: calc(100% - 3rem); padding: 0.5rem 1rem; background: #2d3748; color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-weight: 500; transition: background 0.3s ease;" onmouseover="this.style.background='#1a202c'" onmouseout="this.style.background='#2d3748'">Add to Cart</button>
        </div>
        <?php } ?>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <p style="font-size: 0.9rem; color: #a0aec0;">© 2025, Liora Online Store. All rights reserved.</p>
        <div class="footer-links">
          <a href="aboutus.html">About Us</a>
          <a href="contactus.html">Contact Us</a>
          <a href="terms.html">Terms & Conditions</a>
        </div>
      </div>
    </div>
  </footer>

  <style>
    /* Scroll reveal animation */
    .scroll-reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s ease;
    }
    
    .scroll-reveal.revealed {
      opacity: 1;
      transform: translateY(0);
    }
    
    .animate-fade-in-slow {
      animation: fadeIn 2s cubic-bezier(.4,0,.2,1) both;
    }
    .animate-slide-down {
      animation: slideDown 1.2s cubic-bezier(.4,0,.2,1) both;
    }
    .animate-slide-up {
      animation: slideUp 1.2s cubic-bezier(.4,0,.2,1) both;
    }
    .animate-slide-left {
      animation: slideLeft 1.2s cubic-bezier(.4,0,.2,1) both;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px);}
      to { opacity: 1; transform: translateY(0);}
    }
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-60px);}
      to { opacity: 1; transform: translateY(0);}
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(60px);}
      to { opacity: 1; transform: translateY(0);}
    }
    @keyframes slideLeft {
      from { opacity: 0; transform: translateX(60px);}
      to { opacity: 1; transform: translateX(0);}
    }
  </style>

  <script>
    // Smooth scrolling function
    function smoothScrollTo(targetId) {
      const target = document.getElementById(targetId);
      if (target) {
        const headerHeight = document.querySelector('.site-header').offsetHeight;
        const targetPosition = target.offsetTop - headerHeight - 20; // 20px extra padding
        
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
      }
    }

    // Scroll reveal animation
    function revealOnScroll() {
      const reveals = document.querySelectorAll('.scroll-reveal');
      
      reveals.forEach(element => {
        const windowHeight = window.innerHeight;
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < windowHeight - elementVisible) {
          element.classList.add('revealed');
        }
      });
    }

    // Handle smooth scrolling for anchor links
    document.addEventListener('DOMContentLoaded', function() {
      // Initial reveal check
      revealOnScroll();
      
      // Add scroll event listener for reveal animation
      window.addEventListener('scroll', revealOnScroll);
      
      // Add click handlers for smooth scrolling links
      const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
      smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href').substring(1);
          if (targetId) {
            smoothScrollTo(targetId);
            // Close mobile menu if open
            if (mobileMenu && mobileMenu.style.display === 'block') {
              mobileMenu.style.display = 'none';
            }
            
            // Trigger reveal animation after smooth scroll
            setTimeout(() => {
              const target = document.getElementById(targetId);
              if (target && target.classList.contains('scroll-reveal')) {
                target.classList.add('revealed');
              }
            }, 500);
          }
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
    document.addEventListener('click', (e) => {
      if (!menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
        mobileMenu.style.display = 'none';
      }
    });

    // Carousel functionality
    const slides = document.querySelectorAll('.carousel-img');
    const indicators = document.querySelectorAll('.carousel-indicator');
    const captions = document.querySelectorAll('.carousel-caption');
    let currentSlide = 0;
    let isAnimating = false;

    function resetAnimations(caption) {
      const h2 = caption.querySelector('h2');
      const p = caption.querySelector('p');
      h2 && h2.classList.remove('animate-slide-down', 'animate-slide-up', 'animate-slide-left');
      p && p.classList.remove('animate-fade-in-slow');
      void caption.offsetWidth;
      if (caption.dataset.caption == "0") {
        h2 && h2.classList.add('animate-slide-down');
        p && p.classList.add('animate-fade-in-slow');
      } else if (caption.dataset.caption == "1") {
        h2 && h2.classList.add('animate-slide-up');
        p && p.classList.add('animate-fade-in-slow');
      } else if (caption.dataset.caption == "2") {
        h2 && h2.classList.add('animate-slide-left');
        p && p.classList.add('animate-fade-in-slow');
      }
    }

    function showSlide(index) {
      if (isAnimating || index === currentSlide) return;
      isAnimating = true;
      slides[currentSlide].style.opacity = '0';
      captions[currentSlide].style.opacity = '0';
      setTimeout(() => {
        slides[index].style.opacity = '1';
        captions[index].style.opacity = '1';
        indicators.forEach((dot, i) => {
          dot.style.background = i === index ? '#9ca3af' : '#e5e7eb';
        });
        resetAnimations(captions[index]);
        currentSlide = index;
        setTimeout(() => {
          isAnimating = false;
        }, 700);
      }, 700);
    }

    document.getElementById('prevBtn').onclick = () => {
      let idx = (currentSlide - 1 + slides.length) % slides.length;
      showSlide(idx);
    };
    document.getElementById('nextBtn').onclick = () => {
      let idx = (currentSlide + 1) % slides.length;
      showSlide(idx);
    };
    indicators.forEach((dot, i) => {
      dot.onclick = () => showSlide(i);
    });

    // Initialize carousel
    slides.forEach((img, i) => {
      img.style.position = 'absolute';
      img.style.top = '0';
      img.style.left = '0';
      img.style.width = '100%';
      img.style.height = '100%';
      img.style.objectFit = 'cover';
      img.style.transition = 'opacity 0.7s ease';
      if (i === 0) {
        img.style.opacity = '1';
      } else {
        img.style.opacity = '0';
      }
    });
    captions.forEach((cap, i) => {
      cap.style.transition = 'opacity 0.7s ease';
      if (i === 0) {
        cap.style.opacity = '1';
        resetAnimations(cap);
      } else {
        cap.style.opacity = '0';
      }
    });
    showSlide(0);

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        
        if (!productId) {
          console.error('No product ID found');
          alert('Error: Product ID not found');
          return;
        }
        
        fetch('add_to_cart.php', {
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
              this.style.backgroundColor = '#2d3748';
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
  <script src="Scripts/cursor.js"></script>
  <script src="Scripts/particles.js"></script>
</body>
</html>