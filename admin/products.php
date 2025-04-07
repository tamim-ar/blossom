<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Handle product deletion
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM flowers WHERE id = $id");
}

// Get all products
$result = $conn->query("SELECT * FROM flowers ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Blossom Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include 'includes/admin-nav.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Manage Products</h2>
            <a href="add-product.php" 
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Add New Product
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Image</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Price</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($product = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <img src="../assets/images/<?php echo $product['image']; ?>" 
                                 class="w-16 h-16 object-cover rounded">
                        </td>
                        <td class="px-6 py-4"><?php echo $product['name']; ?></td>
                        <td class="px-6 py-4">৳<?php echo number_format($product['price'], 0); ?></td>
                        <td class="px-6 py-4">
                            <a href="edit-product.php?id=<?php echo $product['id']; ?>" 
                               class="text-blue-600 hover:underline mr-3">Edit</a>
                            <form method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="delete" 
                                        class="text-red-600 hover:underline" 
                                        onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
