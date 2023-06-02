// Check if the cartItems array already exists in local storage
const existingCartItems = localStorage.getItem("cartItems");
let cartItems = existingCartItems ? JSON.parse(existingCartItems) : [];

// Function to update the cart counter
function updateCartCounter() {
  const cartCounter = document.getElementById("cart-counter");
  cartCounter.textContent = cartItems.length;
}



// Function to add an item to the cart
function addToCart(productId, productName, productPrice, productImage) {
  // Check if the product is already in the cart
  const existingItem = cartItems.find(item => item.productId === productId);

  if (existingItem) {
    // If the product is already in the cart, increase its quantity
    existingItem.quantity++;
  } else {
    // If the product is not in the cart, add it as a new item
    const newItem = {
      productId,
      productName,
      productPrice,
      productImage,
      quantity: 1
    };
    cartItems.push(newItem);
  }

  // Save the updated cartItems array in local storage
  localStorage.setItem("cartItems", JSON.stringify(cartItems));

  // Update the cart counter
  updateCartCounter();
}





// Function to remove an item from the cart
function removeFromCart(productId) {
  // Find the index of the item in the cartItems array
  const index = cartItems.findIndex(item => item.id === productId);

  if (index !== -1) {
    // Remove the item from the cartItems array
    cartItems.splice(index, 1);

    // Save the updated cartItems array in local storage
    localStorage.setItem("cartItems", JSON.stringify(cartItems));

    // Update the cart counter
    updateCartCounter();

    // Call displayCartItems to refresh the cart items display
    displayCartItems();
  }
}

// Function to display the cart items in the cart.html page
function displayCartItems() {
  const cartItemsContainer = document.getElementById("cart-items");

  // Check if the cart is empty
  if (cartItems.length === 0) {
    cartItemsContainer.innerHTML = "<p>Your cart is empty.</p>";
  } else {
    cartItemsContainer.innerHTML = `
      <h3>Cart Items</h3>
      <div class="cart-items-grid">
        ${cartItems
          .map(
            item => `
            <div class="cart-item">
              <div class="cart-item-image">
                <img src="${item.productImage}" alt="${item.productName}">
              </div>
              <div class="cart-item-details">
                <p>${item.productName}</p>
                <p>${item.productPrice}</p>
                <button onclick="removeFromCart('${item.productId}')">Remove</button>
              </div>
            </div>
          `
          )
          .join("")}
      </div>
    `;
  }
}


// Function to handle the "Add to Cart" button click
function handleAddToCartClick(productId) {
  // Add to cart logic

  // Get the necessary product details (e.g., name, price, image)
  const productName = "Product 1"; // Replace with the actual product name
  const productPrice = "$9.99"; // Replace with the actual product price
  const productImage = "images/product1.png"; // Replace with the actual product image path

  // Call the addToCart function with the product details
  addToCart(productId, productName, productPrice, productImage);

  // Show the notification
  const notification = document.getElementById("notification");
  notification.innerText = "Item is added";
  notification.style.display = "block";

  // Hide the notification after 3 seconds
  setTimeout(() => {
    notification.style.display = "none";
  }, 3000);
}


// Call the updateCartCounter and displayCartItems functions when the page loads
window.addEventListener("DOMContentLoaded", () => {
  updateCartCounter();
  displayCartItems();
});
