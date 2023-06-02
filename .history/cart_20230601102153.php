<?php
session_start();

// Retrieve cart products from the local storage
$cartItems = isset($_SESSION['cart']) ? json_decode($_SESSION['cart'], true) : [];

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
                <li><a href="cart.php">Cart <span id="cart-counter"><?php echo count($cartItems); ?></span></a></li>
                <li><a href="checkout.php">Checkout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Cart</h1>
        <div id="cart-items"></div>
        <?php if (!empty($cartItems)) : ?>
            <!-- Display the cart items -->
            <div class="cart-container">
                <?php foreach ($cartItems as $item) : ?>
                    <?php
                    // Retrieve the product details from the database based on the product ID
                    $productId = $item['id'];
                    $productSql = "SELECT * FROM products WHERE product_id = '$productId'";
                    $productResult = $conn->query($productSql);
                    $product = $productResult->fetch_assoc();
                    
                    // Convert the base64 image data back to binary
                    $imageData = base64_decode($product['image']);
                    ?>
                    <div class="cart-item">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($imageData); ?>" alt="<?php echo $product['name']; ?>" style="max-width: 200px; height: auto;">
                        <h2><?php echo $product['name']; ?></h2>
                        <p><?php echo $product['description']; ?></p>
                        <span class="price">$<?php echo $product['price']; ?></span>
                        <div class="button-container">
                            <button onclick="handleRemoveFromCartClick(<?php echo $product['product_id']; ?>)">Remove from Cart</button>
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
        

                // Function to display the cart items in the cart.html page
        function displayCartItems() {
            // Retrieve cart items from local storage
            const existingCartItems = localStorage.getItem("cartItems");
            let cartItems = existingCartItems ? JSON.parse(existingCartItems) : [];

            const cartItemsContainer = document.getElementById("cart-items");
            let totalAmount = 0;

            // Check if the cart is empty
            if (cartItems.length === 0) {
            cartItemsContainer.innerHTML = "<p>Your cart is empty.</p>";
            } else {
            let itemsHTML = "<h3>Cart Items</h3><ul>";

            // Loop through each item in the cart
            cartItems.forEach(item => {
                const product = getProductById(item.id);
                const amount = product.price * item.quantity;
                totalAmount += amount;

                itemsHTML += `
                <li>
                    <img src="${product.image}" alt="${product.name}" />
                    <div>
                    <h4>${product.name}</h4>
                    <p>Price: $${product.price}</p>
                    <p>Quantity: ${item.quantity}</p>
                    <p>Amount: $${amount}</p>
                    <button onclick="removeFromCart('${item.id}')">Remove</button>
                    </div>
                </li>
                `;
            });

            itemsHTML += "</ul>";

            // Add the total amount to the cart
            itemsHTML += `<p>Total Amount: $${totalAmount}</p>`;

            cartItemsContainer.innerHTML = itemsHTML;
            }
        }

        // Add event listener to execute the displayCartItems function when the cart page is loaded
        window.addEventListener("DOMContentLoaded", displayCartItems);
        
        // Function to handle removing a product from the cart
        function handleRemoveFromCartClick(productId) {
            // Remove the product from the cart
            removeFromCart(productId);

            // Reload the page to reflect the updated cart
            location.reload();
        }

        // Function to remove an item from the cart
        function removeFromCart(productId) {
            // Retrieve the cart products from the local storage
            let cartItems = JSON.parse(localStorage.getItem("cartItems"));

            // Find the index of the product in the cartItems array
            const itemIndex = cartItems.findIndex(item => item.id === productId);

            if (itemIndex !== -1) {
                // Remove the product from the cartItems array
                cartItems.splice(itemIndex, 1);

                // Update the cart products in the local storage
                localStorage.setItem("cartItems", JSON.stringify(cartItems));
            }
        }

        // Update the cart counter on page load
        const cartCounter = document.getElementById("cart-counter");
        cartCounter.textContent = <?php echo count($cartItems); ?>;
    </script>
</body>
</html>
