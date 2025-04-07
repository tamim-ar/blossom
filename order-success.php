<?php
session_start();
require_once 'config/database.php';

if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['order_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success - Blossom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-12 flex-grow">
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-green-600 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Order Placed Successfully!</h2>
            <p class="text-gray-600 mb-4">Your order #<?php echo $order_id; ?> has been placed successfully.</p>
            <p class="text-gray-600 mb-6">We'll send you an email with the order details shortly.</p>
            <a href="shop.php" 
               class="inline-block bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700">
                Continue Shopping
            </a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
