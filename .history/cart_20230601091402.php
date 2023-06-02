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

// Retrieve cart products from the database
$cartProducts = array();
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cartIds = array_column($_SESSION['cart'], 'id');
    $cartIdsString = implode(',', $cartIds);

    $sql = "SELECT * FROM products WHERE product_id IN ($cartIdsString)";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cartProducts[] = array(
                'id' => $row['product_id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'image' => $row['image']
            );
        }
    }
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
        .cart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .cart-item {
            width: 30%;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .cart-item img {
            max-width: 100%;
            height: auto;
        }
        .cart-item h2, .cart-item p, .cart-item .price {
            margin: 0;
        }
        .cart-item button {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <h1>Your E-commerce Website</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="cart.php">Cart <span id="cart-counter"><?php echo count($cartProducts); ?></span></a></li>
                <li><a href="checkout.php">Checkout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Cart</h1>
        <?php if (!empty($cartProducts)) : ?>
            <!-- Display the cart items -->
            <div class="cart-container">
                <?php foreach ($cartProducts as $product) : ?>
                    <div class="cart-item">
                        <?php
                        // Retrieve the image data from the BLOB field
                        $imageData = $product['image'];
                        // Convert the image data to base64 format
                        $base64Image = base64_encode($imageData);
                        // Generate the data URL for displaying the image
                        $imageSrc = 'data:image/jpeg;base64,' . $base64Image;
                        ?>
                        <img src="<?php echo $imageSrc; ?>" alt="<?php echo $product['name']; ?>" style="max-width: 200px; height: auto;">
                        <h2><?php echo $product['name']; ?></h2>
                        <p><?php echo $product['description']; ?></p>
                        <span class="price">$<?php echo $product['price']; ?></span>
                        <div class="button-container">
                            <button onclick="handleRemoveFromCartClick(<?php echo $product['id']; ?>)">Remove from Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Your E-commerce Website. All rights reserved.</p>
    </footer>

    <script src="app.js"></script>
    <script>
        // Function to handle removing a product from the cart
        function handleRemoveFromCartClick(productId) {
            // Remove the product from the cart
            removeFromCart(productId);

            // Show notification
            showNotification("Item is removed");

            // Update cart count
            updateCartCounter();

            // Reload the page to update the cart items
            location.reload();
        }

        // Function to remove an item from the cart
        function removeFromCart(productId) {
            // Find the index of the product in the cartItems array
            const itemIndex = cartItems.findIndex(item => item.id === productId);

            if (itemIndex !== -1) {
                // Remove the product from the cartItems array
                cartItems.splice(itemIndex, 1);

                // Save the updated cartItems array in local storage
                localStorage.setItem("cartItems", JSON.stringify(cartItems));
            }
        }

        // Update the cart counter on page load
        updateCartCounter();
    </script>
</body>
</html>
