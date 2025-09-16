<?php
// Absolutely clean JSON output - no errors displayed
ob_start();
error_reporting(0);

try {
    // Manual session and database setup to avoid include issues
    session_start();
    
    $servername = "localhost";
    $username = "root";
    $password = "Kavindugimhan@334";
    $dbname = "liora_store";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Database connection failed");
    }
    
    $conn->set_charset("utf8");
    
    // Clear any previous output
    ob_clean();
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    if ($product_id <= 0) {
        throw new Exception('Invalid product ID');
    }

    // Verify product exists
    $product_check = $conn->prepare("SELECT product_id FROM products WHERE product_id = ?");
    if (!$product_check) {
        throw new Exception('Database prepare failed');
    }
    
    $product_check->bind_param("i", $product_id);
    $product_check->execute();
    $product_result = $product_check->get_result();
    
    if ($product_result->num_rows === 0) {
        $product_check->close();
        throw new Exception('Product not found');
    }
    $product_check->close();

    $cart_id = null;

    // Get or create cart
    if (isset($_SESSION['user_id'])) {
        $user_id = (int)$_SESSION['user_id'];
        
        // Check for existing cart
        $cart_stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = ?");
        if (!$cart_stmt) {
            throw new Exception('Cart prepare failed');
        }
        
        $cart_stmt->bind_param("i", $user_id);
        $cart_stmt->execute();
        $cart_result = $cart_stmt->get_result();
        
        if ($cart_result->num_rows === 0) {
            // Create new cart
            $create_cart = $conn->prepare("INSERT INTO cart (user_id) VALUES (?)");
            if (!$create_cart) {
                $cart_stmt->close();
                throw new Exception('Create cart prepare failed');
            }
            
            $create_cart->bind_param("i", $user_id);
            if (!$create_cart->execute()) {
                $create_cart->close();
                $cart_stmt->close();
                throw new Exception('Failed to create cart');
            }
            $cart_id = $conn->insert_id;
            $create_cart->close();
        } else {
            $cart = $cart_result->fetch_assoc();
            $cart_id = $cart['cart_id'];
        }
        $cart_stmt->close();
    } else {
        // Guest cart
        if (!isset($_SESSION['guest_cart_id'])) {
            $session_id = session_id();
            $create_guest_cart = $conn->prepare("INSERT INTO cart (session_id) VALUES (?)");
            if (!$create_guest_cart) {
                throw new Exception('Guest cart prepare failed');
            }
            
            $create_guest_cart->bind_param("s", $session_id);
            if (!$create_guest_cart->execute()) {
                $create_guest_cart->close();
                throw new Exception('Failed to create guest cart');
            }
            $_SESSION['guest_cart_id'] = $conn->insert_id;
            $create_guest_cart->close();
        }
        $cart_id = $_SESSION['guest_cart_id'];
    }

    if (!$cart_id) {
        throw new Exception('Failed to get cart ID');
    }

    // Check if item already exists in cart
    $check_item = $conn->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
    if (!$check_item) {
        throw new Exception('Check item prepare failed');
    }
    
    $check_item->bind_param("ii", $cart_id, $product_id);
    $check_item->execute();
    $item_result = $check_item->get_result();
    
    if ($item_result->num_rows > 0) {
        // Update existing item
        $item = $item_result->fetch_assoc();
        $new_quantity = $item['quantity'] + 1;
        $update_item = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
        if (!$update_item) {
            $check_item->close();
            throw new Exception('Update item prepare failed');
        }
        
        $update_item->bind_param("ii", $new_quantity, $item['cart_item_id']);
        if (!$update_item->execute()) {
            $update_item->close();
            $check_item->close();
            throw new Exception('Failed to update cart item');
        }
        $update_item->close();
    } else {
        // Add new item
        $add_item = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, 1)");
        if (!$add_item) {
            $check_item->close();
            throw new Exception('Add item prepare failed');
        }
        
        $add_item->bind_param("ii", $cart_id, $product_id);
        if (!$add_item->execute()) {
            $add_item->close();
            $check_item->close();
            throw new Exception('Failed to add cart item');
        }
        $add_item->close();
    }
    $check_item->close();

    // Get updated cart count
    $count = 0;
    if (isset($_SESSION['user_id'])) {
        $count_stmt = $conn->prepare("SELECT SUM(ci.quantity) as count FROM cart_items ci JOIN cart c ON ci.cart_id = c.cart_id WHERE c.user_id = ?");
        if ($count_stmt) {
            $count_stmt->bind_param("i", $_SESSION['user_id']);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            if ($count_result && $row = $count_result->fetch_assoc()) {
                $count = $row['count'] ?: 0;
            }
            $count_stmt->close();
        }
    } else {
        $count_stmt = $conn->prepare("SELECT SUM(quantity) as count FROM cart_items WHERE cart_id = ?");
        if ($count_stmt) {
            $count_stmt->bind_param("i", $cart_id);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            if ($count_result && $row = $count_result->fetch_assoc()) {
                $count = $row['count'] ?: 0;
            }
            $count_stmt->close();
        }
    }

    echo json_encode([
        'success' => true, 
        'cartCount' => $count,
        'message' => 'Product added to cart successfully'
    ]);

} catch (Exception $e) {
    // Ensure clean output
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage(),
        'cartCount' => 0
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
    ob_end_flush();
}
exit;