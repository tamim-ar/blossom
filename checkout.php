<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header("Location: auth/login.php");
    exit();
}

$cart_items = [];
$total = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $flower_id => $quantity) {
        $query = "SELECT * FROM flowers WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $flower_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $flower = $result->fetch_assoc();
        
        if ($flower) {
            $flower['quantity'] = $quantity;
            $flower['subtotal'] = $flower['price'] * $quantity;
            $cart_items[] = $flower;
            $total += $flower['subtotal'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($cart_items)) {
    $customer_name = $_SESSION['user_name'];
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);

    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, address, phone, total_amount) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $customer_name, $address, $phone, $total);
        $stmt->execute();
        
        $order_id = $conn->insert_id;
        
        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, flower_id, quantity, price_at_time) VALUES (?, ?, ?, ?)");
        
        foreach ($cart_items as $item) {
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }
        
        // Commit transaction
        $conn->commit();
        
        // Clear cart
        unset($_SESSION['cart']);
        
        // Redirect to success page
        header("Location: order-success.php?order_id=" . $order_id);
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Order placement failed. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Blossom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-12 flex-grow">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Checkout</h2>
        
        <?php if (empty($cart_items)): ?>
            <p class="text-gray-600">Your cart is empty.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4">Order Details</h3>
                    <?php foreach ($cart_items as $item): ?>
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h4 class="font-semibold"><?php echo $item['name']; ?></h4>
                            <p class="text-gray-600">৳<?php echo number_format($item['price'], 0); ?> x <?php echo $item['quantity']; ?></p>
                        </div>
                        <p class="font-semibold">৳<?php echo number_format($item['subtotal'], 0); ?></p>
                    </div>
                    <?php endforeach; ?>
                    <div class="border-t pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <p class="text-xl font-bold">Total:</p>
                            <p class="text-xl font-bold">৳<?php echo number_format($total, 0); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4">Shipping Information</h3>
                    <?php if (isset($error)): ?>
                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Delivery Address</label>
                            <textarea name="address" required
                                      class="w-full p-2 border rounded" rows="3"></textarea>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" required
                                   pattern="[0-9]{11}"
                                   placeholder="01XXXXXXXXX"
                                   class="w-full p-2 border rounded">
                        </div>
                        <button type="submit" 
                                class="w-full bg-pink-600 text-white py-3 rounded-lg hover:bg-pink-700">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
