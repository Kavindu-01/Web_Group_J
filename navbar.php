<?php
function getCartCount() {
    global $conn;
    $count = 0;
    
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT SUM(quantity) as count FROM cart_items ci 
                JOIN cart c ON ci.cart_id = c.cart_id 
                WHERE c.user_id = $user_id";
    } else if (isset($_SESSION['guest_cart_id'])) {
        $cart_id = $_SESSION['guest_cart_id'];
        $sql = "SELECT SUM(quantity) as count FROM cart_items 
                WHERE cart_id = $cart_id";
    }
    
    if (isset($sql)) {
        $result = $conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $count = $row['count'] ?: 0;
        }
    }
    
    return $count;
}

// Determine the base path for links
$base_path = '';
if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
    $base_path = '../';
} else if (strpos($_SERVER['REQUEST_URI'], '/Mens Page/') !== false || 
           strpos($_SERVER['REQUEST_URI'], '/Women Page/') !== false ||
           strpos($_SERVER['REQUEST_URI'], '/Kids_Page/') !== false ||
           strpos($_SERVER['REQUEST_URI'], '/Best Sellers Page/') !== false) {
    $base_path = '../';
}
?>
<style>
/* Header Styles to match Men.php */
* { margin: 0; padding: 0; box-sizing: border-box; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
header { background: black; box-shadow: 0 1px 3px rgba(0,0,0,0.3); position: sticky; top: 0; z-index: 100; }
.header-content { display: flex; align-items: center; justify-content: space-between; padding: 1.5rem 0; }
.logo { font-size: 2.5rem; font-weight: bold; color: white; letter-spacing: 2px; text-decoration: none; }
nav ul { display: flex; gap: 2rem; list-style: none; margin: 0; }
.nav-link { color: white; text-decoration: none; font-size: 1.1rem; font-weight: 500; transition: color 0.3s; }
.nav-link.active, .nav-link:hover { color: #ccc; }
.auth-section { display: flex; align-items: center; gap: 1rem; }
.cart-icon { display: flex; align-items: center; gap: 0.5rem; background: #333; border-radius: 2rem; padding: 0.5rem 1rem; cursor: pointer; transition: background 0.3s; color: white; text-decoration: none; }
.cart-icon:hover { background: #555; }
.cart-count { background: red; color: white; border-radius: 50%; padding: 0.2rem 0.7rem; font-size: 0.9rem; margin-left: 0.3rem; min-width: 1.5rem; text-align: center; }
.auth-btn { background: white; color: black; padding: 0.5rem 1rem; border-radius: 25px; text-decoration: none; font-weight: 500; transition: all 0.3s; }
.auth-btn:hover { background: #f0f0f0; color: black; }
</style>

<header>
    <div class="container header-content">
        <div class="logo">LIORA</div>
        <nav>
            <ul>
                <li><a href="<?php echo $base_path; ?>index.php" class="nav-link">Home</a></li>
                <li><a href="<?php echo $base_path; ?>index.php#new" class="nav-link">New Arrivals</a></li>
                <li><a href="<?php echo $base_path; ?>Mens Page/Men.php" class="nav-link">Men</a></li>
                <li><a href="<?php echo $base_path; ?>Women Page/index.php" class="nav-link">Women</a></li>
                <li><a href="<?php echo $base_path; ?>Kids_Page/kids.php" class="nav-link">Kids</a></li>
                <li><a href="<?php echo $base_path; ?>Best Sellers Page/project.php" class="nav-link">Best Sellers</a></li>
            </ul>
        </nav>
        <div class="auth-section">
            <a href="<?php echo $base_path; ?>cart.php" class="cart-icon">
                <span>Cart</span>
                <span class="cart-count"><?php echo getCartCount(); ?></span>
            </a>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <a href="<?php echo $base_path; ?>profile.php" class="auth-btn">Profile</a>
            <?php } else { ?>
                <a href="<?php echo $base_path; ?>signin.php" class="auth-btn">Login</a>
            <?php } ?>
        </div>
    </div>
</header>