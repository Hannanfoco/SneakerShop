console.log(" JS file loaded!");



  
var UserService = {
    init: function () {
       const token = localStorage.getItem("user_token");
const user = token ? Utils.parseJwt(token) : null;

if (user && user.role && (window.location.hash === "#login" || window.location.hash === "#signup")) {
  window.location.hash = user.role === "admin" ? "#admin" : "#home";
}

      
      
  
      $("#login-form").validate({
        submitHandler: function (form) {
          console.log("Login form is valid, submitting via AJAX...");
          var entity = Object.fromEntries(new FormData(form).entries());
          UserService.login(entity);
        },
      });
  
      $("#register-form").validate({
        rules: {
          confirmPassword: {
            equalTo: "#password",
          },
        },
        submitHandler: function (form) {
          const formData = new FormData(form);
          formData.delete("confirmPassword");
          const entity = Object.fromEntries(formData.entries());
          UserService.register(entity);
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
        success: function (result) {
          console.log(result);
          toastr.success("Registration successful. Please login.");
          window.location.replace("index.html");
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          toastr.error(
            XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : "Error"
          );
        },
      });
    },
  
    login: function (entity) {
        $.ajax({
          url: Constants.PROJECT_BASE_URL + "auth/login",
          type: "POST",
          data: JSON.stringify(entity),
          contentType: "application/json",
          dataType: "json",
          success: function (result) {
            console.log(" Login successful:", result);
        
            // Store JWT token
            localStorage.setItem("user_token", result.data.token);
        
            const user = Utils.parseJwt(result.data.token);
            console.log("👤 Parsed user from token:", user);
        
            //  Store user in localStorage
            if (user) {
                localStorage.setItem("user", JSON.stringify(user));
            } else {
                console.warn("⚠️ User payload not found in token.");
            }
        
            // Generate navigation/menu based on role
            UserService.generateMenuItems();
        
            if (typeof toastr !== "undefined") {
                toastr.success("Login successful! Redirecting to home...");
            } else {
                alert("Login successful! Redirecting to home...");
            }
        
            setTimeout(() => {
                window.location.hash = "#home";
            }, 1000);
        },
        
        
          error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.error(" Login error:", XMLHttpRequest?.responseText || errorThrown);
            toastr.error(
              XMLHttpRequest?.responseText
                ? XMLHttpRequest.responseText
                : "Login failed. Please try again."
            );
          }
        });
      },
      
      
  
      logout: function () {
        console.log("🔓 Logging out...");
      
        localStorage.removeItem("user_token");
      
        toastr.success("You have successfully logged out.");
      
        window.location.hash = "#home";
      
        UserService.generateMenuItems();
      },
      
      
    generateMenuItems: function () {
        console.log("generateMenuItems() called");
      
        const token = localStorage.getItem("user_token");
        console.log(" Token from localStorage:", token);
      
        if (!token) {
          console.log(" No token found. Showing Login button.");
          $("#auth-action").html(
            '<a class="nav-link btn btn-primary text-white px-3" href="#login">Login</a>'
          );
          return;
        }
      
        const user = Utils.parseJwt(token); 

        console.log("👤 Parsed user from token:", user);
      
        if (user && user.role) {
          if (user.role === Constants.USER_ROLE) {
            console.log(" Customer detected. Showing Logout button.");
            $("#auth-action").html(
              '<a id="logout-btn" class="nav-link btn btn-danger text-white px-3" href="#">Logout</a>'
            );
          } else if (user.role === Constants.ADMIN_ROLE) {
            console.log(" Admin detected. Showing Dashboard button.");
            $("#auth-action").html(
              '<a id="dashboard-btn" class="nav-link btn btn-warning text-dark px-3" href="#admin">Dashboard</a>'
            );
          }
      
          $("#logout-btn").on("click", function (e) {
            e.preventDefault();
            console.log("🔓 Logout clicked.");
            UserService.logout();
          });
        } else {
          console.log(" Token exists but no valid user role. Showing Login.");
          $("#auth-action").html(
            '<a class="nav-link btn btn-primary text-white px-3" href="#login">Login</a>'
          );
        }
      }
    };      

    $(document).on("spapp:beforeLoad", function (e) {
        const token = localStorage.getItem("user_token");
        const user = token ? Utils.parseJwt(token) : null;
      
        if (user && user.role && (e.detail.route === "login" || e.detail.route === "signup")) {
          console.log("🔒 Already logged in. Preventing access to:", e.detail.route);
          e.preventDefault(); 
          window.location.hash = user.role === "admin" ? "#admin" : "#home";
          toastr.info("You're already logged in.");
        }
      });
      