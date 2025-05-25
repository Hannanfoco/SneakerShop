let allProducts = [];

let cart = [];

async function loadAllProducts() {
    const token = localStorage.getItem("user_token");

    try {
        const response = await $.ajax({
            url: `http://localhost/SneakerShop/backend/products`,
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token,
                "Authentication": token
            }
        });

        allProducts = Array.isArray(response) ? response :
                      Array.isArray(response.data) ? response.data : [];

    } catch (err) {
        console.error(" Failed to load products:", err.responseText);
        toastr.error("Could not load products.");
    }
}

// 🔄 Load cart from backend when user views cart page
async function loadCartFromBackend() {
    const token = localStorage.getItem("user_token");
    const user = JSON.parse(localStorage.getItem("user"));
    if (!token || !user) return;

    try {
        if (allProducts.length === 0) {
            await loadAllProducts();
        }

        const cartResponse = await $.ajax({
            url: `http://localhost/SneakerShop/backend/cart?user_id=${user.id}`,
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token,
                "Authentication": token
            }
        });

        const cartItems = Array.isArray(cartResponse.data) ? cartResponse.data : [];

        cart = cartItems.map(cartItem => {
            const product = allProducts.find(p => p.id === cartItem.product_id);
            return {
                ...cartItem,
                name: product?.name || "Unknown",
                price: parseFloat(product?.price) || 0,
                image: product?.image_url
                    ? `http://localhost/SneakerShop/${product.image_url}`
                    : "http://localhost/SneakerShop/images/no-image.png"
            };
        });

        updateCartUI();

    } catch (err) {
        console.error(" Failed to load cart or products:", err.responseText);
        toastr.error("Could not load cart.");
    }
}


async function addToCart(name, price, image, productId) {
    const token = localStorage.getItem("user_token");
    const user = JSON.parse(localStorage.getItem("user"));
    if (!token || !user) return;

    const existingProduct = cart.find(item => item.product_id === productId);

    if (existingProduct) {
        await changeQuantity(existingProduct.id, existingProduct.quantity + 1);
        return;
    }

    try {
        const response = await $.ajax({
            url: "http://localhost/SneakerShop/backend/cart",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                user_id: user.id,
                product_id: productId,
                quantity: 1
            }),
            headers: {
                "Authorization": "Bearer " + token,
                "Authentication": token
            }
        });
        toastr.success(`${name} added to cart!`);
        loadCartFromBackend();
    } catch (err) {
        console.error(" Add to cart failed:", err.responseText);
        toastr.error("Could not add product to cart.");
    }
}

function updateCartUI() {
    const cartContainer = document.getElementById("cart-items-container");
    if (!cartContainer) return;

    cartContainer.innerHTML = "";
    let total = 0;

    if (cart.length === 0) {
        cartContainer.innerHTML = `<tr><td colspan="6" class="text-center text-secondary">Your cart is empty.</td></tr>`;
        document.getElementById("cart-total").textContent = "$0.00";
        return;
    }

    cart.forEach((product, index) => {
        total += product.price * product.quantity;

        const image = product.image || "http://localhost/SneakerShop/images/no-image.png";

        let cartRow = `
            <tr>
                <td><img src="${image}" width="50"></td>
                <td>${product.name}</td>
                <td>$${product.price}</td>
                <td><input type="number" value="${product.quantity}" min="1" class="form-control text-center"
                    onchange="changeQuantity(${product.id}, this.value)">
                </td>
                <td>$${(product.price * product.quantity).toFixed(2)}</td>
                <td><button class="btn btn-danger" onclick="removeFromCart(${product.id})">Remove</button></td>
            </tr>
        `;
        cartContainer.innerHTML += cartRow;
    });

    document.getElementById("cart-total").textContent = `$${total.toFixed(2)}`;
}

async function removeFromCart(cartItemId) {
    const token = localStorage.getItem("user_token");
    if (!token) return;

    try {
        await $.ajax({
            url: `http://localhost/SneakerShop/backend/cart?id=${cartItemId}`,
            method: "DELETE",
            headers: {
                "Authorization": "Bearer " + token,
                "Authentication": token
            }
        });
        toastr.success("Item removed from cart");
        loadCartFromBackend();
    } catch (err) {
        console.error("Remove failed:", err.responseText);
        toastr.error("Could not remove item.");
    }
}

async function changeQuantity(cartItemId, newQuantity) {
    if (newQuantity < 1) return;
    const token = localStorage.getItem("user_token");
    if (!token) return;

    try {
        await $.ajax({
            url: `http://localhost/SneakerShop/backend/cart`,
            method: "PUT",
            contentType: "application/json",
            data: JSON.stringify({ id: cartItemId, quantity: parseInt(newQuantity) }),
            headers: {
                "Authorization": "Bearer " + token,
                "Authentication": token
            }
        });
        loadCartFromBackend();
    } catch (err) {
        console.error(" Quantity change failed:", err.responseText);
        toastr.error("Could not update quantity.");
    }
}

function goToPayment() {
    if (cart.length === 0) {
        alert("Your cart is empty. Add items before proceeding!");
        return;
    }
    const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    localStorage.setItem("cartTotal", total.toFixed(2));
    window.location.hash = "#payment";
}

//  Trigger cart load on cart section navigation
$(document).on("click", "a[href='#cart']", function () {
    setTimeout(() => {
        if (document.getElementById("cart-items-container")) {
            loadCartFromBackend();
        }
    }, 500);
});
//  Load products + cart on page load
document.addEventListener("DOMContentLoaded", async function () {
    const token = localStorage.getItem("user_token");
    const user = localStorage.getItem("user");

    if (token && user) {
        await loadAllProducts();        
        await loadCartFromBackend();     
    }
});
