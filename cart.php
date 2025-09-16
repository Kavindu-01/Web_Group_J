<?php
include 'includes/db_connection.php';

// Get cart items
function getCartItems() {
    global $conn;
    
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT ci.*, p.name, p.price, p.image_path 
                FROM cart_items ci 
                JOIN cart c ON ci.cart_id = c.cart_id 
                JOIN products p ON ci.product_id = p.product_id 
                WHERE c.user_id = $user_id";
    } else if (isset($_SESSION['guest_cart_id'])) {
        $cart_id = $_SESSION['guest_cart_id'];
        $sql = "SELECT ci.*, p.name, p.price, p.image_path 
                FROM cart_items ci 
                JOIN products p ON ci.product_id = p.product_id 
                WHERE ci.cart_id = $cart_id";
    } else {
        return [];
    }
    
    $result = $conn->query($sql);
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}

// Handle AJAX remove item requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove_item') {
    $cart_item_id = $_POST['cart_item_id'];
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
    $stmt->bind_param("i", $cart_item_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit();
}

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $cart_item_id => $quantity) {
        if ($quantity > 0) {
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
            $stmt->bind_param("ii", $quantity, $cart_item_id);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
            $stmt->bind_param("i", $cart_item_id);
            $stmt->execute();
        }
    }
    header("Location: cart.php");
    exit();
}

