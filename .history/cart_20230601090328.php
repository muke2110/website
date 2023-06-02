<?php
session_start();

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

// Retrieve the cart items from the session
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Retrieve the product details for the cart items
foreach ($cartItems as $cartItem) {
    $productId = $cartItem['id'];
    $quantity = $cartItem['quantity'];

    // Retrieve the product details based on the cart item
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Display the product details in the cart
    if ($product) {
        $productName = $product['name'];
        $productPrice = $product['price'];
        $subtotal = $productPrice * $quantity;

        echo "<div class='cart-item'>";
        echo "<div class='product-info'>";
        echo "<h3>$productName</h3>";
        echo "<p>Price: $productPrice</p>";
        echo "<p>Quantity: $quantity</p>";
        echo "<p>Subtotal: $subtotal</p>";
        echo "</div>";
        echo "<div class='product-image'>";
        echo "<img src='data:image/jpeg;base64," . base64_encode($product['image']) . "' alt='$productName'>";
        echo "</div>";
        echo "</div>";
    }
}

// Calculate the cart total
function calculateCartTotal($cartItems) {
    $total = 0;

    foreach ($cartItems as $cartItem) {
        $productId = $cartItem['id'];
        $quantity = $cartItem['quantity'];

        // Retrieve the product details based on the cart item
        $sql = "SELECT price FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $price = $product['price'];
            $subtotal = $price * $quantity;
            $total += $subtotal;
        }
    }

    return $total;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Your E-commerce Website</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .cart-item .product-info {
            flex: 1;
        }
        .cart-item h3, .cart-item p {
            margin: 0;
        }
        .cart-item .product-image img {
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>
    <div class="cart-items">
        <?php
        if (count($cartItems) > 0) {
            foreach ($cartItems as $cartItem) {
                // Display the product details in the cart
                // ...
            }
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
    </div>
    <div class="cart-total">
        <?php
        $total = calculateCartTotal($cartItems);
        echo "<h2>Total: $total</h2>";
        ?>
    </div>
</body>
</html>
