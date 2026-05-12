<?php 
// 1. استدعاء ملف الاتصال
include 'db.php';

// 2. جلب الإحصائيات الحقيقية
$count_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$count_categories = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM categories"))['total'];
$count_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];

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
                <p>Welcome back, Admin!</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon-box"><i class="fas fa-box"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $count_products; ?></h3>
                        <p>Products</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-box"><i class="fas fa-th-large"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $count_categories; ?></h3>
                        <p>Categories</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-box"><i class="fas fa-clipboard-list"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $count_orders; ?></h3>
                        <p>Orders</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon-box"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-info">
                        <h3>$<?php echo number_format($total_sales, 2); ?></h3>
                        <p>Total Sales</p>
                    </div>
                </div>
            </div>

            <section class="recent-orders-section">
                <h3><?php echo ($view == 'all') ? 'All Orders History' : 'Recent Orders'; ?></h3>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($orders_query) > 0) {
                            while($order = mysqli_fetch_assoc($orders_query)) {
                        ?>
                        <tr>
                            <td>#<?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name'] ?? 'Guest'); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($order['order_date'])); ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><span class="status paid">Paid</span></td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center;'>No orders found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                
                <div class="table-footer">
                    <?php if ($view == 'all'): ?>
                        <a href="dashbord.php?view=limited" class="view-toggle-btn">
                            <i class="fas fa-chevron-up"></i> Show Less
                        </a>
                    <?php else: ?>
                        <a href="dashbord.php?view=all" class="view-toggle-btn">
                            View All Orders <i class="fas fa-chevron-down"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

</body>
</html>