<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error = "Email already registered";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $password);
        
        if ($stmt->execute()) {
            $_SESSION['signup_success'] = true;
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Blossom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include '../includes/navbar.php'; ?>
    
    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center">Sign Up</h2>
            <?php if (isset($error)): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" required minlength="2"
                           class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required
                           class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Phone</label>
                    <input type="tel" name="phone" required
                           pattern="[0-9]{11}"
                           placeholder="01XXXXXXXXX"
                           class="w-full p-2 border rounded">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full p-2 border rounded">
                    <p class="text-sm text-gray-500 mt-1">Minimum 8 characters</p>
                </div>
                <button type="submit" 
                        class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700">
                    Sign Up
                </button>
            </form>
            <p class="mt-4 text-center">
                Already have an account? <a href="login.php" class="text-pink-600 hover:underline">Login</a>
            </p>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
