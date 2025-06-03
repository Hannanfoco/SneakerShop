$(function () {
    window.app = $.spapp({ defaultView: "#home", templateDir: "pages/" });
  
    app.route({
      view: "home",
      load: "home.html",
      onCreate: function () {
        console.log("✅ Home page loaded.");
        setTimeout(() => {
          loadProducts("home-product-container");
        }, 500);
      }
    });
  
    app.route({
      view: "shop",
      load: "shop.html",
      onCreate: function () {
        console.log("✅ Shop page loaded.");
        setTimeout(() => {
            getAllProducts(products => loadProducts("shop-product-container", products));

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
      load: "profile.html",
      onReady: function () {
        console.log("✅ SPApp loaded #profile — calling profile logic manually");
        runProfilePageLogic();
      }
    });
  
    app.route({
      view: "login",
      load: "login.html"
    });
  
    app.route({
      view: "signup",
      load: "signup.html",
      onCreate: function () {
        console.log("🔥 signup onCreate triggered");
  
        setTimeout(() => {
          const $form = $("#register-form");
  
          if (!$form.length) {
            console.warn("❌ No #register-form found.");
            return;
          }
  
          console.log("✅ Found #register-form, setting manual submit handler");
  
          $form.off("submit").on("submit", function (e) {
            e.preventDefault();
            console.log("✅ FORM SUBMITTED");
  
            const formData = new FormData(this);
            formData.delete("confirmPassword");
  
            const entity = Object.fromEntries(formData.entries());
            console.log("📤 Register payload:", entity);
  
            $.ajax({
              url: "http://localhost/SneakerShop/backend/auth/register",
              type: "POST",
              data: JSON.stringify(entity),
              contentType: "application/json",
              dataType: "json",
              success: function (result) {
                console.log("✅ Register successful:", result);
  
                if (typeof toastr !== "undefined") {
                  toastr.success("Registration successful! Please log in.");
                } else {
                  alert("Registration successful! Please log in.");
                }
  
                setTimeout(() => {
                  window.location.hash = "#login";
                }, 1500);
              },
              error: function (err) {
                console.error("❌ Register failed:", err.responseText || err);
                alert("Register failed.");
              }
            });
          });
        }, 300);
      }
    });
  
    app.route({
      view: "#product-details",
      load: "product-details.html",
      onCreate: function () {
        console.log("🚀 Product Details page loaded via SPAPP!");
        $.getScript("assets/js/product-details.js")
          .done(() => console.log("✅ product-details.js loaded!"))
          .fail(() => console.error("❌ Failed to load product-details.js"));
      }
    });
  
    app.route({
      view: "payment",
      load: "payment.html"
    });
  
    app.run(); // Run the app after all routes are added
  });
  
  