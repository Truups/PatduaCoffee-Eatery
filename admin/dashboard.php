<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PATDUA</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="/logo.svg" rel="icon" type="image/x-icon">
</head>

<body>
    <!-- Navbar Start  -->
    <div class="h-24 flex flex-row">
        <nav class="w-full bg-green-600 text-white p-4 flex justify-between items-center">
            <a href="#home" class="block py-4 px-10">
                <img src="/logo.svg" alt="logo" class="h-16 w-auto">
            </a>
            <div>
                <a href="#" class="px-4 py-2 font-medium hover:bg-white hover:text-green-600 rounded">
                    <?php echo $_SESSION["username"]; ?>
                </a>
                <a href="/logout.php" class="px-4 py-2 font-medium hover:bg-white hover:text-green-600 rounded">Logout</a>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Sidebar and Main Content -->
    <div class="flex">
        <?php include '../func/sidebar.php' ?>

        <div class="flex-1 p-6">
            <h2 class="text-2xl font-bold mb-4">Dashboard</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card Total Pelanggan -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Total Pelanggan</h3>
                    <p class="text-3xl mt-2 text-green-600">
                        <?php
                        include('../service/database.php');
                        $pelanggan = mysqli_query($db, "SELECT COUNT(*) as total FROM pelanggan");
                        $data = mysqli_fetch_assoc($pelanggan);
                        echo $data['total'];
                        ?>
                    </p>
                </div>

                <!-- Card Total Menu -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Total Menu</h3>
                    <p class="text-3xl mt-2 text-green-600">
                        <?php
                        $menu = mysqli_query($db, "SELECT COUNT(*) as total FROM menu");
                        $data = mysqli_fetch_assoc($menu);
                        echo $data['total'];
                        ?>
                    </p>
                </div>

                <!-- Card Total Pesanan -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Total Pesanan</h3>
                    <p class="text-3xl mt-2 text-green-600">
                        <?php
                        $pesanan = mysqli_query($db, "SELECT COUNT(*) as total FROM pesan");
                        $data = mysqli_fetch_assoc($pesanan);
                        echo $data['total'];
                        ?>
                    </p>
                </div>

                <!-- Card Rata-rata Rating -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Rata-rata Rating Menu</h3>
                    <p class="text-3xl mt-2 text-yellow-500">
                        <?php
                        $rating = mysqli_query($db, "SELECT AVG(rating) as rata FROM rating");
                        $data = mysqli_fetch_assoc($rating);
                        echo number_format($data['rata'], 2);
                        ?>
                        ★
                    </p>
                </div>

                <!-- Card Menu Rating Tertinggi -->
                <!-- <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Menu Rating Tertinggi</h3>
                    <p class="mt-2 text-green-700 font-bold">
                        //<?php
                        // $query = mysqli_query($db, "
                        //          SELECT m.nama_menu, AVG(r.rating) as rata 
                        //          FROM rating r 
                        //          JOIN menu m ON r.id_Menu = m.id_Menu 
                        //          GROUP BY r.id_Menu 
                        //          ORDER BY rata DESC 
                        //          LIMIT 1
                        //         ");
                        // if ($row = mysqli_fetch_assoc($query)) 
                        // {
                        //     echo "{$row['nama_menu']} ({$row['rata']} ★)";
                        // } else {
                        //     echo "Belum ada rating";
                        // }
                        //?>
                    </p>
                </div> -->

                <!-- Card Menu Rating Terendah -->
                <!-- <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">Menu Rating Terendah</h3>
                    <p class="mt-2 text-red-700 font-bold">
                        //<?php
                        //$query = mysqli_query($db, "
                        //         SELECT m.nama_menu, AVG(r.rating) as rata 
                        //         FROM rating r 
                        //         JOIN menu m ON r.id_Menu = m.id_Menu 
                        //         GROUP BY r.id_Menu 
                        //         ORDER BY rata ASC 
                        //          LIMIT 1
                        //         ");
                        // if ($row = mysqli_fetch_assoc($query)) 
                        // {
                        //     echo "{$row['nama_menu']} ({$row['rata']} ★)";
                        // } else 
                        // {
                        //     echo "Belum ada rating";
                        // }
                        //?>
                    </p>
                </div> -->

            </div>

            <!-- Section Tambahan -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                <div class="bg-white p-4 rounded-lg shadow-md overflow-auto max-h-64">
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">Pelanggan</th>
                                <th class="px-4 py-2 border-b">Menu</th>
                                <th class="px-4 py-2 border-b">Rating</th>
                                <th class="px-4 py-2 border-b">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($db, "SELECT r.*, p.nama_pelanggan, m.nama_menu 
                                FROM rating r 
                                JOIN pelanggan p ON r.id_Pelanggan = p.id_Pelanggan 
                                JOIN menu m ON r.id_Menu = m.id_Menu 
                                ORDER BY tanggal_rating DESC LIMIT 5");

                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>
                                    <td class='px-4 py-2 border-b'>{$row['nama_pelanggan']}</td>
                                    <td class='px-4 py-2 border-b'>{$row['nama_menu']}</td>
                                    <td class='px-4 py-2 border-b text-yellow-500'>{$row['rating']} ★</td>
                                    <td class='px-4 py-2 border-b'>{$row['tanggal_rating']}</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!-- End Main Content -->

</body>

</html>