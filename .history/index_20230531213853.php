<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Your E-commerce Website</title>
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
        <h1>Welcome to Your E-commerce Website</h1>
        <p>Discover our amazing collection of products and find exactly what you need.</p>
        <a href="products.php" class="btn">Shop Now</a>
    </main>

    <footer>
        <p class="footer-text">&copy; 2023 Your E-commerce Website. All rights reserved.</p>
    </footer>
    
    <script src="app.js"></script>
    <script>
        // Update the cart counter on the index.php page
        function updateCartCounter() {
            const cartCounter = document.getElementById("cart-counter");
            cartCounter.textContent = cartItems.length;
        }

        // Call the updateCartCounter function when the page loads
        window.addEventListener("DOMContentLoaded", updateCartCounter);
    </script>
</body>
</html>
