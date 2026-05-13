<?php
include 'config.php';
session_start();

// ================== BUY FUNCTION ==================
if(isset($_POST['buy'])){

    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){

        $can_buy = true;
        $total_amount = 0;

        // ================== CHECK STOCK ==================
        foreach($_SESSION['cart'] as $item){

            $id = $item['id'];
            $qty = $item['qty'];

            // جلب بيانات المنتج من الداتابيس
            $product_query = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $id");
            $product = mysqli_fetch_assoc($product_query);

            // التأكد أن المنتج موجود وأن الكمية متوفرة
            if(!$product || $qty > $product['stock']){
                $can_buy = false;
                echo "<script>alert('Not enough stock for this product!'); window.location='checkout.php';</script>";
                exit();
            }

            // حساب السعر الكلي للطلب
            $total_amount += $product['price'] * $qty;
        }

        // ================== INSERT ORDER ==================
        if($can_buy){

            // حفظ الطلب في جدول orders
            $customer_name = "Guest Customer";

            mysqli_query($conn, "INSERT INTO orders (customer_name, total_amount) 
                                 VALUES ('$customer_name', $total_amount)");

            // أخذ رقم الطلب الجديد
            $order_id = mysqli_insert_id($conn);

            // ================== INSERT ORDER ITEMS + UPDATE STOCK ==================
            foreach($_SESSION['cart'] as $item){

                $id = $item['id'];
                $qty = $item['qty'];

                // جلب بيانات المنتج مرة ثانية لحفظ السعر
                $product_query = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $id");
                $product = mysqli_fetch_assoc($product_query);

                $unit_price = $product['price'];
                $item_total = $unit_price * $qty;

                // حفظ تفاصيل المنتجات المطلوبة في order_items
                mysqli_query($conn, "INSERT INTO order_items 
                                    (order_id, product_id, quantity, unit_price, item_total)
                                    VALUES
                                    ($order_id, $id, $qty, $unit_price, $item_total)");

                // تحديث الكمية من الداتابيس
                mysqli_query($conn, "UPDATE products 
                                     SET stock = stock - $qty 
                                     WHERE product_id = $id");
            }

            // ================== COOKIE LAST PURCHASE ==================
            // كوكي آخر عملية شراء
            setcookie("last_purchase", "Order completed", time()+3600, "/");

            // ================== EMPTY CART ==================
            // تفريغ السلة
            unset($_SESSION['cart']);

            echo "<script>alert('Order placed successfully!'); window.location='index.php';</script>";
            exit();
        }

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

// جلب بيانات المنتج من الداتابيس
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
        <input type="number" name="qty" value="<?php echo $qty; ?>" min="1" max="<?php echo $row['stock']; ?>" class="qty" required>
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
