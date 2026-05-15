<?php
session_start(); // تشغيل الجلسة لاسترجاع بيانات الأدمن

// منع الدخول المباشر: إذا لم يسجل الأدمن دخوله، يتم طرده لصفحة login.php
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// معالجة تسجيل الخروج: مسح الجلسة عند الضغط على الزر
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

include 'db.php';


$edit_mode = false;
$product_id = ""; $name = ""; $price = ""; $stock = ""; $description = ""; $category = ""; $current_image = "default.jpg";


if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $product_id = $_GET['edit_id'];
    $res = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $product_id");
    if ($row = mysqli_fetch_assoc($res)) {
        $name = $row['name'];
        $price = $row['price'];
        $stock = $row['stock']; 
        $description = $row['description'];
        $category = $row['category'];
        $current_image = $row['image'];
    }
}

// 2. معالجة بيانات النموذج عند الإرسال (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_id = $_POST['p_id'];
    $p_name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $cat_id = $_POST['category'];
    $p_price = $_POST['price'];
    $p_stock = $_POST['stock'];
    $p_desc = mysqli_real_escape_string($conn, $_POST['description']);
    
    // خاصية رفع الصور الذكية
    $image_name = $_POST['old_image']; 
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $target_dir = "images/";
        $image_name = time() . "_" . basename($_FILES["product_image"]["name"]);
        move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_dir . $image_name);
    }

    if (!empty($p_id)) {
        // تحديث (Update)
        $query = "UPDATE products SET name='$p_name', category='$cat_id', price='$p_price', stock='$p_stock', description='$p_desc', image='$image_name' WHERE product_id=$p_id";
        $msg = "Product updated successfully!";
    } else {
        // إضافة جديد (Insert)
        $query = "INSERT INTO products (name, category, price, stock, description, image) 
                  VALUES ('$p_name', '$cat_id', '$p_price', '$p_stock', '$p_desc', '$image_name')";
        $msg = "Product added successfully!";
    }
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('$msg'); window.location.href='manage_products.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NADARA | <?php echo $edit_mode ? 'Edit' : 'Add'; ?> Product</title>
    <link rel="stylesheet" href="style1.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        #preview { width: 100%; max-height: 150px; object-fit: contain; display: <?php echo $edit_mode ? 'block' : 'none'; ?>; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="dashboard-wrapper">
        <?php include 'slide.php'; ?>
        <main class="main-content">
            <div class="content-container">
                <header class="page-header">
                    <h2><?php echo $edit_mode ? 'Edit Product' : 'Add New Product'; ?></h2>
                    <p><?php echo $_SESSION['admin_name']; ?><p>
                </header>

                <div class="form-card">
                    <form action="add_product.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="p_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="old_image" value="<?php echo $current_image; ?>">

                        <div class="image-upload-area" style="text-align: center;">
                            <label class="upload-label" style="cursor: pointer; display: block;">
                                <img id="preview" src="images/<?php echo $current_image; ?>">
                                <div id="placeholder" style="<?php echo $edit_mode ? 'display:none' : ''; ?>">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Upload Product Image</span>
                                </div>
                                <input type="file" name="product_image" id="img_input" style="display: none;" accept="image/*">
                            </label>
                        </div>

                        <div class="form-grid">
                            <div class="input-group full-width">
                                <label>Product Name</label>
                                <input type="text" name="p_name" value="<?php echo $name; ?>" placeholder="Enter product name" required>
                            </div>
                            <div class="input-group">
                                <label>Category</label>
                                 <input 
                                    type="text" 
                                    name="category" 
                                    value="<?php echo $category; ?>" 
                                    placeholder="Enter category" 
                                    required
                                >
                            </div>
            
                            <div class="input-group">
                                <label>Price ($)</label>
                                <input type="number" step="0.01" name="price" value="<?php echo $price; ?>" placeholder="0.00" required>
                            </div>
                            <div class="input-group">
                                <label>Stock Quantity</label>
                                <input type="number" name="stock" value="<?php echo $stock; ?>" placeholder="Enter quantity" required>
                            </div>
                            <div class="input-group full-width">
                                <label>Description</label>
                                <textarea name="description" rows="4" placeholder="Enter product description..."><?php echo $description; ?></textarea>
                            </div>
                        </div>

                        <div class="form-buttons">
                            <a href="manage_products.php" class="btn-cancel" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">Cancel</a>
                            <button type="submit" class="btn-save"><?php echo $edit_mode ? 'Update Product' : 'Save Product'; ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        const imgInput = document.getElementById('img_input');
        const preview = document.getElementById('preview');
        const holder = document.getElementById('placeholder');

        imgInput.onchange = e => {
            const [file] = imgInput.files;
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                holder.style.display = 'none';
            }
        }
    </script>
</body>
</html>