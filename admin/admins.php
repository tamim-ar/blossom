<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Handle admin deletion
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    // Prevent deleting yourself
    if ($id != $_SESSION['admin_id']) {
        $conn->query("DELETE FROM admin WHERE id = $id");
    }
}

// Handle admin creation
if (isset($_POST['add_admin'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $conn->prepare("INSERT INTO admin (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();
}

// Get all admins
$result = $conn->query("SELECT * FROM admin ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins - Blossom Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include 'includes/admin-nav.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Manage Admins</h2>
            <button onclick="document.getElementById('addAdminModal').classList.remove('hidden')"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Add New Admin
            </button>
        </div>
        
        <div class="bg-white rounded-lg shadow-md">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Username</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Created Date</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($admin = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($admin['username']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($admin['email']); ?></td>
                        <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($admin['created_at'])); ?></td>
                        <td class="px-6 py-4">
                            <?php if($admin['id'] != $_SESSION['admin_id']): ?>
                            <form method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                <button type="submit" name="delete" 
                                        class="text-red-600 hover:underline"
                                        onclick="return confirm('Are you sure you want to remove this admin?')">
                                    Remove
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="text-gray-400">Current User</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <div id="addAdminModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold mb-4">Add New Admin</h3>
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" required
                               class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required
                               class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required
                               class="w-full p-2 border rounded">
                    </div>
                    <div class="flex justify-between">
                        <button type="button"
                                onclick="document.getElementById('addAdminModal').classList.add('hidden')"
                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit" name="add_admin"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Add Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
