<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $phone, $id);
    
    if ($stmt->execute()) {
        $success = "User updated successfully!";
    } else {
        $error = "Failed to update user.";
    }
}

// Get user details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header("Location: users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User - Blossom Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include 'includes/admin-nav.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Edit User</h2>
            
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                               required class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                               required class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2">Phone</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"
                               pattern="[0-9]{11}" placeholder="01XXXXXXXXX"
                               class="w-full p-2 border rounded">
                    </div>
                    <div class="flex justify-between">
                        <a href="users.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Back
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
