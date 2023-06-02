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

// Retrieve the product details based on the passed product_id
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $sql = "SELECT * FROM products WHERE product_id = " . $productId;
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Your E-commerce Website</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .product-details {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .product-details img {
            max-width: 200px;
            height: auto;
            margin-right: 10px;
        }
        .product-details .product-info {
            flex: 1;
        }
        .product-details h2, .product-details .price, .product-details .description {
            margin: 0;
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
        <?php if ($product) : ?>
            <div class="product-details">
                <?php
                // Retrieve the image data from the BLOB field
                $imageData = $product['image'];
                // Convert the image data to base64 format
                $base64Image = base64_encode($imageData);
                // Generate the data URL for displaying the image
                $imageSrc = 'data:image/jpeg;base64,' . $base64Image;
                ?>
                <img src="<?php echo $imageSrc; ?>" alt="<?php echo $product['name']; ?>">
                <div class="product-info">
                    <h2><?php echo $product['name']; ?></h2>
                    <span class="price">$<?php echo $product['price']; ?></span>
                    <p class="description"><?php echo $product['description']; ?></p>
                    <button onclick="handleAddToCartClick(<?php echo $product['product_id']; ?>)">Add to Cart</button>
                </div>
            </div>
        <?php else : ?>
            <p>Product not found.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Your E-commerce Website. All rights reserved.</p>
    </footer>

    <div id="notification" class="notification"></div>

    <script src="app.js"></script>

</body> 
</html>
