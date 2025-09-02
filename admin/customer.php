<?php
session_start();

include('../service/database.php');
$sql = "SELECT * FROM pelanggan";
$result = $db->query($sql);

$number = 1;

$db->close();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PATDUA</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="/load_Cust.js"></script>
    <link href="/logo.svg" rel="icon" type="image/x-icon">
</head>

<body>
    <!-- Navbar Start  -->
    <div class="h-24 flex flex-row">
        <nav class="w-full bg-green-600 text-white p-4 flex justify-between items-center">
            <a href="#home" class=" block py-4 px-10">
                <img src="/logo.svg" alt="logo" class="h-16 w-auto">
            </a>
            <div>
                <a href="" class="px-4 py-2 font-medium hover:bg-white hover:text-green-600 rounded"><?php echo $_SESSION["username"] ?></a>
                <a href="/logout.php" class="px-4 py-2 font-medium hover:bg-white hover:text-green-600 rounded">Logout</a>
            </div>

        </nav>

    </div>
    <!-- Navbar End -->


    <!-- Sidebar Content -->
    <div class="flex">
        <?php include '../func/sidebar.php' ?>
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-bold">Customer</h2>
            <!-- Form Pencarian -->
             <form id="formCari" class="mb-4 mt-4">
                <input type="text" id="inputCari" name="inputCari" placeholder="Cari Nama / No Telp / Email" class="border border-gray-300 rounded px-3 py-2 w-1/2">
                    <button type="submit" class="ml-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Cari</button>
             </form>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 px-4 py-2">No</th>
                            <th class="border border-gray-300 px-4 py-2">ID Customer</th>
                            <th class="border border-gray-300 px-4 py-2">Nama</th>
                            <th class="border border-gray-300 px-4 py-2">No.Telp</th>
                            <th class="border border-gray-300 px-4 py-2">Email</th>

                        </tr>
                    </thead>
                    <tbody id="customer-body">
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Sidebar Content End -->

</body>

</html>