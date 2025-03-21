// ✅ Hardcoded favorite products (default favorites)
const defaultFavorites = [
    { name: "Converse Chuck", price: 75, image: "/frontend/images/p1.jpg" },
    { name: "New Balance 550", price: 130, image: "/frontend/images/p2.jpg" },
    { name: "Vans Old Skool", price: 85, image: "/frontend/images/p3.jpg" },
    { name: "Jordan Retro", price: 200, image: "/frontend/images/p4.jpg" },
    { name: "Puma RS-X", price: 110, image: "/frontend/images/p5.jpg" }
];


/**
 * ✅ Function to Load Favorites from LocalStorage and Display in Table
 */
function loadFavorites() {
    console.log("🔄 loadFavorites() function called...");

    const tableBody = document.getElementById("favorites-container");

    if (!tableBody) {
        console.error("❌ Error: favorites-container not found in the DOM.");
        return;
    }

    tableBody.innerHTML = ""; // Clear existing content

    let favorites = JSON.parse(localStorage.getItem("favorites"));

    // ✅ Force Overwriting Old Data
    if (!favorites || !Array.isArray(favorites) || favorites.length === 0 || favorites[0].image.includes("nb550.jpg")) {
        console.warn("⚠️ No favorites found or outdated data detected. Resetting to defaultFavorites...");
        favorites = defaultFavorites;
        localStorage.setItem("favorites", JSON.stringify(favorites)); // Save new data
    }

    favorites.forEach((product, index) => {
        if (!product || !product.name) {
            console.warn(`⚠️ Skipping invalid product at index ${index}:`, product);
            return;
        }

        let imageSrc = product.image.startsWith("/")
    ? product.image
    : `/frontend/images/${product.image}`;


        console.log(`🛍️ Adding product to table: ${product.name}`);

        let row = document.createElement("tr");

        row.innerHTML = `
            <td><img src="${imageSrc}" class="img-fluid rounded" style="width: 80px; height: 80px;"></td>
            <td class="align-middle fw-bold">${product.name}</td>
            <td class="align-middle text-muted">$${product.price}</td>
            <td class="align-middle">${product.description}</td>
            <td class="align-middle">
              <button class="btn btn-light border-0 text-danger fs-9" onclick="removeFromFavorites('${product.name}')">❌</button>
            </td>
        `;

        tableBody.appendChild(row);
    });

    console.log("✅ loadFavorites() execution completed.");
}



/**
 * ✅ Function to Remove a Single Favorite
 */
function removeFromFavorites(productName) {
    console.log(`🗑️ removeFromFavorites() called for: ${productName}`);

    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
    favorites = favorites.filter((item) => item.name !== productName); // Remove item

    localStorage.setItem("favorites", JSON.stringify(favorites));
    loadFavorites(); // Reload favorites after removal
    console.log(`✅ '${productName}' removed from favorites.`);
}

/**
 * ✅ Function to Clear All Favorites
 */
function clearFavorites() {
    console.log("🗑️ clearFavorites() function called. Removing all items...");
    localStorage.removeItem("favorites");
    loadFavorites();
    console.log("✅ All favorites cleared.");
}

/**
 * ✅ Ensure the Favorites Page Loads Properly
 */
document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ Page Loaded: Checking if we're on the Favorites page...");
    
    if (window.location.hash === "#favorites") {
        console.log("✅ Favorites page detected, calling loadFavorites()");
        setTimeout(loadFavorites, 500); // Small delay to ensure DOM is ready
    }
});
