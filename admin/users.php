<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Handle user deletion
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $conn->query("DELETE FROM users WHERE id = $id");
}

// Handle user addition
if (isset($_POST['add_user'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $password);
    $stmt->execute();
}

// Get all users
$result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Blossom Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include 'includes/admin-nav.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Manage Users</h2>
            <button onclick="document.getElementById('addUserModal').classList.remove('hidden')"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Add New User
            </button>
        </div>
        
        <div class="bg-white rounded-lg shadow-md">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Phone</th>
                        <th class="px-6 py-3 text-left">Joined Date</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['name']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        <td class="px-6 py-4">
                            <a href="edit-user.php?id=<?php echo $user['id']; ?>" 
                               class="text-blue-600 hover:underline mr-3">Edit</a>
                            <form method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="delete" 
                                        class="text-red-600 hover:underline"
                                        onclick="return confirm('Are you sure you want to delete this user?')">
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

    <!-- Add User Modal -->
    <div id="addUserModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold mb-4">Add New User</h3>
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" required
                               class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required
                               class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Phone</label>
                        <input type="tel" name="phone" pattern="[0-9]{11}"
                               placeholder="01XXXXXXXXX"
                               class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required
                               class="w-full p-2 border rounded">
                    </div>
                    <div class="flex justify-between">
                        <button type="button"
                                onclick="document.getElementById('addUserModal').classList.add('hidden')"
                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit" name="add_user"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Add User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
