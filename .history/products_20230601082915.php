<?php
// Start the session
session_start();

// Check if the selected product is stored in the session
if (isset($_SESSION['selected_product'])) {
    // Retrieve the selected product from the session
    $selectedProduct = $_SESSION['selected_product'];

    // Clear the selected product from the session
    unset($_SESSION['selected_product']);

    // Assign the product details to variables
    $productName = $selectedProduct['name'] ?? "";
    $productImage = base64_encode($selectedProduct['image']) ?? "";
    $productDescription = $selectedProduct['description'] ?? "";
    $productPrice = $selectedProduct['price'] ?? "";
} else {
    // If the selected product is not available in the session, redirect back to the products page
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Your E-commerce Website</title>
    <link rel="stylesheet" href="css/styles.css">
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
                <li><a href="cart.php">Cart <span id="cart-counter"></span></a></li>
                <li><a href="checkout.php">Checkout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <div class="product-details">
                <div class="product-image">
                    <img src="data:image/jpeg;base64,<?php echo $productImage; ?>" alt="<?php echo $productName; ?>">
                </div>
                <div class="product-info">
                    <h2><?php echo $productName; ?></h2>
                    <p><?php echo $productDescription; ?></p>
                    <span class="price">$<?php echo $productPrice; ?></span>
                    <!-- Add the button for adding to cart or any other desired functionality -->
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Your E-commerce Website. All rights reserved.</p>
    </footer>

    <div id="notification" class="notification"></div>

    <script src="app.js"></script>
    <script>
        // Update the cart counter on the product_details.php page
        function updateCartCounter() {
            const cartCounter = document.getElementById("cart-counter");
            cartCounter.textContent = cartItems.length;
        }

        // Call the updateCartCounter function when the page loads
        window.addEventListener("DOMContentLoaded", updateCartCounter);
    </script>
</body>
</html>
