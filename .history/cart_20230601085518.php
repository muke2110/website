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

$cartProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

// Retrieve the product details for all cart items
$productIdList = array_column($cartProducts, 'id');
$productIds = implode(",", $productIdList);
$sql = "SELECT * FROM products WHERE product_id IN ($productIds)";
$result = $conn->query($sql);
$productData = array();
while ($row = $result->fetch_assoc()) {
    $productData[$row['product_id']] = $row;
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
        .cart-item h2, .cart-item .price {
            margin: 0;
        }
        .cart-item button {
            display: block;
            margin-top: 10px;
        }
        .cart-total {
            margin-top: 20px;
            text-align: right;
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
        <?php if (count($cartProducts) > 0) : ?>
            <!-- Display the items in the cart -->
            <div class="cart-container">
                <?php foreach ($cartProducts as $cartItem) : ?>
                    <?php
                    // Retrieve the product details based on the cart item
                    $productId = $cartItem['id'];
                    $product = $productData[$productId];
                    ?>
                    <div class="cart-item">
                        <?php
                        // Retrieve the image data from the BLOB field
                        $imageData = $product['image'];
                        // Convert the image data to base64 format
                        $base64Image = base64_encode($imageData);
                        // Generate the image source URL
                        $imageSrc = 'data:image/jpeg;base64,' . $base64Image;
                        ?>
                        <img src="<?php echo $imageSrc; ?>" alt="Product Image">
                        <h2><?php echo $product['name']; ?></h2>
                        <span class="price">$<?php echo $product['price']; ?></span>
                        <p>Quantity: <?php echo $cartItem['quantity']; ?></p>
                        <button onclick="handleRemoveFromCartClick(<?php echo $product['product_id']; ?>)">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-total">
                <h3>Total: $<?php echo calculateCartTotal($cartProducts); ?></h3>
            </div>
        <?php else : ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Your E-commerce Website. All rights reserved.</p>
    </footer>

    <div id="notification" class="notification"></div>

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

          // Reload the page to reflect the changes
          location.reload();
        }

        // Function to remove an item from the cart
        function removeFromCart(productId) {
          // Find the index of the product in the cart
          const itemIndex = cartItems.findIndex(item => item.id === productId);

          if (itemIndex > -1) {
            // Remove the item from the cartItems array
            cartItems.splice(itemIndex, 1);

            // Save the updated cartItems array in local storage
            localStorage.setItem("cartItems", JSON.stringify(cartItems));
          }
        }



        // Function to show a notification message
        function showNotification(message) {
          const notification = document.getElementById("notification");
          notification.textContent = message;
          notification.style.display = "block";

          // Hide the notification after 3 seconds
          setTimeout(function() {
              notification.style.display = "none";
          }, 3000);
        }

        // Update the cart counter on page load
        updateCartCounter();

                // Calculate the total price of the cart items
        function calculateCartTotal(cartItems) {
          let total = 0;

          for (const item of cartItems) {
            const productId = item.id;
            const quantity = item.quantity;

            // Retrieve the product details based on the cart item
            const product = <?php echo json_encode($productData); ?>[productId];

            if (product && product.price) {
              total += product.price * quantity;
            }
          }

          return total.toFixed(2);
        }
    </script>

</body>
</html>
