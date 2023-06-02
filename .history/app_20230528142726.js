// Check if the cartItems array already exists in local storage
const existingCartItems = localStorage.getItem("cartItems");
let cartItems = existingCartItems ? JSON.parse(existingCartItems) : [];

// Function to update the cart counter
function updateCartCounter() {
  const cartCounter = document.getElementById("cart-counter");
  cartCounter.textContent = cartItems.length;
}

// Function to add an item to the cart
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

  // Save the updated cartItems array in local storage
  localStorage.setItem("cartItems", JSON.stringify(cartItems));

  // Update the cart counter
  updateCartCounter();
}

// Function to remove an item from the cart
function removeFromCart(product) {
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
      <ul>
        ${cartItems
          .map(
            item => `
            <li>
              ${item.id} - Quantity: ${item.quantity}
              <button onclick="removeFromCart('${item.id}')">Remove</button>
            </li>
          `
          )
          .join("")}
      </ul>
    `;
  }
}

// Function to handle the "Add to Cart" button click
function handleAddToCartClick(product) {
  // Add the product to the cart
  addToCart(product);

  // Show notification
  const notification = document.getElementById('notification');
  notification.innerText = 'Item is added';
  notification.style.display = 'block';

  // Hide notification after 3 seconds
  setTimeout(() => {
    notification.style.display = 'none';
  }, 3000);

  // Update cart count
  const cartCounter = document.getElementById('cart-counter');
  cartCounter.innerText = cartItems.length;
}

// Call the updateCartCounter and displayCartItems functions when the page loads
window.addEventListener("DOMContentLoaded", () => {
  updateCartCounter();
  displayCartItems();
});
