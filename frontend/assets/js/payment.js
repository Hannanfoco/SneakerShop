$(document).on("click", "a[href='#payment']", function () {
    setTimeout(() => {
      const form = document.getElementById("payment-form");
      if (!form) {
        console.warn(" #payment-form not found yet.");
        return;
      }
  
      console.log(" Binding payment form logic...");
  
      // Set total from localStorage
      const total = localStorage.getItem("cartTotal") || "0.00";
      $("#payment-total").text(`$${total}`);
  
      // Bind submit event
      $("#payment-form").off("submit").on("submit", async function (e) {
        e.preventDefault();
        console.log("💳 Submit triggered");
  
        const token = localStorage.getItem("user_token");
        const userRaw = localStorage.getItem("user");
  
        if (!token || !userRaw) {
          toastr.warning("You must be logged in to complete payment.");
          return;
        }
  
        const user = JSON.parse(userRaw);
  
        const paymentPayload = {
          user_id: user.id,
          amount: parseFloat(total),
          payment_status: "paid"
        };
  
        try {
          //  POST payment to backend
          const response = await $.ajax({
            url: "http://localhost/SneakerShop/backend/payment",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(paymentPayload),
            headers: {
              "Authorization": "Bearer " + token,
              "Authentication": token
            }
          });
  
          console.log(" Payment created:", response);
  
          //  DELETE cart items
          await $.ajax({
            url: `http://localhost/SneakerShop/backend/cart?user_id=${user.id}`,
            method: "DELETE",
            headers: {
              "Authorization": "Bearer " + token,
              "Authentication": token
            }
          });
  
          //  Clear local cart
          localStorage.removeItem("cart");
          localStorage.removeItem("cartTotal");
  
          toastr.success("Payment successful! 🎉");
          setTimeout(() => {
            window.location.hash = "#home";
          }, 1000);
  
        } catch (err) {
          console.error("Payment failed:", err.responseText || err);
          toastr.error("Failed to complete payment.");
        }
      });
    }, 400); 
  });
  