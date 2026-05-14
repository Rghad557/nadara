<?php
session_start();
include 'db.php'; 

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['admin_email']);
    $password = $_POST['admin_pass'];


    $query = "SELECT * FROM admins WHERE email = '$email' AND password = '$password' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        
        if (isset($_POST['remember_me'])) {
            setcookie("admin_login", $email, time() + (86400 * 30), "/"); 
        } else {
            if (isset($_COOKIE["admin_login"])) {
                setcookie("admin_login", "", time() - 3600, "/");
            }
        }

        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        
        header("Location: dashbord.php");
        exit();
    } else {
        $error_message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NADARA | Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style1.css">
    <style>
        
        .login-card {
            max-width: 350px !important; 
            padding: 30px 25px !important; /
            margin: 20px !important; 
            border-radius: 35px !important;
        }

       
        .login-card h1 { font-size: 2rem !important; }
        .login-card h2 { font-size: 1.1rem !important; }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        .back-nav {
            text-align: left;
            margin-bottom: 15px;
        }
        .back-link {
            text-decoration: none;
            color: #888;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: 0.3s;
        }
        .back-link:hover {
            color: #E8B4B8;
            transform: translateX(-3px);
        }
    </style>
</head>
<body>

    <div class="login-body">
        <div class="login-card">
            <div class="back-nav">
                <a href="index.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>

            <header>
                <h1>NADARA</h1>
                <h2>Admin Login</h2>
                <p style="font-size: 0.85rem; color: #888;">Welcome back, Admin!</p>
            </header>
            
            <?php if ($error_message): ?>
                <div class="error-msg"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="input-field">
                    <label style="font-size: 0.85rem;">Email</label>
                    <input type="email" name="admin_email" placeholder="adminlogin@nadara.com" 
                           value="<?php echo $_COOKIE['admin_login'] ?? ''; ?>" required>
                </div>

                <div class="input-field">
                    <label style="font-size: 0.85rem;">Password</label>
                    <input type="password" name="admin_pass" placeholder="••••••••" required>
                </div>

                <div class="options" style="font-size: 0.8rem;">
                    <label>
                        <input type="checkbox" name="remember_me" <?php if(isset($_COOKIE["admin_login"])) echo "checked"; ?>> Remember me
                    </label>
                    <a href="javascript:void(0)" onclick="alert('System Admin: Please check the database credentials or contact support.');" style="color: #E8B4B8; text-decoration: none;">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-login" style="padding: 12px; border-radius: 12px;">Login</button>
            </form>

            <footer style="margin-top: 25px; font-size: 0.75rem; color: #ccc;">
                <p>© 2026 NADARA. All rights reserved.</p>
            </footer>
        </div>
    </div>

</body>
</html>