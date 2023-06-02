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

// Check if the product ID is provided
if (!isset($_GET['id'])) {
    die("Product ID not provided.");
}

$productId = $_GET['id'];

// Retrieve the product from the database based on the product ID
$sql = "SELECT * FROM products WHERE product_id = $productId";
$result = $conn->query($sql);

// Check if the product was found
if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
    // Convert the image data to base64 format
    $imageData = base64_encode($product['image']);
    // Generate the data URL for displaying the image
    $imageSrc = 'data:image/jpeg;base64,' . $imageData;

    // Create an associative array containing the product details
    $productDetails = array(
        'id' => $product['product_id'],
        'name' => $product['name'],
        'description' => $product['description'],
        'price' => $product['price'],
        'image' => $imageSrc
    );

    // Send the product details as JSON response
    header('Content-Type: application/json');
    echo json_encode($productDetails);
} else {
    // No product found with the provided ID
    die("Product not found.");
}

// Close the database connection
$conn->close();
?>
