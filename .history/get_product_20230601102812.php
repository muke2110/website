<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "myecommerce";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the product ID from the query string
$productId = $_GET['id'];

// Fetch the product details from the database based on the ID
$productSql = "SELECT * FROM products WHERE product_id = '$productId'";
$productResult = $conn->query($productSql);
$product = $productResult->fetch_assoc();

// Convert the base64 image data back to binary
$imageData = base64_decode($product['image']);

// Create an associative array containing the product details
$response = [
    'product' => [
        'id' => $product['product_id'],
        'name' => $product['name'],
        'description' => $product['description'],
        'price' => $product['price'],
        'image' => 'data:image/jpeg;base64,' . base64_encode($imageData)
    ]
];

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
