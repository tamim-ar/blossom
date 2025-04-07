<nav class="bg-pink-600 text-white p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-8">
            <a href="/blossom-flower-shop/shop.php" class="text-2xl font-bold hover:text-pink-200">Blossom</a>
            <div class="space-x-4">
                <a href="/blossom-flower-shop/cart.php" class="hover:text-pink-200">Cart</a>
            </div>
        </div>
        <div class="space-x-4">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="text-pink-200">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="/blossom-flower-shop/profile.php" class="hover:text-pink-200">Profile</a>
                <?php if(isset($_SESSION['admin_id'])): ?>
                    <a href="/blossom-flower-shop/admin/dashboard.php" class="hover:text-pink-200">Admin Panel</a>
                <?php endif; ?>
                <a href="/blossom-flower-shop/auth/logout.php" class="bg-pink-700 px-4 py-2 rounded hover:bg-pink-800">Logout</a>
            <?php else: ?>
                <a href="/blossom-flower-shop/auth/login.php" class="hover:text-pink-200">Login</a>
                <a href="/blossom-flower-shop/auth/signup.php" class="bg-pink-700 px-4 py-2 rounded hover:bg-pink-800">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
