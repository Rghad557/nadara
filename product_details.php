<?php
include 'config.php';
session_start();

// ================== GET PRODUCT ID ==================
$id = isset($_GET['id']) ? $_GET['id'] : 1;

// ================== GET PRODUCT DATA ==================
$sql = "SELECT * FROM products WHERE product_id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// ================== ADD TO CART ==================
if(isset($_POST['add'])) {

    $product_id = $_POST['id'];
    $qty = $_POST['qty'];

    // ================== VALIDATION ==================

    // التأكد أن الكمية أكبر من صفر
    if($qty <= 0){

        echo "<script>alert('Quantity must be greater than 0');</script>";

    } 
    // التأكد أن الكمية المطلوبة متوفرة بالمخزون
    elseif($qty > $row['stock']){

        echo "<script>alert('Sorry, not enough stock available!');</script>";

    } 
    else {

        // ================== CHECK IF PRODUCT EXISTS IN CART ==================
        $found = false;

        if(isset($_SESSION['cart'])){

            foreach($_SESSION['cart'] as &$item){

                // إذا المنتج موجود مسبقًا بالسلة
                if($item['id'] == $product_id){

                    $new_qty = $item['qty'] + $qty;

                    // التأكد أن الكمية الجديدة لا تتجاوز المخزون
                    if($new_qty > $row['stock']){

                        echo "<script>alert('Cannot add more than available stock!');</script>";

                    } else {

                        $item['qty'] = $new_qty;

                        echo "<script>alert('Cart updated successfully ✅');</script>";
                    }

                    $found = true;
                    break;
                }
            }
        }

        // ================== ADD NEW PRODUCT TO CART ==================
        if(!$found){

            $_SESSION['cart'][] = [
                "id" => $product_id,
                "qty" => $qty
            ];

            echo "<script>alert('Added to cart successfully ✅');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php echo $row['name']; ?></title>

<link rel="stylesheet" href="style.css">

<style>

:root{
    --primary:#EFD9DC;
    --primary-hover:#e6cdd1;
    --bg-color:#F9F5F3;
    --transition:0.3s ease;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background-color:var(--bg-color);
    font-family:Arial, sans-serif;
    color:black;
}

.page-wrapper{
    padding:30px 40px 50px;
}

.back-btn{
    display:inline-block;
    text-decoration:none;
    background-color:var(--primary);
    color:black;
    padding:14px 22px;
    border-radius:18px;
    font-size:16px;
    margin-bottom:35px;
    transition:var(--transition);
    font-weight:500;
}

.back-btn:hover{
    background-color:var(--primary-hover);
}

.product-container{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:60px;
    flex-wrap:wrap;
}

.product-img{
    width:450px;
    max-width:100%;
    border-radius:26px;
    object-fit:cover;
    box-shadow:0 10px 30px rgba(0,0,0,0.06);
}

.product-info{
    flex:1;
    min-width:300px;
    max-width:550px;
}

.product-info h1{
    font-size:58px;
    margin-bottom:20px;
    line-height:1.1;
}

.price{
    font-size:28px;
    margin-bottom:26px;
    font-weight:bold;
}

.description{
    font-size:19px;
    line-height:1.7;
    margin-bottom:28px;
}

.stock{
    margin-bottom:20px;
    font-size:18px;
    font-weight:600;
    color:#444;
}

.qty-row{
    display:flex;
    align-items:center;
    gap:16px;
    margin-bottom:28px;
    font-size:18px;
}

.qty{
    width:120px;
    padding:10px 12px;
    font-size:18px;
    border:1px solid #d8caca;
    border-radius:10px;
    outline:none;
    background:white;
}

.btn-group{
    display:flex;
    gap:16px;
    flex-wrap:wrap;
    margin-bottom:34px;
}

.btn{
    background-color:var(--primary);
    color:black;
    border:none;
    padding:0 28px;
    height:48px;
    border-radius:16px;
    font-size:18px;
    cursor:pointer;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    transition:var(--transition);
    font-weight:600;
}

.btn:hover{
    background-color:var(--primary-hover);
}

/* ================== HELP POPUP ================== */

.popup-overlay{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.45);
    justify-content:center;
    align-items:center;
    z-index:999;
}

.popup-box{
    background:white;
    width:420px;
    max-width:90%;
    padding:30px;
    border-radius:22px;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
    position:relative;
}

.popup-box h2{
    margin-bottom:15px;
}

.popup-box p{
    margin-bottom:12px;
    line-height:1.6;
}

.close-btn{
    position:absolute;
    top:12px;
    right:18px;
    font-size:28px;
    cursor:pointer;
    font-weight:bold;
}

/* ================== RESPONSIVE ================== */

@media (max-width:900px){

    .product-container{
        flex-direction:column;
        align-items:flex-start;
    }

    .product-info h1{
        font-size:42px;
    }

    .page-wrapper{
        padding:24px 20px 40px;
    }

    .product-img{
        width:100%;
    }
}

</style>
</head>

<body>

<!-- ================== NAVBAR ================== -->

<div class="navbar">

    <div class="logo">
        Nadara
    </div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="checkout.php">Shopping Cart 🛒</a>
        <a href="contact.php">Contact Us 📍</a>
    </div>

</div>

<div class="page-wrapper">

<!-- ================== BACK BUTTON ================== -->

<a href="index.php" class="back-btn">
    ← Back to products
</a>

<div class="product-container">

<!-- ================== PRODUCT IMAGE ================== -->

<img src="images/<?php echo $row['image']; ?>" 
     class="product-img" 
     alt="<?php echo $row['name']; ?>">

<div class="product-info">

<!-- ================== PRODUCT DETAILS ================== -->

<h1><?php echo $row['name']; ?></h1>

<h3 class="price">
    <?php echo $row['price']; ?> SAR
</h3>

<p class="description">
    <?php echo $row['description']; ?>
</p>

<p class="stock">
    Available Stock: <?php echo $row['stock']; ?>
</p>

<!-- ================== ADD TO CART FORM ================== -->

<form method="post">

<input type="hidden" 
       name="id" 
       value="<?php echo $row['product_id']; ?>">

<div class="qty-row">

    <label>Quantity:</label>

    <input type="number"
           name="qty"
           value="1"
           min="1"
           max="<?php echo $row['stock']; ?>"
           class="qty"
           required>

</div>

<div class="btn-group">

    <!-- ADD TO CART -->
    <button type="submit" name="add" class="btn">
        Add to Cart
    </button>

    <!-- CHECKOUT -->
    <button type="button" 
            class="btn" 
            onclick="location.href='checkout.php'">
        Checkout
    </button>

    <!-- HELP BUTTON -->
    <button type="button" 
            class="btn" 
            onclick="openHelp()">
        Help
    </button>

</div>

</form>

</div>
</div>
</div>

<!-- ================== HELP POPUP ================== -->

<div id="helpPopup" class="popup-overlay">

<div class="popup-box">

<span class="close-btn" onclick="closeHelp()">
    &times;
</span>

<h2>Need Help?</h2>

<p>
Need help with your skincare order, payment, or delivery?
Our support team is happy to assist you.
</p>

<p><strong>Phone:</strong> +966 55 123 4567</p>

<p><strong>Email:</strong> support@nadara.com</p>

<button class="btn" onclick="closeHelp()">
    Close
</button>

</div>
</div>

<!-- ================== JAVASCRIPT ================== -->

<script>

// OPEN HELP POPUP
function openHelp(){
    document.getElementById("helpPopup").style.display = "flex";
}

// CLOSE HELP POPUP
function closeHelp(){
    document.getElementById("helpPopup").style.display = "none";
}

</script>

</body>
</html>
