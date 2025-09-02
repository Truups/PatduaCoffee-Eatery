<?php
session_start();
include('../service/database.php');


if (!isset($_GET['id']) || empty($_GET['id'])) 
{
    die("ID Pesanan tidak ditemukan.");
}

$id_pesan = $_GET['id'];

// Cek apakah ID pesan valid dan dapatkan kode order
$sql = "SELECT kode_Order FROM pesan WHERE id_Pesan = ?";
$stmt = $db -> prepare($sql);
$stmt ->bind_param("i",$id_pesan);
$stmt -> execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) 
{
    $detes = $result->fetch_assoc();
    $kode_order = $detes['kode_Order'];
} else 
{
    die("Pesanan tidak ditemukan.");
}

// Query untuk mengambil semua detail pesanan dengan kode order yang sama
$sql = "SELECT pesan.kode_Order, pelanggan.nama_Pelanggan, menu.nama_Menu, pesan.jumlah_Pesan, pesan.total_Harga, pesan.status_Pesan
        FROM pesan
        INNER JOIN pelanggan ON pesan.id_Pelanggan = pelanggan.id_Pelanggan
        INNER JOIN menu ON pesan.id_Menu = menu.id_Menu
        WHERE pesan.kode_Order = '$kode_order'";

$result = $db->query($sql);

// Cek apakah ada data pesanan
if ($result->num_rows == 0) 
{
    die("Detail order tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>


    <div class="flex flex-wrap items-center justify-center h-screen bg-gray-200">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg text-center">
        <h3 class="text-green-600 text-2xl font-bold mb-4">Detail Order</h3>

        <div class="text-left space-y-3">
            <p><strong>Kode Order:</strong> <?= $kode_order ?></p>
        </div>
        <div class="overflow-x-auto">

        </div>
        <table class="w-full border-collapse border border-gray-400 mt-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-400 px-4 py-2">Nama Menu</th>
                    <th class="border border-gray-400 px-4 py-2">Jumlah</th>
                    <th class="border border-gray-400 px-4 py-2">Total Harga</th>
                    <th class="border border-gray-400 px-4 py-2">Status Order</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td class='border border-gray-400 px-4 py-2'>{$row['nama_Menu']}</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['jumlah_Pesan']}</td>
                            <td class='border border-gray-400 px-4 py-2'>Rp " . number_format($row['total_Harga'], 0, ',', '.') . "</td>
                            <td class='border border-gray-400 px-4 py-2'>{$row['status_Pesan']}</td>
                        
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <button onclick="window.location.href='/order.php'" class="mt-6 w-full py-2 bg-green-600 text-white rounded hover:bg-green-500">
            Kembali
        </button>
    </div>

    </div>
    

</body>
</html>
