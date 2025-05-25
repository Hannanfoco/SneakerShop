async function loadFavorites() {
    console.log("🔄 loadFavorites() called");

    const container = document.getElementById("favorites-container");
    if (!container) {
        console.error(" favorites-container not found");
        return;
    }

    container.innerHTML = "";
    const token = localStorage.getItem("user_token");
    if (!token) return;

    try {
        const response = await $.ajax({
            url: "http://localhost/SneakerShop/backend/favourites",
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token,
                "Authentication": token
            }
        });

        const favorites = Array.isArray(response.data) ? response.data : [];
        if (favorites.length === 0) {
            container.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No favorites yet.</td></tr>`;
            return;
        }

        favorites.forEach((product) => {
            const imageSrc = product.image_url
                ? `http://localhost/SneakerShop/${product.image_url}`
                : "http://localhost/SneakerShop/images/no-image.png";

            const row = document.createElement("tr");
            row.innerHTML = `
                <tr>
                    <td><img src="${imageSrc}" class="img-fluid rounded" style="width: 80px; height: 80px;"></td>
                    <td class="align-middle fw-bold">${product.name}</td>
                    <td class="align-middle text-muted">$${product.price}</td>
                    <td class="align-middle">${product.description || ""}</td>
                    <td class="align-middle">
                        <button class="btn btn-light border-0 text-danger fs-9" onclick="removeFromFavorites(${product.favourite_id})">❌</button>
                    </td>
                </tr>
            `;
            container.appendChild(row);
        });

    } catch (err) {
        console.error(" Failed to load favorites:", err.responseText || err);
        toastr.error("Could not load favorites.");
    }
}

async function toggleFavorite(e, productId, name, price, imageUrl, description, buttonEl) {
    e.stopPropagation();
    const token = localStorage.getItem("user_token");
    if (!token) {
        toastr.warning("Please log in to manage favorites.");
        return;
    }

    const isLiked = buttonEl.textContent.includes("❤️");

    try {
        if (isLiked) {
            // Remove favorite — this will call /favourites GET to match the correct ID
            const favs = await $.ajax({
                url: "http://localhost/SneakerShop/backend/favourites",
                method: "GET",
                headers: {
                    "Authorization": "Bearer " + token,
                    "Authentication": token
                }
            });

            const target = favs.data?.find(f => f.product_id === productId);
            if (!target) throw new Error("Favorite not found");

            await $.ajax({
                url: `http://localhost/SneakerShop/backend/favourites?id=${target.favourite_id}`,
                method: "DELETE",
                headers: {
                    "Authorization": "Bearer " + token,
                    "Authentication": token
                }
            });

            buttonEl.innerHTML = '🤍';
            toastr.info(`${name} removed from favorites`);

        } else {
            // Add favorite
            await $.ajax({
                url: "http://localhost/SneakerShop/backend/favourites",
                method: "POST",
                contentType: "application/json",
                data: JSON.stringify({ product_id: productId }),
                headers: {
                    "Authorization": "Bearer " + token,
                    "Authentication": token
                }
            });

            buttonEl.innerHTML = '❤️';
            toastr.success(`${name} added to favorites`);
        }

    } catch (err) {
        console.error(" toggleFavorite error:", err.responseText || err);
        toastr.error("Failed to update favorites.");
    }
}

//  Remove favorite by favorite ID
async function removeFromFavorites(favouriteId) {
    const token = localStorage.getItem("user_token");
    if (!token) return;

    try {
        await $.ajax({
            url: `http://localhost/SneakerShop/backend/favourites?id=${favouriteId}`,
            method: "DELETE",
            headers: {
                "Authorization": "Bearer " + token,
                "Authentication": token
            }
        });
        toastr.success("Favorite removed.");
        loadFavorites();
    } catch (err) {
        console.error(" removeFromFavorites error:", err.responseText || err);
        toastr.error("Could not remove favorite.");
    }
}

//  Trigger load on hash route
$(document).on("click", "a[href='#favorites']", function () {
    setTimeout(loadFavorites, 300);
});
