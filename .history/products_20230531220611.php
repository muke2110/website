<?php
// Assuming you have already established a database connection
// Replace the database credentials with your own
$servername = "localhost";
$username = "";
$password = "your_password";
$dbname = "ecommerce";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product data from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

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
                <li><a href="index.html">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="cart.html">Cart <span id="cart-counter"></span></a></li>
                <li><a href="checkout.html">Checkout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <div class="product-list">
                <div class="row">
                    <?php
                    // Display product items dynamically using fetched data
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $productId = $row["id"];
                            $productName = $row["name"];
                            $productImage = base64_encode($row["image"]);
                            $productDescription = $row["description"];
                            $productPrice = $row["price"];
                            ?>
                            <div class="product-item">
                                <img src="data:image/jpeg;base64,<?php echo $productImage; ?>" alt="<?php echo $productName; ?>">
                                <h2><?php echo $productName; ?></h2>
                                <p><?php echo $productDescription; ?></p>
                                <span class="price">$<?php echo $productPrice; ?></span>
                                <button onclick="window.location.href='product<?php echo $productId; ?>.html'" class="product-button">View Details</button>
                                <button onclick="handleAddToCartClick('product<?php echo $productId; ?>')">Add to Cart</button>
                            </div>
                            <?php
                        }
                    } else {
                        echo "No products found.";
                    }
                    ?>
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
        // Update the cart counter on the products.php page
        function updateCartCounter() {
            const cartCounter = document.getElementById("cart-counter");
            cartCounter.textContent = cartItems.length;
        }

        // Call the updateCartCounter function when the page loads
        window.addEventListener("DOMContentLoaded", updateCartCounter);
    </script>
</body>
</html>
