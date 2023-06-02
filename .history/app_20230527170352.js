// Array to store the cart items
let cartItems = [];

// Function to update the cart counter in the header
function updateCartCounter() {
  const cartCounter = document.getElementById("cart-counter");
  cartCounter.innerText = cartItems.length.toString();
}

// Function to retrieve cart items from local storage
function getCartItemsFromStorage() {
  const storedCartItems = localStorage.getItem("cartItems");
  if (storedCartItems) {
    cartItems = JSON.parse(storedCartItems);
  }
  updateCartCounter();
}

// Function to save cart items to local storage
function saveCartItemsToStorage() {
  localStorage.setItem("cartItems", JSON.stringify(cartItems));
}

// Function to add an item to the cart
function addToCart(productId) {
  // Check if the item is already in the cart
  const existingItem = cartItems.find(item => item.id === productId);

  if (existingItem) {
    // If the item already exists, increment the quantity
    existingItem.quantity++;
  } else {
    // If the item doesn't exist, add it to the cart
    const newItem = {
      id: productId,
      quantity: 1
    };
    cartItems.push(newItem);
  }

  // Save the updated cart items to local storage
  saveCartItemsToStorage();

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
    saveCartItemsToStorage();

    // Update the cart counter
    updateCartCounter();

    // If you are on the cart.html page, reload the page to update the cart items display
    if (window.location.pathname.includes("cart.html")) {
      location.reload();
    }
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

// Function to initialize the cart functionality
function initializeCart() {
  getCartItemsFromStorage();
  updateCartCounter();
}

// Call the initializeCart function to start the cart functionality
initializeCart();
