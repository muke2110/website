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

// Retrieve products from the database
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
    <style>
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .product {
            width: 30%;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .product img {
            max-width: 100%;
            height: auto;
        }
        .product h2, .product p, .product .price {
            margin: 0;
        }
        .product button {
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
        <h1>Product Catalog</h1>
        <?php if ($result && $result->num_rows > 0) : ?>
            <!-- Display the products from the database -->
            <div class="product-container">
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="product">
                        <?php
                        // Retrieve the image data from the BLOB field
                        $imageData = $row['image'];
                        // Convert the image data to base64 format
                        $base64Image = base64_encode($imageData);
                        // Generate the data URL for displaying the image
                        $imageSrc = 'data:image/jpeg;base64,' . $base64Image;
                        ?>
                        <img src="<?php echo $imageSrc; ?>" alt="<?php echo $row['name']; ?>" style="max-width: 200px; height: auto;"> 
                        <h2><?php echo $row['name']; ?></h2>
                        <p><?php echo $row['description']; ?></p>
                        <span class="price">$<?php echo $row['price']; ?></span>
                        <div class="button-container">
                            <button onclick="handleAddToCartClick(<?php echo $row['product_id']; ?>)">Add to Cart</button>
                            <button onclick="window.location.href = 'product_details.php?id=<?php echo $row['product_id']; ?>'">View Details</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p>No products found.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Your E-commerce Website. All rights reserved.</p>
    </footer>

    <div id="notification" class="notification"></div>


    <script src="app.js"></script>
    <script>
        // Check if the cartItems array already exists in local storage
        const existingCartItems = localStorage.getItem("cartItems");
        let cartItems = existingCartItems ? JSON.parse(existingCartItems) : [];

        // Function to update the cart counter
        function updateCartCounter() {
          const cartCounter = document.getElementById("cart-counter");
          cartCounter.textContent = cartItems.length;
        }
       
        

        // Update the cart counter on page load
        updateCartCounter();
    </script>

</body>
</html>
