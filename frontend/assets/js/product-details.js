document.addEventListener("DOMContentLoaded", function () {
    $(document).on("spapp.load", function (event, page) {
        if (page.includes("product-details")) {
            console.log("🚀 Loading Product Details...");
            setTimeout(loadProductDetails, 300); // Delay to ensure the page is loaded
        }
    });
});

function loadProductDetails() {
    const productId = localStorage.getItem("selectedProductId");
    const products = JSON.parse(localStorage.getItem("allProducts")) || [];
    const product = products.find(p => p.id == productId);

    if (!product) {
        console.error("❌ No product data found!");
        document.getElementById("product-details-container").innerHTML =
            "<p class='text-center text-danger'>Product not found.</p>";
        return;
    }

    // Ensure elements exist before modifying them
    const imageElement = document.getElementById("product-image");
    const nameElement = document.getElementById("product-name");
    const priceElement = document.getElementById("product-price");
    const descriptionElement = document.getElementById("product-description");
    const addToCartButton = document.getElementById("add-to-cart-btn");
    const addToFavoritesButton = document.getElementById("add-to-favorites-btn");

    if (!imageElement || !nameElement || !priceElement || !descriptionElement || !addToCartButton || !addToFavoritesButton) {
        console.error("❌ Missing elements in product-details.html!");
        return;
    }

    // Populate the product details page
    imageElement.src = product.image;
    imageElement.alt = product.name;
    nameElement.textContent = product.name;
    priceElement.textContent = `$${product.price}`;
    descriptionElement.textContent = product.description;

    // Add to Cart functionality
    addToCartButton.onclick = function () {
        addToCart(product.name, product.price, product.image);
    };

    // Add to Favorites functionality
    addToFavoritesButton.onclick = function () {
        toggleFavorite(product.id, product.name, product.price, product.image, product.description, this);
    };
}
