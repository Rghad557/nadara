<?php
include 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nadara</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-btn {
            border: 1px solid #E8B4B8;
            padding: 5px 15px;
            border-radius: 20px;
            color: #E8B4B8 !important;
            transition: 0.3s;
        }
        .admin-btn:hover {
            background-color: #E8B4B8;
            color: white !important;
        }

        .cookie-msg {
            text-align: center;
            color: green;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="navbar">

    <div class="logo">
        Nadara
    </div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="checkout.php">Shopping Cart 🛒</a>
        <a href="contact.php">Contact Us 📍</a>
        <a href="login.php" class="admin-btn">Admin Login 👤</a>
    </div>

</div>

<!--  عرض الكوكي -->
<?php
if(isset($_COOKIE['last_purchase'])){
    echo "<div class='cookie-msg'>Last purchase: ".$_COOKIE['last_purchase']."</div>";
}
?>

<section class="hero">
    <h1>Discover Your Beauty 🌿</h1>
    <p class="subtitle">Natural products for your glow</p>
</section>

<div class="categories">
    <button onclick="filterProducts('all')">All</button>
    <button onclick="filterProducts('1')">Cleanser</button>
    <button onclick="filterProducts('3')">Serum</button>
    <button onclick="filterProducts('4')">Cream</button>
    <button onclick="filterProducts('5')">Sunscreen</button>
    <button onclick="filterProducts('2')">Toner</button>
    <button onclick="filterProducts('6')">Mask</button>
</div>

<div class="products">

<?php
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

if ($result) {
    while($row = mysqli_fetch_assoc($result)) {
?>

    <div class="card" data-category="<?php echo $row['category_id']; ?>">
        <a href="product_details.php?id=<?php echo $row['product_id']; ?>">
            <img src="images/<?php echo $row['image']; ?>">
            <h3><?php echo $row['name']; ?></h3>
            <p><?php echo $row['price']; ?> SAR</p>
        </a>
    </div>

<?php
    }
}
?>

</div>

<script>
function filterProducts(category) {
    let cards = document.querySelectorAll(".card");

    cards.forEach(card => {
        if (category === 'all') {
            card.style.display = "block";
        } else {
            if (card.getAttribute("data-category") === category) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        }
    });
}
</script>

</body>
</html>
