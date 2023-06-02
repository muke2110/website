// Check if the cartItems array already exists in local storage
const existingCartItems = localStorage.getItem("cartItems");
let cartItems = existingCartItems ? JSON.parse(existingCartItems) : [];

// Function to update the cart counter
function updateCartCounter() {
  const cartCounter = document.getElementById("cart-counter");
  cartCounter.textContent = cartItems.length;
}

// Function to add an item to the cart
function addToCart(product) {
  // Check if the product is already in the cart
  const existingItem = cartItems.find(item => item.id === product.id);

  if (existingItem) {
    // If the product is already in the cart, increase its quantity
    existingItem.quantity++;
    existingItem.amount = existingItem.quantity * existingItem.price;
  } else {
    // If the product is not in the cart, add it as a new item
    const newItem = {
      id: product.id,
      name: product.name,
      price: product.price,
      quantity: 1,
      amount: product.price
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

// Function to get the total amount of the cart
function getTotalAmount() {
  return cartItems.reduce((total, item) => total + item.amount, 0);
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
            ${cartItems.map(item => `
                <li>
                    <img src="${item.image}" alt="${item.name}">
                    <div>
                        <h4>${item.name}</h4>
                        <p>Price: $${item.price}</p>
                        <p>Quantity: ${item.quantity}</p>
                        <p>Amount: $${item.amount}</p>
                        <button onclick="removeFromCart('${item.id}')">Remove</button>
                    </div>
                </li>
            `).join("")}
        </ul>
        <p>Total Amount: $${getTotalAmount()}</p>
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
