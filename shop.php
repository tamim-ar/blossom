<?php
session_start();
require_once 'config/database.php';
$query = "SELECT * FROM flowers";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Blossom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/js/shop.js" defer></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-12 flex-grow">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Our Flowers</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while($flower = $result->fetch_assoc()): ?>
            <div class="bg-white rounded-lg shadow-md p-4">
                <img src="assets/images/<?php echo $flower['image']; ?>" 
                     alt="<?php echo $flower['name']; ?>"
                     class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="text-xl font-semibold"><?php echo $flower['name']; ?></h3>
                <p class="text-gray-600 mb-2"><?php echo $flower['description']; ?></p>
                <p class="text-pink-600 font-bold mb-4">৳<?php echo number_format($flower['price'], 0); ?></p>
                <button onclick="addToCart(<?php echo $flower['id']; ?>)"
                        class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700">
                    Add to Cart
                </button>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
