<?php
ob_start();
error_reporting(0);

try {
    session_start();
    
    header('Content-Type: application/json');
    
    // Basic database connection test
    $servername = "localhost";
    $username = "root";
    $password = "Kavindugimhan@334";
    $dbname = "liora_store";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST requests allowed');
    }
    
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    if ($product_id <= 0) {
        throw new Exception('Invalid product ID');
    }
    
    // Simple success response for testing
    echo json_encode([
        'success' => true,
        'message' => 'Cart test successful',
        'cartCount' => 1,
        'debug' => [
            'product_id' => $product_id,
            'session_id' => session_id(),
            'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'none'
        ]
    ]);
    
} catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'cartCount' => 0
    ]);
}

ob_end_flush();
?>