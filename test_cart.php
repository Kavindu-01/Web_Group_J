<?php
// Simple test version to debug the JSON issue
header('Content-Type: application/json');

// Test if we can output clean JSON
$test_response = [
    'success' => true,
    'message' => 'Test response',
    'cartCount' => 5,
    'debug' => [
        'php_version' => phpversion(),
        'method' => $_SERVER['REQUEST_METHOD'],
        'post_data' => $_POST
    ]
];

echo json_encode($test_response);
exit;
?>