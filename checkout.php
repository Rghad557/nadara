<?php
include 'config.php';
session_start();

// ================== BUY FUNCTION ==================
if(isset($_POST['buy'])){

    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){

        // 1. Check stock before buying
        foreach($_SESSION['cart'] as $item){

            $id = $item['id'];
            $qty = $item['qty'];

            $product_query = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $id");
            $product = mysqli_fetch_assoc($product_query);

            if(!$product || $qty > $product['stock']){
                echo "<script>alert('Not enough stock for this product!'); window.location='checkout.php';</script>";
                exit();
            }
        }

        // 2. Update product stock only
        foreach($_SESSION['cart'] as $item){

            $id = $item['id'];
            $qty = $item['qty'];

            mysqli_query($conn, "UPDATE products 
                                 SET stock = stock - $qty 
                                 WHERE product_id = $id");
        }

        // 3. Save last purchase cookie
        setcookie("last_purchase", "Purchase completed", time()+3600, "/");

        // 4. Empty cart
        unset($_SESSION['cart']);

        echo "<script>alert('Purchase completed successfully!'); window.location='index.php';</script>";
        exit();

    } else {
        echo "<script>alert('Your cart is empty!'); window.location='checkout.php';</script>";
        exit();
    }
}

// ================== DELETE SINGLE ITEM ==================
if(isset($_GET['delete'])) {
    $index = $_GET['delete'];

    if(isset($_SESSION['cart'][$index])){
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    header("Location: checkout.php");
    exit();
}

// ================== CLEAR ALL CART ==================
if(isset($_POST['clear'])) {
    unset($_SESSION['cart']);
    header("Location: checkout.php");
    exit();
}

// ================== UPDATE QUANTITY ==================
if(isset($_POST['update'])) {
    $index = $_POST['index'];
    $qty = $_POST['qty'];

    if(isset($_SESSION['cart'][$index]) && $qty > 0){
        $_SESSION['cart'][$index]['qty'] = $qty;
    }

    header("Location: checkout.php");
    exit();
}

// ================== CHECK CART STATUS ==================
$cart_empty = !isset($_SESSION['cart']) || empty($_SESSION['cart']);

// ================== TOTAL ==================
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- HEADER -->
<div class="navbar">
    <div class="logo">
        Nadara
    </div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="contact.php">Contact Us 📍</a>
        <?php if(basename($_SERVER['PHP_SELF']) != "checkout.php"): ?>
            <a href="checkout.php">Shopping Cart 🛒</a>
        <?php endif; ?>
    </div>
</div>

<!-- PAGE TITLE -->
<h2 class="page-title">Shopping Cart</h2>

<!-- TOP ACTION -->
<div class="top-actions">
    <a href="index.php" class="btn secondary">← Continue Shopping</a>
</div>

<!-- CART SECTION -->
<div class="cart">

<?php if(!$cart_empty): ?>

<?php foreach($_SESSION['cart'] as $index => $item):

$id = $item['id'];
$qty = $item['qty'];

$sql = "SELECT * FROM products WHERE product_id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(!$row){
    continue;
}

$item_total = $row['price'] * $qty;
$total += $item_total;

?>

<div class="cart-item">

    <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">

    <div class="item-info">
        <h3><?php echo $row['name']; ?></h3>
        <p>Price: <?php echo $row['price']; ?> SAR</p>
        <p>Quantity: <?php echo $qty; ?></p>
        <p>Total: <?php echo $item_total; ?> SAR</p>
    </div>

    <!-- UPDATE QUANTITY FORM -->
    <form method="post" class="cart-actions">
        <input type="hidden" name="index" value="<?php echo $index; ?>">
        <input 
            type="number" 
            name="qty" 
            value="<?php echo $qty; ?>" 
            min="1" 
            max="<?php echo $row['stock']; ?>" 
            class="qty" 
            required
        >
        <button type="submit" name="update" class="btn">Update</button>
    </form>

    <!-- DELETE ITEM -->
    <a href="checkout.php?delete=<?php echo $index; ?>" class="btn danger">Remove</a>

</div>

<?php endforeach; ?>

<?php else: ?>

    <p class="empty">Your cart is empty 🛒</p>

<?php endif; ?>

</div>

<!-- SUMMARY -->
<div class="summary">

    <h3>Total Price: <?php echo $total; ?> SAR</h3>

    <div class="summary-actions">

        <!-- CLEAR CART -->
        <form method="post">
            <button type="submit" name="clear" class="btn danger-all" <?php echo $cart_empty ? 'disabled' : ''; ?>>
                Clear Cart
            </button>
        </form>

        <!-- BUY NOW -->
        <form method="post">
            <button type="submit" name="buy" class="btn primary-btn" <?php echo $cart_empty ? 'disabled' : ''; ?>>
                Buy Now
            </button>
        </form>

        <a href="index.php" class="btn secondary">Continue Shopping</a>

    </div>

</div>

</body>
</html>
