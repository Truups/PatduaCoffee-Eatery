

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

<body>
    <div class="flex flex-1">
        <!-- Sidebar -->
        <div class="w-64 min-h-screen bg-green-600 text-white flex flex-col p-4">
            <h1 class="text-xl font-bold mb-4">Dashboard</h1>
            <nav>
                <a href="dashboard.php" class="block py-2 px-3 rounded font-medium hover:bg-white hover:text-green-600 duration-300 ease-in-out">Home</a>
                <a href="order.php" class="block py-2 px-3 rounded font-medium hover:bg-white hover:text-green-600 duration-300 ease-in-out">Order</a>
                <a href="menu.php" class="block py-2 px-3 rounded font-medium hover:bg-white hover:text-green-600 duration-300 ease-in-out">Menu</a>
                <a href="customer.php" class="block py-2 px-3 rounded font-medium hover:bg-white hover:text-green-600 duration-300 ease-in-out">Customer</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
                    <a href="report.php" class="block py-2 px-3 rounded font-medium hover:bg-white hover:text-green-600 duration-300 ease-in-out">Report</a>
                <?php endif; ?>
            </nav>
        </div>

</body>

</html>