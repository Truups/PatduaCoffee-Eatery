<?php 
include('../service/database.php');

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

$query = "SELECT p.id_Pesan, p.kode_Order, pl.nama_Pelanggan,
                 SUM(p.total_Harga) AS total_Harga,
                 MIN(p.tanggal_Pesan) AS tanggal_Pesan,
                 p.status_Pesan
          FROM pesan p
          INNER JOIN pelanggan pl ON p.id_Pelanggan = pl.id_Pelanggan";

if (!empty($cari)) 
{
    $cari = $db->real_escape_string($cari);
    $query .= " WHERE p.kode_Order LIKE '%$cari%' OR pl.nama_Pelanggan LIKE '%$cari%'";
}

$query .= " GROUP BY p.kode_Order, pl.nama_Pelanggan, p.status_Pesan";

$result = $db->query($query);

if (!$result) {
    die("Query gagal: " . $db->error);
}

$number = 1;

if ($result->num_rows > 0) 
{
    while ($row = $result->fetch_assoc()) 
    {
        echo "<tr class='hover:bg-gray-100 text-center'>";
        echo "<td class='border border-gray-300 px-4 py-2 '>" . $number++ . "</td>";
        echo "<td class='border border-gray-300 px-4 py-2'>" . $row['kode_Order'] . "</td>";
        echo "<td class='border border-gray-300 px-4 py-2'>" . $row['nama_Pelanggan'] . "</td>";
        echo "<td class='border border-gray-300 px-4 py-2 '>Rp " . number_format($row['total_Harga'], 0, ',', '.') . "</td>";
        echo "<td class='border border-gray-300 px-4 py-2'>" . $row['tanggal_Pesan'] . "</td>";
        echo "<td class='border border-gray-300 px-4 py-2 statusCell'>" . $row['status_Pesan'] . "</td>";
        echo "<td class='border border-gray-300 px-4 py-2 text-center'>";
        echo "<a href='/edit_Order.php?id=" . $row['id_Pesan'] . "' class='text-green-600 hover:underline px-2'>Edit</a>";
        echo "<a href='/detes_Order.php?id=" . $row['id_Pesan'] . "' class='text-green-600 hover:underline px-2'>Detail</a>";
        echo "<a href='/hapus_Order.php?id=" . $row['id_Pesan'] . "' class='text-red-600 hover:underline px-2' onclick='return confirm(\"Apakah Anda yakin ingin menghapus menu ini?\")'>Hapus</a>";
        echo "</td>";
        echo "</tr>";
    }
} 
else 
{
    echo "<tr><td colspan='7' class='text-center py-4'>Tidak ada data menu</td></tr>";
}

?>
