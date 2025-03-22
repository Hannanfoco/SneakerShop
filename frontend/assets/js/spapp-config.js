$(function() {
    var app = $.spapp({ defaultView: "#home", templateDir: "pages/" });

    app.route({
        view: "home",
        load: "home.html",
        onCreate: function() {
            console.log("✅ Home page loaded.");
            setTimeout(() => {
                loadProducts("home-product-container");
            }, 500);
        }
    });

    app.route({
        view: "shop",
        load: "shop.html",
        onCreate: function() {
            console.log("✅ Shop page loaded.");
            setTimeout(() => {
                loadProducts("shop-product-container");
            }, 500);
        }
    });

    app.route({
        view: "cart",
        load: "cart.html"
    });

    app.route({
        view: "favorites",
        load: "favorites.html"
    });

    app.route({
        view: "profile",
        load: "profile.html"
    });

    app.route({
        view: "login",
        load: "login.html"
    });

    // ✅ Correct placement for signup route
    app.route({
        view: "signup",
        load: "signup.html"
    });

    app.route({
        view: "#product-details",
        load: "product-details.html", // ✅ No extra `/pages/` needed
        onCreate: function() {
            console.log("🚀 Product Details page loaded via SPAPP!");
            $.getScript("assets/js/product-details.js")
              .done(() => console.log("✅ product-details.js loaded!"))
              .fail(() => console.error("❌ Failed to load product-details.js"));
        }
    });

    app.route({ view: "payment", load: "payment.html" });


    app.run();  // Run the app after all routes are added
});
