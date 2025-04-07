<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $order_id");
}

// Get all orders
$result = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Blossom Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include 'includes/admin-nav.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Manage Orders</h2>
        
        <div class="bg-white rounded-lg shadow-md">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Order ID</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Amount</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($order = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4">#<?php echo $order['id']; ?></td>
                        <td class="px-6 py-4"><?php echo $order['customer_name']; ?></td>
                        <td class="px-6 py-4">৳<?php echo number_format($order['total_amount'], 0); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm
                                <?php echo $order['status'] == 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($order['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-blue-100 text-blue-800'); ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" class="inline">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" onchange="this.form.submit()" 
                                        class="border rounded px-2 py-1">
                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>
                                        Pending
                                    </option>
                                    <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>
                                        Processing
                                    </option>
                                    <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>
                                        Completed
                                    </option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                            <a href="view-order.php?id=<?php echo $order['id']; ?>" 
                               class="text-blue-600 hover:underline ml-2">View Details</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
