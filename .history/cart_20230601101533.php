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
    
</body>
</html>
