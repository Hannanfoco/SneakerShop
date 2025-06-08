console.log(" JS file loaded!");

// === USER SERVICE ===
const UserService = {
  init: function () {
    const token = localStorage.getItem("user_token");
    const user = token ? Utils.parseJwt(token) : null;

    // Redirect logged-in users from login/signup pages
    if (user && user.role && (window.location.hash === "#login" || window.location.hash === "#signup")) {
      window.location.hash = user.role === "admin" ? "#admin" : "#home";
    }

    // Setup login form validation
    $("#login-form").validate({
      rules: {
        email: {
          required: true,
          email: true
        },
        password: {
          required: true,
          minlength: 3
        }
      },
      messages: {
        email: {
          required: "Email is required.",
          email: "Please enter a valid email address."
        },
        password: {
          required: "Password is required.",
          minlength: "Password must be at least 3 characters long."
        }
      },
      submitHandler: function (form) {
        const entity = Object.fromEntries(new FormData(form).entries());
        UserService.login(entity);
      },
    });
    

    $("#register-form").validate({
      rules: {
        fullName: {
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
        confirmPassword: {
          required: true,
          equalTo: "#signup-password" 
        }
      },
      messages: {
        fullName: {
          required: "Full name is required.",
          minlength: "Full name must be at least 3 characters."
        },
        email: {
          required: "Email is required.",
          email: "Please enter a valid email address."
        },
        password: {
          required: "Password is required.",
          minlength: "Password must be at least 3 characters long."
        },
        confirmPassword: {
          required: "Please confirm your password.",
          equalTo: "Passwords do not match."
        }
      },
      submitHandler: function (form) {
        const formData = new FormData(form);
        formData.delete("confirmPassword");
        const entity = Object.fromEntries(formData.entries());
        UserService.register(entity);
      },
    });
    
    

    // Initial navbar rendering
    this.updateNavbar();
  },

  login: function (entity) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/login",
      type: "POST",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function (result) {
        const token = result.data.token;
        localStorage.setItem("user_token", token);

        const user = Utils.parseJwt(token);
        if (user) {
          localStorage.setItem("user", JSON.stringify(user));
        }

        toastr.success("Login successful!");

        setTimeout(() => {
          window.location.hash = user.role === "admin" ? "#admin" : "#home";
          UserService.updateNavbar();
        }, 500);
      },
      error: function (xhr) {
        toastr.error(xhr?.responseText || "Login failed.");
      },
    });
  },

  register: function (entity) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/register",
      type: "POST",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function () {
        toastr.success("Registration successful. Please login.");
        window.location.replace("index.html");
      },
      error: function (xhr) {
        toastr.error(xhr?.responseText || "Registration error.");
      },
    });
  },

  logout: function () {
    console.log("🔓 Logging out...");
    localStorage.clear();
    toastr.success("You have been logged out.");
    this.updateNavbar();
    window.location.hash = "#home";
  },

  updateNavbar: function () {
    const navContainer = document.getElementById("dynamic-nav");
    if (!navContainer) return;
  
    const token = localStorage.getItem("user_token");
    const user = token ? Utils.parseJwt(token) : null;
  
    navContainer.innerHTML = ""; // Clear everything first
  
    if (!token || !user || !user.role) {
      // === Not Logged In ===
      navContainer.innerHTML = `
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link btn btn-outline-light mx-2" href="#login">Login</a></li>
        <li class="nav-item"><a class="nav-link btn btn-success text-white" href="#signup">Register</a></li>
      `;
    } else {
      // === Logged In ===
      navContainer.innerHTML = `
        <li class="nav-item"><a class="nav-link" href="#shop">Shop</a></li>
        <li class="nav-item"><a class="nav-link" href="#cart">Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="#favorites">Favorites</a></li>
        <li class="nav-item"><a class="nav-link" href="#profile">Profile</a></li>
      `;
  
      if (user.role === "admin") {
        navContainer.innerHTML += `
          <li class="nav-item"><a class="nav-link btn btn-warning text-dark me-2" href="#admin">Dashboard</a></li>
        `;
      }
  
      navContainer.innerHTML += `
        <li class="nav-item"><a id="logout-btn" class="nav-link btn btn-danger text-white px-3" href="#">Logout</a></li>
      `;
  
      const logoutBtn = document.getElementById("logout-btn");
      if (logoutBtn) {
        logoutBtn.addEventListener("click", (e) => {
          e.preventDefault();
          UserService.logout();
        });
      }
    }
  },
};

// === PROTECT LOGIN/SIGNUP WHEN LOGGED IN ===
$(document).on("spapp:beforeLoad", function (e) {
  const token = localStorage.getItem("user_token");
  const user = token ? Utils.parseJwt(token) : null;

  if (user && user.role && (e.detail.route === "login" || e.detail.route === "signup")) {
    console.log("🔒 Already logged in. Blocking:", e.detail.route);
    e.preventDefault();
    window.location.hash = user.role === "admin" ? "#admin" : "#home";
    toastr.info("You're already logged in.");
  }
});

// === NAVBAR INIT ON LOAD AND ROUTE CHANGE ===
document.addEventListener("DOMContentLoaded", () => {
  UserService.updateNavbar();
});

$(document).on("spapp:afterLoad", () => {
  UserService.updateNavbar();
});
