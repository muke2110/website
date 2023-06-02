<?php
// Start the session
session_start();

// Assuming you have already established a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myecommerce";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product data from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Store the product data in the session
$productData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productData[] = [
            'product_id' => $row['product_id'],
            'name' => $row['name'],
            'image' => $row['image'],
            'description' => $row['description'],
            'price' => $row['price']
        ];
    }
}
$_SESSION['products'] = $productData;

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
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="cart.php">Cart <span id="cart-counter"></span></a></li>
                <li><a href="checkout.php">Checkout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <div class="product-list">
                <div class="row">
                    <?php
                    // Display product items dynamically using session data
                    if (!empty($_SESSION['products'])) {
                        foreach ($_SESSION['products'] as $product) {
                            $productId = $product["product_id"];
                            $productName = $product["name"];
                            $productImage = $product["image"];
                            $productDescription = $product["description"];
                            $productPrice = $product["price"];
                            ?>
                            <div class="product-item">
                                <img src="images/<?php echo $productImage; ?>" alt="<?php echo $productName; ?>">
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
            // Get the cart items from the session or any other storage mechanism
            const cartItems = <?php echo json_encode($_SESSION['cart'] ?? []); ?>;
            cartCounter.textContent = cartItems.length;
        }

        // Call the updateCartCounter function when the page loads
        window.addEventListener("DOMContentLoaded", updateCartCounter);
    </script>
</body>
</html>
