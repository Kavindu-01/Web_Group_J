<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database (which handles session_start)
include 'includes/db_connection.php';

echo "<h1>Cart Debug Information</h1>";

// Test database connection
echo "<h2>Database Connection:</h2>";
if ($conn) {
    echo "✅ Database connected successfully<br>";
    echo "Database: " . $conn->get_server_info() . "<br>";
} else {
    echo "❌ Database connection failed<br>";
}

// Check session info
echo "<h2>Session Information:</h2>";
echo "Session ID: " . session_id() . "<br>";
echo "User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not logged in') . "<br>";
echo "Guest Cart ID: " . (isset($_SESSION['guest_cart_id']) ? $_SESSION['guest_cart_id'] : 'None') . "<br>";

// Check if tables exist
echo "<h2>Database Tables:</h2>";
$tables = ['users', 'products', 'cart', 'cart_items'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "✅ Table '$table' exists<br>";
    } else {
        echo "❌ Table '$table' missing<br>";
    }
}

// Check if there are any products
echo "<h2>Products Count:</h2>";
$result = $conn->query("SELECT COUNT(*) as count FROM products");
if ($result) {
    $row = $result->fetch_assoc();
    echo "Total products: " . $row['count'] . "<br>";
} else {
    echo "❌ Error counting products: " . $conn->error . "<br>";
}

// Test cart functionality
echo "<h2>Test Cart Creation:</h2>";
try {
    $session_id = session_id();
    $test_cart = $conn->prepare("INSERT INTO cart (session_id) VALUES (?)");
    $test_cart->bind_param("s", $session_id);
    if ($test_cart->execute()) {
        $test_cart_id = $conn->insert_id;
        echo "✅ Test cart created with ID: $test_cart_id<br>";
        
        // Clean up test cart
        $cleanup = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
        $cleanup->bind_param("i", $test_cart_id);
        $cleanup->execute();
        echo "✅ Test cart cleaned up<br>";
        $cleanup->close();
    } else {
        echo "❌ Failed to create test cart: " . $test_cart->error . "<br>";
    }
    $test_cart->close();
} catch (Exception $e) {
    echo "❌ Exception creating test cart: " . $e->getMessage() . "<br>";
}

echo "<h2>PHP Version:</h2>";
echo phpversion() . "<br>";

echo "<h2>POST Data Test:</h2>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST data received:<br>";
    print_r($_POST);
} else {
    echo "No POST data (this is normal for GET request)<br>";
}
?>