let cart = JSON.parse(localStorage.getItem("cart")) || [];

function addToCart(name, price, image) {
    const existingProduct = cart.find(item => item.name === name);
    
    if (existingProduct) {
        existingProduct.quantity += 1;
    } else {
        cart.push({ name, price, image, quantity: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartUI();
    alert(`${name} added to cart! 🛒`);
}

function updateCartUI() {
    const cartContainer = document.getElementById("cart-items-container");
    if (!cartContainer) return; // Exit if the element does not exist

    cartContainer.innerHTML = "";
    let total = 0;

    if (cart.length === 0) {
        cartContainer.innerHTML = `<tr><td colspan="6" class="text-center text-secondary">Your cart is empty.</td></tr>`;
        document.getElementById("cart-total").textContent = "$0.00";
        return;
    }

    cart.forEach((product, index) => {
        total += product.price * product.quantity;

        let cartRow = `
            <tr>
                <td><img src="${product.image}" width="50"></td>
                <td>${product.name}</td>
                <td>$${product.price}</td>
                <td><input type="number" value="${product.quantity}" min="1" class="form-control text-center"
                    onchange="changeQuantity(${index}, this.value)">
                </td>
                <td>$${(product.price * product.quantity).toFixed(2)}</td>
                <td><button class="btn btn-danger" onclick="removeFromCart(${index})">Remove</button></td>
            </tr>
        `;
        cartContainer.innerHTML += cartRow;
    });

    document.getElementById("cart-total").textContent = `$${total.toFixed(2)}`;
}

function removeFromCart(index) {
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartUI();
}

function changeQuantity(index, newQuantity) {
    if (newQuantity < 1) return;
    cart[index].quantity = parseInt(newQuantity);
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartUI();
}

function loadCart() {
    cart = JSON.parse(localStorage.getItem("cart")) || [];
    updateCartUI();
}

// Load cart only when the cart page is shown
$(document).on("click", "a[href='#cart']", function () {
    setTimeout(() => {
        if (document.getElementById("cart-items-container")) {
            updateCartUI();
        }
    }, 500);

    function goToPayment() {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        if (cart.length === 0) {
            alert("Your cart is empty. Add items before proceeding!");
            return;
        }

        let total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        localStorage.setItem("cartTotal", total.toFixed(2));

        window.location.hash = "#payment"; // Navigate to the Payment Page
    }
});
