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
                <li><a href="cart.php">Cart <span id="cart-counter"></span></a></li>
                <li><a href="checkout.php">Checkout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Cart</h1>
        <div class="cart-container" id="cart-container"></div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Your E-commerce Website. All rights reserved.</p>
    </footer>

    <script>
        // Retrieve cart items from local storage
        const cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];

        // Get the cart container element
        const cartContainer = document.getElementById("cart-container");

        // Function to display cart items
        function displayCartItems() {
            cartContainer.innerHTML = '';

            if (cartItems.length === 0) {
                cartContainer.innerHTML = '<p>Your cart is empty.</p>';
                return;
            }

            for (const cartItem of cartItems) {
                const productId = cartItem.id;
                const quantity = cartItem.quantity;

                // Fetch product details from the database based on the product ID
                fetchProductDetails(productId)
                    .then(product => {
                        // Create a cart item element
                        const cartItemElement = document.createElement("div");
                        cartItemElement.classList.add("cart-item");

                        // Create an image element
                        const imgElement = document.createElement("img");
                        imgElement.src = product.image;
                        imgElement.alt = product.name;
                        imgElement.style.maxWidth = "200px";
                        imgElement.style.height = "auto";

                        // Create a heading element for product name
                        const nameElement = document.createElement("h2");
                        nameElement.textContent = product.name;

                        // Create a paragraph element for product description
                        const descriptionElement = document.createElement("p");
                        descriptionElement.textContent = product.description;

                        // Create a span element for product price
                        const priceElement = document.createElement("span");
                        priceElement.classList.add("price");
                        priceElement.textContent = "$" + product.price;

                        // Create a button element to remove the product from the cart
                        const removeButton = document.createElement("button");
                        removeButton.textContent = "Remove from Cart";
                        removeButton.addEventListener("click", () => {
                            removeFromCart(productId);
                            displayCartItems();
                            updateCartCounter();
                        });

                        // Append all elements to the cart item element
                        cartItemElement.appendChild(imgElement);
                        cartItemElement.appendChild(nameElement);
                        cartItemElement.appendChild(descriptionElement);
                        cartItemElement.appendChild(priceElement);
                        cartItemElement.appendChild(removeButton);

                        // Append the cart item element to the cart container
                        cartContainer.appendChild(cartItemElement);
                    });
            }
        }

        // Function to fetch product details from the database
        function fetchProductDetails(productId) {
            return fetch(`get_product_details.php?id=${productId}`)
                .then(response => response.json());
        }

        // Function to remove an item from the cart
        function removeFromCart(productId) {
            const itemIndex = cartItems.findIndex(item => item.id === productId);
            if (itemIndex !== -1) {
                cartItems.splice(itemIndex, 1);
                localStorage.setItem("cartItems", JSON.stringify(cartItems));
            }
        }

        // Function to update the cart counter
        function updateCartCounter() {
            const cartCounter = document.getElementById("cart-counter");
            cartCounter.textContent = cartItems.length;
        }

        // Display the cart items and update the cart counter on page load
        displayCartItems();
        updateCartCounter();
    </script>
</body>
</html>
