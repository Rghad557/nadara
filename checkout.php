<?php
include 'config.php';
session_start();

// delete single item
if(isset($_GET['delete'])) {
    $index = $_GET['delete'];
    unset($_SESSION['cart'][$index]);
}

// clear all cart
if(isset($_POST['clear'])) {
    unset($_SESSION['cart']);
}

// update quantity
if(isset($_POST['update'])) {
    $index = $_POST['index'];
    $qty = $_POST['qty'];
    $_SESSION['cart'][$index]['qty'] = $qty;
}

// check cart status
$cart_empty = !isset($_SESSION['cart']) || empty($_SESSION['cart']);

// total
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

<!-- Page Title -->
<h2 class="page-title">Shopping Cart</h2>

<!-- Top Action -->
<div class="top-actions">
    <a href="index.php" class="btn secondary">← Continue Shopping</a>
</div>

<!-- Cart Section -->
<div class="cart">

<?php if(!$cart_empty): ?>

<?php foreach($_SESSION['cart'] as $index => $item):

$id = $item['id'];
$qty = $item['qty'];

$sql = "SELECT * FROM products WHERE product_id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$item_total = $row['price'] * $qty;
$total += $item_total;

?>

<div class="cart-item">

    <img src="images/<?php echo $row['image']; ?>">

    <div class="item-info">
        <h3><?php echo $row['name']; ?></h3>
        <p>Price: <?php echo $row['price']; ?> SAR</p>
        <p>Total: <?php echo $item_total; ?> SAR</p>
    </div>

    <form method="post" class="cart-actions">
        <input type="hidden" name="index" value="<?php echo $index; ?>">
        <input type="number" name="qty" value="<?php echo $qty; ?>" min="1" class="qty">
        <button name="update" class="btn">Update</button>
    </form>

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

        <form method="post">
            <button name="clear" class="btn danger-all" <?php echo $cart_empty ? 'disabled' : ''; ?>>
                Clear Cart
            </button>
        </form>

        <button class="btn primary-btn" onclick="buyNow(<?php echo $cart_empty ? 'true' : 'false'; ?>)">
            Buy Now
        </button>

        <a href="index.php" class="btn secondary">Continue Shopping</a>

    </div>

</div>

<script>
function buyNow(isEmpty){
    if(isEmpty){
        alert("Your cart is empty 🛒");
    } else {
        alert("Order placed successfully!");
    }
}
</script>

</body>
</html>