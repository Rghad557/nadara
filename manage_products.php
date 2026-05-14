<?php 
include 'db.php';

// --- 1. معالجة عملية الحذف ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // حذف المنتج من جدول products فقط
    $delete_sql = "DELETE FROM products WHERE product_id = $id";

    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('Product deleted successfully'); window.location.href='manage_products.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting product'); window.location.href='manage_products.php';</script>";
        exit();
    }
}

// --- 2. معالجة البحث ---
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// --- 3. جلب المنتجات من جدول products فقط ---
// تم حذف JOIN لأن جدول categories انحذف
$sql = "SELECT * FROM products 
        WHERE name LIKE '%$search%' 
        ORDER BY product_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NADARA | Manage Products</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="dashboard-wrapper">
       <?php include 'slide.php'; ?>

        <main class="main-content">
            <div class="content-container">

                <header class="page-header">
                    <h2>Manage Products</h2>
                    <p>View, search, edit, and delete products.</p>
                </header>

                <div class="management-actions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    
                    <form method="GET" action="manage_products.php" class="search-box">
                        <i class="fas fa-search" style="color: #ccc;"></i>
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Search products..." 
                            value="<?php echo htmlspecialchars($search); ?>"
                        >
                        <button type="submit" style="display:none;"></button>
                    </form>

                    <a href="add_product.php" class="add-new-btn">
                        <i class="fas fa-plus"></i> Add Product
                    </a>

                </div>

                <div class="table-card">
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td class="product-info">
                                            <img 
                                                src="images/<?php echo $row['image']; ?>" 
                                                alt="Product" 
                                                style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover;"
                                            >

                                            <div class="details">
                                                <strong><?php echo $row['name']; ?></strong>
                                                <small><?php echo $row['size']; ?></small>
                                            </div>
                                        </td>

                                        <td><?php echo $row['category']; ?></td>
                                        <td>$<?php echo $row['price']; ?></td>
                                        <td><?php echo $row['stock']; ?></td>

                                        <td class="action-icons" style="text-align: center;">
                                            <a 
                                                href="add_product.php?edit_id=<?php echo $row['product_id']; ?>" 
                                                class="btn-edit"
                                            >
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            
                                            <a 
                                                href="manage_products.php?delete_id=<?php echo $row['product_id']; ?>" 
                                                class="btn-delete" 
                                                onclick="return confirm('Are you sure you want to delete this product?');"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="5" style="text-align:center; padding: 20px;">
                                        No products found.
                                    </td>
                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
 
</body>
</html>
