<aside class="sidebar">
    <div class="sidebar-brand">
        <h1>NADARA</h1>
        <p>Admin Panel</p>
    </div>
    <nav class="sidebar-menu">
        <ul>
            <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
            
            <li class="<?= ($current_page == 'dashbord.php') ? 'active' : ''; ?>">
                <a href="dashbord.php"><i class="fas fa-columns"></i> Dashboard</a>
            </li>
            
            <li class="<?= ($current_page == 'add_product.php') ? 'active' : ''; ?>">
                <a href="add_product.php"><i class="fas fa-plus-square"></i> Add Product</a>
            </li>
            
            <li class="<?= ($current_page == 'manage_products.php') ? 'active' : ''; ?>">
                <a href="manage_products.php"><i class="fas fa-tasks"></i> Manage Products</a>
            </li>
        </ul>
    </nav>
    <div class="sidebar-footer">
        <a href="login.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</aside>