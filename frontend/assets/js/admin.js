const AdminDashboard = {
  loadUsers: function () {
    const token = localStorage.getItem("user_token");

    if (!token || token.split('.').length !== 3) {
      console.warn("⚠️ No valid JWT token found. Cannot load users.");
      $("#userTableBody").html(
        `<tr><td colspan="5" class="text-warning text-center">Login as admin to view users.</td></tr>`
      );
      return;
    }

    console.log("User token:", token);


    $.ajax({
      url: "http://localhost/SneakerShop/backend/users",
      type: "GET",
      dataType: "json", 
      headers: {
        "Authentication": token,
        "Authorization": "Bearer " + token
      },
      success: function (response) {
        console.log(" Response:", response);
        AdminDashboard.renderUserTable(response);
      },
      error: function (xhr, status, error) {
        console.error(" AJAX error loading users:", status, error);
        $("#userTableBody").html(
          `<tr><td colspan="5" class="text-danger text-center">Unauthorized or error loading users (Status ${xhr.status}).</td></tr>`
        );
      }
    });
  },
    
  

  renderUserTable: function (users) {
    const tableBody = $("#userTableBody");
    tableBody.empty();

    if (!users || users.length === 0) {
      tableBody.append(`<tr><td colspan="5" class="text-center">No users found.</td></tr>`);
      return;
    }

    users.forEach(user => {
      tableBody.append(`
        <tr>
          <td>${user.id}</td>
          <td>${user.username}</td>
          <td>${user.email}</td>
          <td>${user.role}</td>
          <td>
            <button class="btn btn-sm btn-danger delete-user-btn" data-id="${user.id}">Delete</button>
          </td>
        </tr>
      `);
    });
  }
};
$(document).ready(function () {
  function onAdminPageLoad() {
    AdminDashboard.loadUsers();  
    loadProducts();             
    loadStats();
  }

  if (window.location.hash === "#admin") {
    onAdminPageLoad();
  }

  $(window).on("hashchange", function () {
    if (window.location.hash === "#admin") {
      onAdminPageLoad();
    }
  });

  function loadProducts() {
      console.log(" loadProducts() CALLED");
    
      const token = localStorage.getItem("user_token");
    
      if (!token) {
        console.warn("⚠️ No token found. Cannot fetch products.");
        return;
      }
    
      $.ajax({
        url: "http://localhost/SneakerShop/backend/products",
        type: "GET",
        headers: {
          "Authentication": token,
          "Authorization": "Bearer " + token
        },
        success: function (response) {
          console.log(" Products fetched:", response);
          renderProductTable(response.data || response);
        },
        error: function (xhr) {
          console.error(" Failed to fetch products:", xhr.responseText);
          toastr.error("Failed to load products.");
        }
      });
    
    
    
    
    }
    function renderProductTable(products) {
      const tbody = $("#productTableBody"); 
      tbody.empty();
    
      if (!products || products.length === 0) {
        tbody.append(`<tr><td colspan="4" class="text-center">No products found.</td></tr>`);
        return;
      }
    
      products.forEach(product => {
        const row = `
          <tr>
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>$${product.price}</td>
            <td>${product.stock}</td>
          </tr>`;
        tbody.append(row);
      });
    }


    // Attach jQuery Validation rules to the form
    $(document).ready(function () {
      $("#addUserForm").validate({
        rules: {
          username: {
            required: true,
            minlength: 3
          },
          email: {
            required: true,
            email: true
          },
          password: {
            required: true,
            minlength: 3
          },
          role: {
            required: true
          }
        },
        messages: {
          username: {
            required: "Username is required.",
            minlength: "Username must be at least 3 characters long."
          },
          email: {
            required: "Email is required.",
            email: "Please enter a valid email address."
          },
          password: {
            required: "Password is required.",
            minlength: "Password must be at least 3 characters long."
          },
          role: {
            required: "Please select a role."
          }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (element) {
          $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
          $(element).removeClass("is-invalid");
        }
      });
    });
    

    
    
    

    $(document).on("submit", "#addUserForm", function (event) {
      event.preventDefault();

      if (!$('#addUserForm').valid()) return;


      const formData = new FormData(this);
      const user = Object.fromEntries(formData.entries());
      const token = localStorage.getItem("user_token");
  
      if (!token) {
        alert("Not authorized. Please login as admin.");
        return;
      }
  
      $.ajax({
        url: "http://localhost/SneakerShop/backend/users",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(user),
        headers: {
          "Authentication": token,
          "Authorization": "Bearer " + token
        },
        success: function (response) {
          toastr.success("User added successfully.");
          const modal = bootstrap.Modal.getInstance(document.getElementById("userModal"));
          modal.hide();
          $("#addUserForm")[0].reset();
          AdminDashboard.loadUsers();
        },
        error: function (xhr) {
          toastr.error("Failed to add user.");
        }
      });
    });
  


  // Delete user 
  $(document).on("click", ".delete-user-btn", function () {
    const userId = $(this).data("id");
    const token = localStorage.getItem("user_token");

    if (!confirm(`Are you sure you want to delete user ID ${userId}?`)) return;
    if (!token) return alert("Not authorized.");

    $.ajax({
      url: `http://localhost/SneakerShop/backend/users?id=${userId}`,
      type: "DELETE",
      headers: {
        "Authentication": token,
        "Authorization": "Bearer " + token
      },
      success: function () {
        toastr.success("User deleted.");
        AdminDashboard.loadUsers();
      },
      error: function (xhr) {
        toastr.error("Failed to delete user.");
        console.error(xhr.responseText);
      }
    });
  });
});

function loadStats() {
  const token = localStorage.getItem("user_token");

  if (!token) {
    console.warn("⚠️ No token found. Skipping stats.");
    return;
  }

  $.ajax({
    url: "http://localhost/SneakerShop/backend/users/stats",
    method: "GET",
    headers: {
      Authentication: token,
      Authorization: "Bearer " + token
    },
    success: function (res) {
      if (res.success && res.stats) {
        $("#total-users").text(res.stats.total_users || 0);
        $("#active-users").text(res.stats.active_users || 0);
        $("#new-signups").text(res.stats.new_signups || 0);
      } else {
        console.warn("⚠️ Unexpected stats response", res);
      }
    },
    error: function (xhr) {
      console.error(" Failed to load stats", xhr.responseText);
    }
  });
}



  