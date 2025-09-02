<?php
include('../service/database.php');

$pencarian = isset($_GET['cari']) ? trim($_GET['cari']) : '';

if($pencarian != '')
{
    $cari_esc = $db->real_escape_string($pencarian);
    $sql = "SELECT * FROM pelanggan
            WHERE nama_Pelanggan LIKE LOWER('%$cari_esc%')
            OR telp_Pelanggan LIKE LOWER('%$cari_esc%')
            OR email_Pelanggan LIKE LOWER('%$cari_esc$')";
}else
{
    $sql = "SELECT * FROM pelanggan";
}

$result = $db->query($sql);
$nomor = 1;

if ($result->num_rows > 0) 
{
    while ($row = $result->fetch_assoc()) 
    {
        echo "<tr class='hover:bg-gray-100 text-center'>";
        echo "<td class='border border-gray-300 px-4 py-2 '>" . $nomor++ . "</td>";
        echo "<td class='border border-gray-300 px-4 py-2'>" . $row['kode_Pelanggan'] . "</td>";
        echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['nama_Pelanggan']) . "</td>";
        echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['telp_Pelanggan']) . "</td>";
        echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['email_Pelanggan']) . "</td>";
        echo "</tr>";
    }
} else 
{
    echo "<tr><td colspan='5' class='text-center py-4'>Tidak ada data pelanggan</td></tr>";
}

$db->close();
?>