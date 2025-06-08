console.log("profile.js loaded");

function parseJwt(token) {
  if (!token) return null;
  try {
    const base64Payload = token.split('.')[1];
    const payload = atob(base64Payload);
    return JSON.parse(payload);
  } catch (e) {
    console.error("Invalid token format:", e);
    return null;
  }
}

function getStatusColor(status) {
  if (status === "Shipped") return "info";
  if (status === "Delivered") return "success";
  if (status === "Processing") return "warning";
  return "secondary";
}

function runProfilePageLogic() {
  console.log("Running profile page logic...");

  const token = localStorage.getItem("user_token");
  console.log(" Token in localStorage:", token);

  if (!token) {
    console.warn(" No token found.");
    return;
  }

  const user = parseJwt(token);
  console.log(" Parsed user from token:", user);

  if (user) {
    $("#profile-name").text(user.username || "User");
    $("#profile-email").text(user.email || "Unknown");
  } else {
    $("#profile-name").text("Guest");
    $("#profile-email").text("Unknown");
  }

}




