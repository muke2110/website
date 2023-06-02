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

// Retrieve cart products from the session
$cartItems = isset($_SESSION['cart']) ? json_decode($_SESSION['cart'], true) : [];

?>

<!DOCTYPE html>
<html>
<head>
    <!-- Head section code here -->
</head>
<body>
    <!-- Header section code here -->
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
        <!-- Main content code here -->
    </main>

    <!-- Footer section code here -->

    <script src="app.js"></script>
    <script>
        // Function to display the cart items in the cart.html page
        function displayCartItems() {
            // Retrieve cart items from the session
            const existingCartItems = <?php echo json_encode($cartItems); ?>;
            let cartItems = existingCartItems ? existingCartItems : [];

            const cartItemsContainer = document.getElementById("cart-items");
            let totalAmount = 0;

            // Check if the cart is empty
            if (cartItems.length === 0) {
                cartItemsContainer.innerHTML = "<p>Your cart is empty.</p>";
            } else {
                let itemsHTML = "<h3>Cart Items</h3><ul>";

                // Loop through each item in the cart
                cartItems.forEach(item => {
                    // Fetch the product details from the server based on the product ID
                    getProductById(item.id)
                        .then(product => {
                            // Calculate the amount for the item
                            const amount = product.price * item.quantity;
                            totalAmount += amount;

                            // Generate the HTML for the item
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

                            // Update the cart items HTML
                            cartItemsContainer.innerHTML = itemsHTML;

                            // Add the total amount to the cart
                            itemsHTML += `<p>Total Amount: $${totalAmount}</p>`;
                        })
                        .catch(error => {
                            console.error(error);
                        });
                });

                itemsHTML += "</ul>";

                // Update the cart items HTML
                cartItemsContainer.innerHTML = itemsHTML;

                // Add the total amount to the cart
                itemsHTML += `<p>Total Amount: $${totalAmount}</p>`;
            }
        }

        // Add event listener to execute the displayCartItems function when the cart page is loaded
        window.addEventListener("DOMContentLoaded", displayCartItems);

        // Function to fetch product details by ID
        function getProductById(productId) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open("GET", `get_product.php?id=${productId}`, true);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        resolve(response.product);
                    } else {
                        reject(xhr.statusText);
                    }
                };

                xhr.onerror = function() {
                    reject("Network error");
                };

                xhr.send();
            });
        }

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
            let cartItems = <?php echo json_encode($cartItems); ?>;

            // Find the index of the product in the cartItems array
            const itemIndex = cartItems.findIndex(item => item.id === productId);

            if (itemIndex !== -1) {
                // Remove the product from the cartItems array
                cartItems.splice(itemIndex, 1);

                // Update the cart products in the session
                <?php echo "const updatedCartItems = " . json_encode($cartItems) . ";"; ?>
                localStorage.setItem("cartItems", JSON.stringify(updatedCartItems));
            }
        }
    </script>
</body>
</html>
