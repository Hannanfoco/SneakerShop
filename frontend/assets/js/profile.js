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
  

  const orderHistoryBody = document.getElementById("order-history-body");
  if (!orderHistoryBody) {
    console.warn("⚠️ Order history table not found.");
    return;
  }

  orderHistoryBody.innerHTML = "";

  const orders = [
    { id: "#1001", date: "2025-03-10", items: "Nike Air Max 90, Adidas UltraBoost", total: "$250.00", status: "Shipped" },
    { id: "#1002", date: "2025-03-05", items: "Puma RS-X", total: "$120.00", status: "Delivered" },
    { id: "#1003", date: "2025-02-28", items: "Jordan 1 Retro", total: "$180.00", status: "Processing" }
  ];

  orders.forEach(order => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${order.id}</td>
      <td>${order.date}</td>
      <td>${order.items}</td>
      <td>${order.total}</td>
      <td><span class="badge bg-${getStatusColor(order.status)}">${order.status}</span></td>
    `;
    orderHistoryBody.appendChild(row);
  });

  console.log(" Orders rendered");
}
