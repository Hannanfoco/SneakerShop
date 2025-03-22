$(document).on("spapp.load", function(event, page) {
    console.log("📌 `spapp.load` event triggered! Detected page:", page);

    if (page.includes("profile")) {
        console.log("✅ Inside `profile` page condition, script running...");

        // Wait until the DOM is updated
        requestAnimationFrame(() => {
            setTimeout(() => {
                const orderHistoryBody = document.getElementById("order-history-body");
                console.log("🔍 orderHistoryBody found?", orderHistoryBody);

                if (!orderHistoryBody) {
                    console.error("❌ Order history table body not found! Retrying...");
                    return;
                }

                orderHistoryBody.innerHTML = ""; // ✅ Prevent duplicate rows
                console.log("✅ Order history table found. Adding orders...");

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

                console.log("✅ Orders successfully added!");
            }, 200);  // ✅ Delay slightly to allow SPAPP to render profile.html
        });
    }
});

function getStatusColor(status) {
    if (status === "Shipped") return "info";
    if (status === "Delivered") return "success";
    if (status === "Processing") return "warning";
    return "secondary";
}
