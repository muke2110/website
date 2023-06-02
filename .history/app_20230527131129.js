// Array to store cart items
let cartItems = [];

// Function to update the cart counter
function updateCartCounter() {
  const cartCounter = document.getElementById("cart-counter");
  cartCounter.textContent = cartItems.length;
}

// Function to handle adding items to the cart
function addToCart(productId) {
  // Check if the product is already in the cart
  const existingItem = cartItems.find(item => item.id === productId);

  if (existingItem) {
    // If the product is already in the cart, increase its quantity
    existingItem.quantity++;
  } else {
    // If the product is not in the cart, add it as a new item
    const newItem = {
      id: productId,
      quantity: 1
    };
    cartItems.push(newItem);
  }

  // Update the cart counter
  updateCartCounter();
}

// Function to handle the "Add to Cart" button click
function handleAddToCartClick(productId) {
  addToCart(productId);
  alert("Item added to cart!");
}

// Call the updateCartCounter function when the page loads
window.addEventListener("DOMContentLoaded", updateCartCounter);
