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

// Calculate the cart total
function calculateCartTotal($cartItems) {
    global $conn;

    $total = 0;

    foreach ($cartItems as $cartItem) {
        $productId = $cartItem['id'];
        $quantity = $cartItem['quantity'];

        // Retrieve the product details based on the cart item
        $sql = "SELECT price FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $price = $product['price'];
            $subtotal = $price * $quantity;
            $total += $subtotal;
        }
    }

    return $total;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Your E-commerce Website</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .product {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .product img {
            max-width: 100px;
            height: auto;
        }
        .product .details {
            flex: 1;
            padding: 0 10px;
        }
        .product .quantity {
            font-weight: bold;
        }
        .product .price {
            font-weight: bold;
            color: green;
        }
        .cart-total {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
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
        <?php if (count($cartProducts) > 0) : ?>
            <div class="cart-items">
                <?php foreach ($cartProducts as $cartItem) : ?>
                    <div class="product">
                        <?php
                        // Retrieve the product details based on the cart item
                        $productId = $cartItem['id'];
                        $sql = "SELECT * FROM products WHERE product_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $productId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $product = $result->fetch_assoc();

                        if ($product) {
                            // Retrieve the image data from the BLOB field
                            $imageData = $product['image'];
                            // Convert the image data to base64 format
                            $base64Image = base64_encode($imageData);
                            // Generate the data URL for displaying the image
                            $imageSrc = 'data:image/jpeg;base64,' . $base64Image;
                            ?>
                            <img src="<?php echo $imageSrc; ?>" alt="<?php echo $product['name']; ?>">
                            <div class="details">
                                <h2><?php echo $product['name']; ?></h2>
                                <p><?php echo $product['description']; ?></p>
                                <div class="quantity">Quantity: <?php echo $cartItem['quantity']; ?></div>
                                <div class="price">Price: $<?php echo $product['price']; ?></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-total">
                Total: $<?php echo calculateCartTotal($cartProducts); ?>
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
