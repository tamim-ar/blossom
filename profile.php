<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    
    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $phone, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name;
        $success = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile.";
    }
}

// Get user details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get order history
$stmt = $conn->prepare("SELECT * FROM orders WHERE customer_name = ? ORDER BY order_date DESC");
$stmt->bind_param("s", $user['name']);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Blossom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-12 flex-grow">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Profile Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">Profile Information</h2>
                <?php if (isset($success)): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" 
                               required class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
                               readonly class="w-full p-2 border rounded bg-gray-50">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2">Phone</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"
                               pattern="[0-9]{11}" placeholder="01XXXXXXXXX"
                               class="w-full p-2 border rounded">
                    </div>
                    <button type="submit" 
                            class="bg-pink-600 text-white px-6 py-2 rounded hover:bg-pink-700">
                        Update Profile
                    </button>
                </form>
            </div>

            <!-- Order History -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">Order History</h2>
                <?php if ($orders->num_rows > 0): ?>
                    <div class="divide-y">
                        <?php while ($order = $orders->fetch_assoc()): ?>
                            <div class="py-4">
                                <div class="flex justify-between items-center mb-2">
                                    <div>
                                        <span class="font-semibold">Order #<?php echo $order['id']; ?></span>
                                        <span class="text-gray-500 ml-2">
                                            <?php echo date('M d, Y', strtotime($order['order_date'])); ?>
                                        </span>
                                    </div>
                                    <span class="px-2 py-1 rounded text-sm
                                        <?php echo $order['status'] == 'completed' ? 'bg-green-100 text-green-800' : 
                                                ($order['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                'bg-blue-100 text-blue-800'); ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </div>
                                <div class="text-gray-600">Total: ৳<?php echo number_format($order['total_amount'], 0); ?></div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-600">No orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
