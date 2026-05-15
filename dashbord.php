<?php 
session_start();

// إذا ضغط المستخدم على خروج
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// حماية الصفحة من الدخول غير المصرح به
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// 2. جلب إحصائيات المنتجات فقط
$count_products = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM products")
)['total'];

// مجموع المخزون
$total_stock = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(stock) as total FROM products")
)['total'] ?? 0;

// أعلى سعر منتج
$highest_price = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT MAX(price) as max_price FROM products")
)['max_price'] ?? 0;

// عدد المنتجات المتوفرة
$available_products = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE stock > 0")
)['total'];

$sales_result = mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders");
$sales_data = mysqli_fetch_assoc($sales_result);
$total_sales = $sales_data['total'] ?? 0;

// 3. الخاصية الذكية: التحكم في عرض الجدول (الكل أو آخر 4) في نفس الصفحة
$view = isset($_GET['view']) ? $_GET['view'] : 'limited';

if ($view == 'all') {
    // جلب كل الطلبات بدون تحديد عدد
    $orders_query = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_date DESC");
} else {
    // جلب آخر 4 طلبات فقط
    $orders_query = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_date DESC LIMIT 4");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NADARA | Admin Dashboard</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* التعديلات التي طلبتِها لتكبير العنوان والمسافات */
        .header-section {
            margin-bottom: 50px; /* مسافة كبيرة تحت الداشبورد */
            padding-top: 10px;
        }
        .header-section h2 {
            font-size: 2.8rem; /* تكبير كلمة Dashboard */
            font-weight: 700;
            margin-bottom: 10px;
        }
        .header-section p {
            font-size: 1.2rem;
            color: #888;
        }
        .table-footer {
            margin-top: 25px;
            text-align: center;
        }
        .view-toggle-btn {
            text-decoration: none;
            color: #E8B4B8; /* لون موقعك الوردي */
            font-weight: bold;
            font-size: 1.1rem;
            transition: 0.3s;
        }
        .view-toggle-btn:hover {
            opacity: 0.7;
        }
    </style>
</head>
<body>

    <div class="dashboard-wrapper">
       <?php include 'slide.php'; ?>
        <main class="main-content">
            
            <div class="header-section">
                <h2>Dashboard</h2>
                <p>Welcome back, <?php echo $_SESSION['admin_name']; ?></p>
            </div>

        <!-- Statistics -->
        <div class="stats-grid">

            <!-- Products -->
            <div class="stat-card">
                <div class="icon-box">
                    <i class="fas fa-box"></i>
                </div>

                <div class="stat-info">
                    <h3><?php echo $count_products; ?></h3>
                    <p>Total Products</p>
                </div>
            </div>

            <!-- Total Stock -->
            <div class="stat-card">
                <div class="icon-box">
                    <i class="fas fa-layer-group"></i>
                </div>

                <div class="stat-info">
                    <h3><?php echo $total_stock; ?></h3>
                    <p>Total Stock</p>
                </div>
            </div>

            <!-- Available Products -->
            <div class="stat-card">
                <div class="icon-box">
                    <i class="fas fa-check-circle"></i>
                </div>

                <div class="stat-info">
                    <h3><?php echo $available_products; ?></h3>
                    <p>Available Products</p>
                </div>
            </div>

            <!-- Highest Price -->
            <div class="stat-card">
                <div class="icon-box">
                    <i class="fas fa-dollar-sign"></i>
                </div>

                <div class="stat-info">
                    <h3>$<?php echo number_format($highest_price, 2); ?></h3>
                    <p>Highest Price</p>
                </div>
            </div>

        </div>

        <!-- Products Table -->
        <section class="recent-orders-section products-preview">

            <h3>Latest Products</h3>

            <table class="orders-table">

                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $products_query = mysqli_query(
                        $conn,
                        "SELECT * FROM products ORDER BY product_id DESC LIMIT 5"
                    );

                    if(mysqli_num_rows($products_query) > 0){

                        while($product = mysqli_fetch_assoc($products_query)){
                    ?>

                    <tr>

                        

                        <td>
                            <?php echo htmlspecialchars($product['name']); ?>
                        </td>

                       

                        <td>
                            $<?php echo number_format($product['price'], 2); ?>
                        </td>

                        <td>
                            <?php echo $product['stock']; ?>
                        </td>

                    </tr>

                    <?php
                        }

                    } else {

                        echo "
                        <tr>
                            <td colspan='5' style='text-align:center; padding:20px;'>
                                No products found
                            </td>
                        </tr>
                        ";
                    }
                    ?>

                </tbody>

            </table>

        </section>

    </main>

</div>

</body>
</html>