$cart_items = getCartItems();
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shopping Cart - Liora Store</title>
  <meta name="description" content="Your shopping cart." />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    html { scroll-behavior: smooth; }
    body { 
      font-family: 'Inter', sans-serif; 
      background-color: beige; 
      margin: 0;
      padding: 0;
    }
    
    /* Header */
    .site-header { 
      background-color: black; 
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .header-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: white;
    }
    .logo { 
      font-size: 1.5rem; 
      font-weight: bold; 
      letter-spacing: -0.025em;
      color: white;
      text-decoration: none;
    }
    .main-nav { display: none; }
    .nav-links { 
      display: flex; 
      align-items: center; 
      gap: 1.5rem; 
      font-weight: 500; 
    }
    .nav-links a { 
      color: white; 
      text-decoration: none;
      transition: color 0.2s;
    }
    .nav-links a:hover { color: rgba(255,255,255,0.7); }
    .auth-section { 
      display: flex; 
      align-items: center; 
      gap: 1.25rem; 
    }
    .auth-section a { 
      color: white; 
      font-weight: 600;
      text-decoration: none;
    }
    .login-btn { 
      padding: 0.5rem 1rem; 
      border-radius: 0.5rem; 
      background-color: white; 
      color: black !important;
      transition: opacity 0.2s;
    }
    .login-btn:hover { opacity: 0.9; }
    .menu-btn { 
      display: block; 
      padding: 0.5rem; 
      border-radius: 0.5rem; 
      background: transparent;
      border: none;
      color: white;
      cursor: pointer;
    }
    .menu-btn:hover { background-color: rgba(255,255,255,0.1); }
    
    /* Mobile Menu */
    .mobile-menu { 
      display: none; 
      background-color: #334155; 
      color: white; 
      border-top: 1px solid rgba(255,255,255,0.1);
      padding: 0.75rem 1rem; 
    }
    .mobile-menu a { 
      display: block; 
      padding: 0.5rem 0;
      color: white;
      text-decoration: none;
    }
    
    /* Main Content */
    main { 
      padding: 2.5rem 1rem; 
      margin: 0 auto; 
      max-width: 64rem; 
    }
    h1 { 
      font-size: 1.875rem; 
      font-weight: bold; 
      text-align: center; 
      margin-bottom: 2rem; 
      color: #1e293b;
    }
    
    .cart-container { 
      background-color: white; 
      padding: 1.5rem; 
      border-radius: 0.5rem; 
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); 
    }
    
    .cart-item { 
      display: flex;
      align-items: center;
      padding: 1rem 0;
      border-bottom: 1px solid #e5e7eb;
    }
    .cart-item:last-child { border-bottom: none; }
    
    .item-image { 
      width: 80px; 
      height: 80px; 
      object-fit: cover; 
      border-radius: 0.5rem;
      margin-right: 1rem;
    }
    
    .item-details { 
      flex: 1; 
      margin-right: 1rem;
    }
    .item-name { 
      font-weight: 600; 
      margin-bottom: 0.25rem;
      color: #1e293b;
    }
    .item-price { 
      color: #6b7280; 
      font-size: 0.875rem;
    }
    
    .quantity-controls { 
      display: flex; 
      align-items: center; 
      gap: 0.5rem;
      margin-right: 1rem;
    }
    .quantity-btn { 
      width: 2rem; 
      height: 2rem; 
      border: 1px solid #d1d5db; 
      background-color: white; 
      border-radius: 0.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-weight: 600;
    }
    .quantity-btn:hover { background-color: #f9fafb; }
    .quantity-input { 
      width: 3rem; 
      text-align: center; 
      border: 1px solid #d1d5db; 
      border-radius: 0.25rem;
      padding: 0.25rem;
    }
    
    .item-total { 
      font-weight: 600;
      color: #1e293b;
      min-width: 80px;
      text-align: right;
    }
    
    .remove-btn { 
      color: #dc2626; 
      background: none;
      border: none;
      cursor: pointer;
      font-size: 0.875rem;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
    }
    .remove-btn:hover { background-color: #fef2f2; }
    
    .cart-summary { 
      margin-top: 1.5rem; 
      padding-top: 1.5rem; 
      border-top: 1px solid #e5e7eb; 
    }
    .summary-row { 
      display: flex; 
      justify-content: space-between; 
      margin-bottom: 0.5rem;
    }
    .summary-total { 
      font-size: 1.125rem; 
      font-weight: bold;
      color: #1e293b;
    }
    
    .cart-actions { 
      display: flex; 
      gap: 1rem; 
      margin-top: 1.5rem;
    }
    .btn { 
      padding: 0.75rem 1.5rem; 
      border-radius: 0.5rem; 
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
      text-align: center;
      cursor: pointer;
      border: none;
      transition: all 0.2s;
    }
    .btn-primary { 
      background-color: black; 
      color: white; 
    }
    .btn-primary:hover { background-color: #374151; }
    .btn-secondary { 
      background-color: #6b7280; 
      color: white; 
    }
    .btn-secondary:hover { background-color: #4b5563; }
    
    .empty-cart { 
      text-align: center; 
      padding: 3rem 1rem;
    }
    .empty-cart h2 { 
      font-size: 1.5rem; 
      margin-bottom: 1rem;
      color: #6b7280;
    }
    .empty-cart p { 
      color: #9ca3af; 
      margin-bottom: 2rem;
    }
    
    /* Footer */
    .site-footer { 
      padding: 2.5rem 0; 
      border-top: 1px solid #e5e7eb; 
      margin-top: 2.5rem; 
      background-color: white;
    }
    .footer-inner { 
      margin: 0 auto; 
      max-width: 1200px; 
      padding: 0 1rem; 
      display: flex; 
      flex-direction: column;
      align-items: center; 
      justify-content: space-between; 
      gap: 1rem;
    }
    .footer-inner p { 
      font-size: 0.875rem; 
      color: #6b7280; 
    }
    .footer-links { 
      display: flex; 
      align-items: center; 
      gap: 1rem; 
      font-size: 0.875rem;
    }
    .footer-links a { 
      color: #6b7280;
      text-decoration: none;
    }
    .footer-links a:hover { color: #374151; }
    
    /* Responsive */
    @media (min-width: 768px) { 
      .main-nav { display: block; }
      .menu-btn { display: none; }
      .footer-inner { flex-direction: row; }
      .cart-item { align-items: flex-start; }
      .item-details { margin-right: 2rem; }
    }
    
    @media (max-width: 767px) {
      .cart-item { flex-direction: column; align-items: flex-start; gap: 1rem; }
      .cart-actions { flex-direction: column; }
      .quantity-controls { justify-content: center; }
      .item-total { text-align: left; }
    }
  </style>
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      <a href="index.php" class="logo">LIORA</a>
      <nav class="main-nav">
        <ul class="nav-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="Mens Page/Men.php">Men</a></li>
          <li><a href="Women Page/index.php">Women</a></li>
          <li><a href="Kids_Page/kids.php">Kids</a></li>
          <li><a href="Best Sellers Page/project.php">Best Sellers</a></li>
        </ul>
      </nav>
      <div class="auth-section">
        <a href="cart.php" style="color: #fbbf24;">Cart</a>
        <?php if (isset($_SESSION['user_id'])) { ?>
          <a href="profile.php" class="login-btn">Profile</a>
        <?php } else { ?>
          <a href="signin.php" class="login-btn">Login</a>
        <?php } ?>
      </div>
      <button class="menu-btn" id="menuBtn">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
    <div class="mobile-menu" id="mobileMenu">
      <a href="index.php">Home</a>
      <a href="Mens Page/Men.php">Men</a>
      <a href="Women Page/index.php">Women</a>
      <a href="Kids_Page/kids.php">Kids</a>
      <a href="Best Sellers Page/project.php">Best Sellers</a>
      <a href="cart.php" style="font-weight: 600;">Cart</a>
    </div>
  </header>

  <main>
    <h1>Your Shopping Cart</h1>
    
    <div class="cart-container">
      <?php if (empty($cart_items)) { ?>
        <div class="empty-cart">
          <h2>Your cart is empty</h2>
          <p>Looks like you haven't added anything to your cart yet.</p>
          <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        </div>
      <?php } else { ?>
        <form method="POST" action="">
          <div id="cart-items-container">
            <?php foreach ($cart_items as $item) { 
              $item_total = $item['price'] * $item['quantity'];
              $total += $item_total;
            ?>
              <div class="cart-item">
                <img src="<?php echo htmlspecialchars($item['image_path'] ?: 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                
                <div class="item-details">
                  <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                  <div class="item-price">$<?php echo number_format($item['price'], 2); ?> each</div>
                </div>
                
                <div class="quantity-controls">
                  <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $item['cart_item_id']; ?>, -1)">-</button>
                  <input type="number" name="quantity[<?php echo $item['cart_item_id']; ?>]" 
                         value="<?php echo $item['quantity']; ?>" min="0" class="quantity-input" 
                         onchange="updateItemTotal(this, <?php echo $item['price']; ?>)">
                  <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $item['cart_item_id']; ?>, 1)">+</button>
                </div>
                
                <div class="item-total">$<?php echo number_format($item_total, 2); ?></div>
                
                <button type="button" class="remove-btn" onclick="removeItem(<?php echo $item['cart_item_id']; ?>)">Remove</button>
              </div>
            <?php } ?>
          </div>
          
          <div class="cart-summary">
            <div class="summary-row">
              <span>Subtotal:</span>
              <span>$<?php echo number_format($total, 2); ?></span>
            </div>
            <div class="summary-row">
              <span>Shipping:</span>
              <span>Free</span>
            </div>
            <div class="summary-row summary-total">
              <span>Total:</span>
              <span>$<?php echo number_format($total, 2); ?></span>
            </div>
          </div>
          
          <div class="cart-actions">
            <button type="submit" name="update_cart" class="btn btn-secondary">Update Cart</button>
            <?php if (isset($_SESSION['user_id'])) { ?>
              <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            <?php } else { ?>
              <a href="signin.php" class="btn btn-primary">Sign In to Checkout</a>
            <?php } ?>
          </div>
        </form>
      <?php } ?>
    </div>
  </main>

  <footer class="site-footer">
    <div class="footer-inner">
      <p>© <span id="year"><?php echo date('Y'); ?></span> Liora. All rights reserved.</p>
      <div class="footer-links">
        <a href="aboutus.html">About Us</a>
        <a href="contactus.html">Contact</a>
        <a href="terms.html">Terms</a>
      </div>
    </div>
  </footer>

  <script>
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

    // Quantity update functions
    function updateQuantity(cartItemId, change) {
      const input = document.querySelector(`input[name="quantity[${cartItemId}]"]`);
      const currentValue = parseInt(input.value);
      const newValue = Math.max(0, currentValue + change);
      input.value = newValue;
      
      if (newValue === 0) {
        removeItem(cartItemId);
      }
    }

    function removeItem(cartItemId) {
      if (confirm('Are you sure you want to remove this item from your cart?')) {
        // Use AJAX to remove item
        fetch('cart.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `action=remove_item&cart_item_id=${cartItemId}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Reload the page to update the cart display
            window.location.reload();
          } else {
            alert('Error removing item from cart');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          // Fallback to form submission method
          const input = document.querySelector(`input[name="quantity[${cartItemId}]"]`);
          input.value = 0;
          const form = input.closest('form');
          form.submit();
        });
      }
    }

    function updateItemTotal(input, price) {
      const quantity = parseInt(input.value) || 0;
      const itemTotal = quantity * price;
      const totalElement = input.closest('.cart-item').querySelector('.item-total');
      totalElement.textContent = '$' + itemTotal.toFixed(2);
    }
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
</body>
</html>