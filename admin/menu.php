<?php
session_start();

include('../service/database.php');

$search = isset($_GET['search']) ? $db->real_escape_string($_GET['search']) : '';
if (!empty($search)) 
{
    $sql = "SELECT * FROM menu WHERE nama_Menu LIKE LOWER('%$search%')
            OR katg_Menu LIKE LOWER('%$search%')";
} else 
{
    $sql = "SELECT * FROM menu";
}
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
    <script src="/modal.js"></script>
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
                <a href="" class="px-4 py-2 font-medium hover:bg-white hover:text-green-600 rounded"> <?php echo $_SESSION["username"] ?></a>
                <a href="/logout.php" class="px-4 py-2 font-medium hover:bg-white hover:text-green-600 rounded">Logout</a>
            </div>

        </nav>

    </div>
    <!-- Navbar End -->


    <!-- Sidebar Content -->

    <div class="flex">
        <?php include '../func/sidebar.php' ?>
        <div class="flex-2 p-6">
            <h2 class="text-2xl font-bold">Menu</h2>

            <!-- Search Bar Menu Start -->
            <form method="GET" class=" flex item-center mb-2 mt-2">
                <input type="text" name="search" placeholder="Cari Menu" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="border border-gray-300 rounded-1 px-4 py-2 w-64">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-r hover:bg-green-500">Cari</button>
            </form>
            <!-- Search Bar Menu End -->

            <div class="container mx-auto p-0">
                <div class="flex justify-end mr-4">
                    <button onclick="openModal()" type="button" class="text-white px-6 py-2 font-medium bg-green-600 hover:bg-green-500 rounded-full">Tambah Menu</button>
                </div>
                <div class="bg-white p-4 shadow-md rounded-lg">
                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-gray-300 px-4 py-2">No</th>
                                <th class="border border-gray-300 px-4 py-2">Nama Menu</th>
                                <th class="border border-gray-300 px-4 py-2">Gambar</th>
                                <th class="border border-gray-300 px-4 py-2">Kategori</th>
                                <th class="border border-gray-300 px-4 py-2">Harga</th>
                                <th class="border border-gray-300 px-4 py-2">Aksi</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) 
                            {
                                while ($row = $result->fetch_assoc()) 
                                {
                                    echo "<tr class='hover:bg-gray-100 '>";
                                    echo "<td class='border border-gray-300 px-4 py-2 text-center'>" . $number++ . "</td>";
                                    echo "<td class='border border-gray-300 px-4 py-2'>" . $row['nama_Menu'] . "</td>";
                                    echo "<td class='border border-gray-300 px-4 py-2'> <img src='https://patdua.store/img/" . htmlspecialchars($row['gamb_Menu']) . "' alt='Gambar Menu' width='100'></td>";
                                    echo "<td class='border border-gray-300 px-4 py-2'>" . $row['katg_Menu'] . "</td>";
                                    echo "<td class='border border-gray-300 px-4 py-2 text-right'>Rp " . number_format($row['harga_Menu'], 0, ',', '.') . "</td>";
                                    echo "<td class='border border-gray-300 px-4 py-2 text-center'>";
                                    echo "<a href='/edit_Menu.php?id=" . $row['id_Menu'] . "' class='text-green-600 hover:underline px-2'>Edit</a>";
                                    echo "<a href='/hapus_Menu.php?id=" . $row['id_Menu'] . "' class='text-red-600 hover:underline px-2' onclick='return confirm(\"Apakah Anda yakin ingin menghapus menu ini?\")'>Hapus</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else 
                            {
                                echo "<tr><td colspan='4' class='text-center py-4'>Tidak ada data menu</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>


    <!-- Sidebar Content End -->


    <!-- Modal Component Start -->
    <div id="modal" class="fixed inset-0 bg-gray-600/50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">

            <!-- Tambah Menu Start -->
            <form action="/inputMenu.php" method="POST" enctype="multipart/form-data">
                <h2 class="text-green-600 text-xl font-bold mb-4">Tambah Menu</h2>
                <label for="namaMenu" class="block mb-2">Nama Menu:</label>
                <input name="nama_Menu" id="namaMenu" type="text" class="w-full border border-gray-300 p-2 rounded mb-4">
                <label for="gambar" class="block mb-2">Gambar:</label>
                <input name="gamb_Menu" id="gambar" type="file" class="w-full border border-gray-300 p-2 rounded mb-4 file:bg-green-600 file:text-white file:px-3 file:py-1 file:rounded-full file:cursor-pointer">
                <label for="kategori" class="block mb-2">Kategori:</label>
                <select name="katg_Menu" id="kategori" class="w-full border border-gray-300 p-2 rounded mb-4">
                    <option selected hidden>Pilih Kategori</option>
                    <option value="Snack">Snack</option>
                    <option value="Food">Food</option>
                    <option value="Drink">Drink</option>
                </select>
                <label for="harga" class="block mb-2">Harga:</label>
                <input name="harga_Menu" id="harga" type="number" class="w-full border border-gray-300 p-2 rounded mb-4">
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg mr-2">Batal</button>
                    <button name="submit" type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg">Simpan</button>
                </div>
            </form>
            <!-- Tambah Menu End -->
        </div>
    </div>
    <!-- Modal Component End -->

</body>

</html>