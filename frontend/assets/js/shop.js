function loadProducts(containerId) {
    const container = document.getElementById(containerId);
    if (!container) {
        console.warn(`⚠️ Skipping loadProducts: Container '${containerId}' not found.`);
        return; // Exit the function if the container is missing
    }

    console.log(`✅ Loading products into: ${containerId}`);
    container.innerHTML = ""; // Clear previous content

    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];

    let row = document.createElement("div");
    row.classList.add("row", "g-4");
    container.appendChild(row);

    products.forEach((product, index) => {
        const isFavorite = favorites.some(fav => fav.id === product.id);
        const productCard = document.createElement("div");
        productCard.classList.add("col-lg-4", "col-md-6", "col-sm-12", "d-flex", "align-items-stretch");
    
        productCard.innerHTML = `
            <div class="card shadow-sm w-100 border-0 p-3" onclick="openProductDetails(${product.id})">
                <div class="position-relative">
                    <img src="${product.image}" class="card-img-top rounded" alt="${product.name}">
                    <button class="btn btn-light position-absolute top-0 end-0 m-2 border rounded-circle favorite-btn" 
                        onclick="toggleFavorite(event, ${product.id}, '${product.name}', '${product.price}', '${product.image}', '${product.description}', this)">
                        ${isFavorite ? '❤️' : '🤍'} 
                    </button>
                </div>
                <div class="card-body d-flex flex-column text-center">
                    <h5 class="card-title fw-bold">${product.name}</h5>
                    <p class="text-muted">$${product.price}</p>
                    <p class="card-text">${product.description}</p>
                    <button class="btn btn-warning mt-auto w-100" onclick="event.stopPropagation(); addToCart('${product.name}', ${product.price}, '${product.image}')">
                        🛒 Add to Cart
                    </button>
                </div>
            </div>
        `;
        row.appendChild(productCard);
    
    


        row.appendChild(productCard);

        // Every 3 products, create a new row
        if ((index + 1) % 3 === 0 && index !== products.length - 1) {
            row = document.createElement("div");
            row.classList.add("row", "g-4");
            container.appendChild(row);
        }
    });
}


// ✅ Function to toggle favorite products (fixes heart button issue)
function toggleFavorite(name, price, image, description, button) {
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];

    // Check if product is already in favorites
    const existingIndex = favorites.findIndex(fav => fav.name === name);

    if (existingIndex === -1) {
        // Add product to favorites
        favorites.push({ name, price, image, description });
        button.innerHTML = '❤️'; // Change to red heart
    } else {
        // Remove from favorites
        favorites.splice(existingIndex, 1);
        button.innerHTML = '🤍'; // Change back to white heart
    }

    localStorage.setItem("favorites", JSON.stringify(favorites));

    // Reload favorites page if user is viewing it
    if (window.location.hash === "#favorites") {
        loadFavorites();
    }
}

// ✅ Function to load favorites on the Favorites page
function loadFavorites() {
    const container = document.getElementById("favorites-product-container");
    if (!container) return;

    container.innerHTML = ""; // Clear existing content
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];

    if (favorites.length === 0) {
        container.innerHTML = "<p class='text-center text-muted'>No favorites yet.</p>";
        return;
    }

    let row = document.createElement("div");
    row.classList.add("row", "g-4");
    container.appendChild(row);

    favorites.forEach(product => {
        const productCard = `
            <div class="col-lg-4 col-md-6 col-sm-12 d-flex align-items-stretch">
                <div class="card shadow-sm w-100 border-0 p-3">
                    <div class="position-relative">
                        <img src="${product.image}" class="card-img-top rounded" alt="${product.name}">
                        <button class="btn btn-danger position-absolute top-0 end-0 m-2 border rounded-circle" onclick="removeFavorite('${product.name}')">
                            ❌
                        </button>
                    </div>
                    <div class="card-body d-flex flex-column text-center">
                        <h5 class="card-title fw-bold">${product.name}</h5>
                        <p class="text-muted">$${product.price}</p>
                        <p class="card-text">${product.description}</p>
                        <button class="btn btn-warning mt-auto w-100" onclick="addToCart('${product.name}', ${product.price}, '${product.image}')">
                            🛒 Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        `;
        row.innerHTML += productCard;
    });
}

// ✅ Function to remove from favorites from the Favorites page
function removeFavorite(name) {
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
    favorites = favorites.filter(fav => fav.name !== name);

    localStorage.setItem("favorites", JSON.stringify(favorites));
    loadFavorites(); // Refresh the favorites page
}

// ✅ Function to add a product to the cart
function addToCart(name, price, image) {
    alert(`${name} added to cart! 🛒`);
}

// ✅ Ensure products load on page load
document.addEventListener("DOMContentLoaded", function () {
    // Ensure Home Container Exists Before Loading
    if (document.getElementById("shop-product-container")) {
        loadProducts("shop-product-container");
    }

    // Ensure Shop Container Exists Before Loading
    if (document.getElementById("shop-product-container")) {
        loadProducts("shop-product-container");
    }

    // Ensure Favorites Container Exists Before Loading
    if (document.getElementById("favorites-product-container")) {
        loadFavorites();
    }
});


// ✅ Ensure correct section loads when switching pages
function showSection(sectionId) {
    document.querySelectorAll('.page-section').forEach(section => {
        section.style.display = 'none';
    });

    const section = document.getElementById(sectionId);
    if (!section) {
        console.error(`❌ Section '${sectionId}' not found.`);
        return;
    }

    section.style.display = 'block';

    if (sectionId === "shop") {
        loadProducts("shop-product-container");
    } else if (sectionId === "home") {
        loadProducts("home-product-container");
    } else if (sectionId === "favorites") {
        loadFavorites();
    }

    localStorage.setItem('currentSection', sectionId);
}

function applyFilter(filter) {
    selectedFilter = filter;
    filterProducts(); // Call filter function when a button is clicked

    // Remove "active" class from all filter buttons
    document.querySelectorAll(".filter-btn").forEach(btn => btn.classList.remove("active"));

    // Add "active" class to the clicked button
    document.querySelector(`[data-filter="${filter}"]`).classList.add("active");
}

function filterProducts() {
    console.log("Filtering products..."); // Debugging log

    const selectedFilter = document.querySelector(".filter-btn.active")?.dataset.filter;
    const products = document.querySelectorAll(".card"); // Adjust selector if needed

    products.forEach(product => {
        const productCategory = product.getAttribute("data-category"); // Adjust based on your HTML
        if (selectedFilter === "all" || productCategory === selectedFilter) {
            product.style.display = "block";
        } else {
            product.style.display = "none";
        }
    });
}

function openProductDetails(name, price, image, description) {
    const product = { name, price, image, description };
    localStorage.setItem("selectedProduct", JSON.stringify(product)); // Store product data
    window.location.hash = "#product-details"; // Redirect to details page
}

function openProductDetails(id) {
    localStorage.setItem("selectedProductId", id); // ✅ Store only the ID
    window.location.hash = "#product-details"; // ✅ Redirect to product details page
}





