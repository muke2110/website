<?php
session_start();

// Retrieve cart products from the session
$cartProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
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
                        // Convert the base64 image data back to binary
                        $imageData = base64_decode($product['image']);
                        ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($imageData); ?>" alt="<?php echo $product['name']; ?>" style="max-width: 200px; height: auto;">
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

            // Reload the page to reflect the updated cart
            location.reload();
        }

        // Function to remove an item from the cart
        function removeFromCart(productId) {
            // Retrieve the cart products from the session
            let cartProducts = JSON.parse('<?php echo json_encode($cartProducts); ?>');

            // Find the index of the product in the cartProducts array
            const itemIndex = cartProducts.findIndex(item => item.id === productId);

            if (itemIndex !== -1) {
                // Remove the product from the cartProducts array
                cartProducts.splice(itemIndex, 1);

                // Update the cart products in the session
                sessionStorage.setItem('cart', JSON.stringify(cartProducts));
            }
        }

        // Update the cart counter on page load
        const cartCounter = document.getElementById("cart-counter");
        cartCounter.textContent = <?php echo count($cartProducts); ?>;
    </script>
</body>
</html>
