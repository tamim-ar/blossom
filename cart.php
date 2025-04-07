<?php
session_start();
require_once 'config/database.php';

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Blossom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-12 flex-grow">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Shopping Cart</h2>
        
        <?php if (empty($cart_items)): ?>
            <p class="text-gray-600">Your cart is empty.</p>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <?php foreach ($cart_items as $item): ?>
                <div class="flex items-center justify-between border-b py-4">
                    <div class="flex items-center">
                        <img src="assets/images/<?php echo $item['image']; ?>" 
                             alt="<?php echo $item['name']; ?>"
                             class="w-20 h-20 object-cover rounded">
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold"><?php echo $item['name']; ?></h3>
                            <div class="flex items-center space-x-2 mt-2">
                                <button onclick="updateQuantity(<?php echo $item['id']; ?>, 'decrease')" 
                                        class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300">-</button>
                                <span id="qty-<?php echo $item['id']; ?>"><?php echo $item['quantity']; ?></span>
                                <button onclick="updateQuantity(<?php echo $item['id']; ?>, 'increase')" 
                                        class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300">+</button>
                            </div>
                            <p class="text-gray-600 mt-1">৳<?php echo number_format($item['price'], 0); ?> each</p>
                        </div>
                    </div>
                    <button onclick="removeFromCart(<?php echo $item['id']; ?>)"
                            class="text-red-600 hover:text-red-800">Remove</button>
                </div>
                <?php endforeach; ?>
                
                <div class="mt-6">
                    <div class="text-xl font-bold">Total: ৳<?php echo number_format($total, 0); ?></div>
                    <a href="checkout.php" 
                       class="mt-4 inline-block bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    function updateQuantity(flowerId, action) {
        fetch('api/cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                flower_id: flowerId,
                action: action
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>
</body>
</html>
