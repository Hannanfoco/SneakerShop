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
  
      $.ajax({
        url: "http://localhost/SneakerShop/backend/users",
        type: "GET",
        headers: {
          "Authentication": token,
          "Authorization": "Bearer " + token
        },
        success: function (response) {
          const users = Array.isArray(response)
            ? response
            : response.users || response.user || [];
          console.log("👥 Users loaded:", users);
          AdminDashboard.renderUserTable(users);
        },
        error: function (xhr) {
          console.error(" Failed to load users:", xhr.responseText);
          $("#userTableBody").html(
            `<tr><td colspan="5" class="text-danger text-center">Unauthorized or error loading users.</td></tr>`
          );
        }
      });
    },
  
    renderUserTable: function (users) {
      const columns = [
        { data: "id", title: "ID" },
        { data: "username", title: "Name" },
        { data: "email", title: "Email" },
        { data: "role", title: "Role" },
        {
          data: null,
          title: "Actions",
          render: function (data, type, row) {
            return `<button class="btn btn-sm btn-danger delete-user-btn" data-id="${row.id}">Delete</button>`;
          }
        }
      ];
      Utils.datatable("userTable", columns, users || []);
    }
  };
  $(document).ready(function () {
    function onAdminPageLoad() {
      AdminDashboard.loadUsers();  
      loadProducts();             
    }
  
    // On initial load
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
        const tbody = $("#productTableBody"); //  specific to product table
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
      
      
      
  
    //  Add user on  save
    $(document).on("submit", "#addUserForm", function (event) {
      event.preventDefault();
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
    