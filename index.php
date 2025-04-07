<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blossom - Online Flower Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Hero Section -->
    <div class="container mx-auto px-4 py-12">
        <div class="text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Welcome to Blossom</h2>
            <p class="text-gray-600 mb-8">Find the perfect flowers for every occasion</p>
            <a href="shop.php" class="bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700">
                Shop Now
            </a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
