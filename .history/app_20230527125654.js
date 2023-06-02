// app.js

// Get the cart counter element
const cartCounter = document.getElementById("cart-counter");

// Initialize the cart items array
let cartItems = [];

// Function to add an item to the cart
function addToCart(productId) {
  // Check if the product is already in the cart
  const existingItem = cartItems.find(item => item.id === productId);

  if (existingItem) {
    // If the product is already in the cart, increment its quantity
    existingItem.quantity++;
  } else {
    // If the product is not in the cart, add it as a new item
    const newItem = { id: productId, quantity: 1 };
    cartItems.push(newItem);
  }

  // Update the cart counter
  updateCartCounter();
}

// Function to update the cart counter
function updateCartCounter() {
  const totalItems = cartItems.reduce((total, item) => total + item.quantity, 0);
  cartCounter.textContent = totalItems.toString();
}

// Function to initialize the cart counter
function initializeCartCounter() {
  updateCartCounter();
}

// Call the initializeCartCounter function when the page loads
initializeCartCounter();
