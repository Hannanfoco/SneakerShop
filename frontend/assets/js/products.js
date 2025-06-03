const products = [
    { id:1,name: "Nike Air Max", price: 120, image: "/SneakerShop/images/p1.jpg", description: "A classic sneaker with modern comfort, perfect for everyday wear." },
    { id:2,name: "Adidas UltraBoost", price: 150, image: "/SneakerShop/images/p2.jpg", description: "Boost technology for ultimate comfort and a sleek design." },
    { id:3,name: "Puma RS-X", price: 110, image: "/SneakerShop/images/p3.jpg", description: "Retro-inspired design with modern materials for style and comfort." },
    { id:4,name: "Jordan Retro", price: 200, image: "/SneakerShop/images/p4.jpg", description: "Legendary sneaker that defines streetwear culture." },
    { id:5,name: "Reebok Classic", price: 90, image: "/SneakerShop/images/p5.jpg", description: "Timeless design with a casual and versatile look." },
    { id:6,name: "Converse Chuck", price: 75, image: "/SneakerShop/images/p6.jpg", description: "Iconic high-top sneaker, loved for generations." },
    { id:7,name: "Yeezy Boost", price: 220, image: "/SneakerShop/images/p7.jpg", description: "Kanye West's signature sneaker, combining fashion and performance." },
    { id:8,name: "New Balance 550", price: 130, image: "/SneakerShop/images/p8.jpg", description: "Retro basketball sneaker with modern updates." },
    { id:9,name: "Vans Old Skool", price: 85, image: "/SneakerShop/images/p9.jpg", description: "Classic skate shoe with durable materials and timeless style." }
];

localStorage.setItem("allProducts", JSON.stringify(products));



document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("shop-product-container")) {
        loadProducts("shop-product-container");
    }
});
