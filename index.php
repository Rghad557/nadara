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
        }

        /*  Improved invoice design */
        .invoice-box {
            width: 85%;
            max-width: 750px;
            margin: 30px auto;
            padding: 20px;
            background: var(--card);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }

        .invoice-box h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        .invoice-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .invoice-item img {
            width: 70px;
            border-radius: 12px;
        }

        .invoice-item p {
            margin: 2px 0;
        }

        .invoice-total {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">Nadara</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="checkout.php">Shopping Cart 🛒</a>
        <a href="contact.php">Contact Us 📍</a>
        <a href="login.php" class="admin-btn">Admin Login 👤</a>
    </div>
</div>

<!--  INVOICE (COOKIE) -->
<?php
if(isset($_COOKIE['last_purchase'])){

    $invoice = json_decode($_COOKIE['last_purchase'], true);

    echo "<div class='invoice-box'>";
    echo "<h3>🧾 Last Purchase</h3>";

    $total_all = 0;

    foreach($invoice as $item){

        $name = $item['name'];
        $price = $item['price'];
        $qty = $item['qty'];
        $image = $item['image'];

        $total = $price * $qty;
        $total_all += $total;

        echo "
        <div class='invoice-item'>
            <img src='images/$image'>
            <div>
                <p><b>$name</b></p>
                <p>Qty: $qty</p>
                <p>$total SAR</p>
            </div>
        </div>
        ";
    }

    echo "<hr>";
    echo "<p class='invoice-total'>Total: $total_all SAR</p>";
    echo "</div>";
}
?>

<!-- HERO -->
<section class="hero">
    <h1>Discover Your Beauty 🌿</h1>
    <p class="subtitle">Natural products for your glow</p>
</section>

<!-- CATEGORIES -->
<div class="categories">
    <button onclick="filterProducts('all')">All</button>
    <button onclick="filterProducts('Cleanser')">Cleanser</button>
    <button onclick="filterProducts('Serum')">Serum</button>
    <button onclick="filterProducts('Cream')">Cream</button>
    <button onclick="filterProducts('Sunscreen')">Sunscreen</button>
    <button onclick="filterProducts('Toner')">Toner</button>
    <button onclick="filterProducts('Mask')">Mask</button>
</div>

<!-- PRODUCTS -->
<div class="products">

<?php
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)){
?>

<div class="card" data-category="<?php echo $row['category']; ?>">
    <a href="product_details.php?id=<?php echo $row['product_id']; ?>">
        <img src="images/<?php echo $row['image']; ?>">
        <h3><?php echo $row['name']; ?></h3>
        <p><?php echo $row['price']; ?> SAR</p>
    </a>
</div>

<?php } ?>

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
