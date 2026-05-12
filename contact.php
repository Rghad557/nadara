<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .contact-container {
            padding: 40px;
        }

        .contact-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .contact-box p {
            margin: 10px 0;
            font-size: 16px;
        }

        iframe {
            margin-top: 20px;
            border-radius: 12px;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">Nadara</div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="checkout.php">Shopping Cart 🛒</a>
        <a href="contact.php">Contact Us 📍</a>
        <a href="login.php" class="admin-btn">Admin Login 👤</a>
    </div>
</div>

<!-- Title -->
<h2 style="padding:20px;">Contact Us</h2>

<!-- Content -->
<div class="contact-container">

    <div class="contact-box">

        <p><strong>Address:</strong> Dammam, Saudi Arabia</p>

        <p><strong>Phone:</strong> +966 55 123 4567</p>

        <p>
            <strong>Email:</strong> 
            <a href="mailto:support@nadara.com">support@nadara.com</a>
        </p>

        <!-- Google Map -->
        <iframe 
            width="100%" 
            height="250"
            style="border:0;"
            loading="lazy"
            src="https://maps.google.com/maps?q=dammam&output=embed">
        </iframe>

    </div>

</div>

</body>
</html>