<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get orders count
$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status='pending'");
$pending_orders = $result->fetch_assoc()['count'];

// Get total products
$result = $conn->query("SELECT COUNT(*) as count FROM flowers");
$total_products = $result->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Blossom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include 'includes/admin-nav.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold mb-4">Pending Orders</h3>
                <p class="text-3xl text-pink-600"><?php echo $pending_orders; ?></p>
                <a href="orders.php" class="text-blue-600 hover:underline">View Orders →</a>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold mb-4">Total Products</h3>
                <p class="text-3xl text-pink-600"><?php echo $total_products; ?></p>
                <a href="products.php" class="text-blue-600 hover:underline">Manage Products →</a>
            </div>
        </div>
    </div>
</body>
</html>
