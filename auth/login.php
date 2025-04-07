<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    // First try user login
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        
        // Redirect to saved URL if exists
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: ../" . $redirect);
            exit();
        }
        
        header("Location: ../index.php");
        exit();
    }
    
    // Then try admin login
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($admin = $result->fetch_assoc()) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_email'] = $admin['email'];
        header("Location: ../admin/dashboard.php");
        exit();
    }
    
    $error = "Invalid email or password";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Blossom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <?php include '../includes/navbar.php'; ?>
    
    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
            <?php if (isset($error)): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required 
                           class="w-full p-2 border rounded">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full p-2 border rounded">
                </div>
                <button type="submit" 
                        class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700">
                    Login
                </button>
            </form>
            <p class="mt-4 text-center">
                Don't have an account? <a href="signup.php" class="text-pink-600 hover:underline">Sign Up</a>
            </p>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
