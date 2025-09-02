<?php
session_start();
include('../service/database.php');


// Statistik 
$statistikQuery = "
    SELECT 
        COUNT(DISTINCT p.kode_Order) AS total_transaksi,
        SUM(CASE WHEN t.status_pembayaran = 'paid' THEN p.total_Harga ELSE 0 END) AS total_pendapatan,
        COUNT(DISTINCT CASE WHEN t.status_pembayaran = 'paid' THEN p.kode_Order END) AS transaksi_berhasil,
        COUNT(DISTINCT CASE WHEN t.status_pembayaran IN ('failed', 'expired') THEN p.kode_Order END) AS transaksi_gagal,
        COUNT(DISTINCT CASE WHEN t.status_pembayaran = 'pending' THEN p.kode_Order END) AS transaksi_pending
    FROM pesan p
    JOIN transaksi t ON p.kode_Order = t.kode_Order";


$statsRes = $db->query($statistikQuery);
if (!$statsRes) 
{
    die("Query error: " . $db->error);
}

$stat = $statsRes->fetch_assoc();

// Query data laporan
$query = "SELECT
            p.kode_Order AS kode_Order,
            MIN(p.tanggal_Pesan) AS tanggal_Pesan,
            SUM(p.total_Harga) AS total_Harga,
            MAX(t.jumlah_dibayar) AS jumlah_dibayar,
            MAX(t.status_pembayaran) AS status_pembayaran,
            MAX(t.tanggal_bayar) AS tanggal_bayar
          FROM pesan p
          JOIN transaksi t ON p.kode_Order = t.kode_Order
          GROUP BY p.kode_Order
          ORDER BY tanggal_Pesan DESC";

$result = $db->query($query);
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
            <h1 class="text-3xl font-bold mb-6">Dashboard Report</h1>
    
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-10">
        <div class="bg-white p-4 rounded shadow-lg text-center">
            <h3 class="text-gray-600">Total Transaksi</h3>
            <p class="text-2xl font-semibold"><?= number_format($stat['total_transaksi']) ?></p>
        </div>
        <div class="bg-white p-4 rounded shadow-lg text-center">
            <h3 class="text-gray-600">Total Pendapatan</h3>
            <p class="text-2xl font-semibold">Rp <?= number_format($stat['total_pendapatan'], 0, ',', '.') ?></p>
        </div>
        <div class="bg-white p-4 rounded shadow-lg text-center">
            <h3 class="text-gray-600">Transaksi Berhasil</h3>
            <p class="text-2xl font-semibold"><?= number_format($stat['transaksi_berhasil']) ?></p>
        </div>
        <div class="bg-white p-4 rounded shadow-lg text-center">
            <h3 class="text-gray-600">Transaksi Gagal</h3>
            <p class="text-2xl font-semibold"><?= number_format($stat['transaksi_gagal']) ?></p>
        </div>
        <div class="bg-white p-4 rounded shadow-lg text-center">
            <h3 class="text-gray-600">Transaksi Pending</h3>
            <p class="text-2xl font-semibold"><?= number_format($stat['transaksi_pending']) ?></p>
        </div>
    </div>

    <!-- Tabel Laporan Pesanan -->
    <h2 class="text-2xl font-bold mb-4">Laporan Pesanan</h2>
    <table class="min-w-full bg-white shadow rounded-lg border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-3 px-6 text-center border border-gray-300">Kode Order</th>
                <th class="py-3 px-6 text-center border border-gray-300">Tanggal Pesan</th>
                <th class="py-3 px-6 text-center border border-gray-300">Total Harga</th>
                <th class="py-3 px-6 text-center border border-gray-300">Jumlah Dibayar</th>
                <th class="py-3 px-6 text-center border border-gray-300">Status Pembayaran</th>
                <th class="py-3 px-6 text-center border border-gray-300">Tanggal Bayar</th>

            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0) : ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr class="bg-gray-100">
                        <td class="py-3 px-6 text-center border border-gray-300"><?= htmlspecialchars($row['kode_Order']) ?></td>
                        <td class="py-3 px-6 text-center border border-gray-300"><?= htmlspecialchars($row['tanggal_Pesan']) ?></td>
                        <td class="py-3 px-6 text-center border border-gray-300"><?= number_format($row['total_Harga'], 0, ',', '.') ?></td>
                        <td class="py-3 px-6 text-center border border-gray-300"><?= number_format($row['jumlah_dibayar'], 0, ',', '.') ?></td>
                        <td class="py-3 px-6 text-center border border-gray-300"><?= htmlspecialchars(ucfirst($row['status_pembayaran'])) ?></td>
                        <td class="py-3 px-6 text-center border border-gray-300"><?= htmlspecialchars($row['tanggal_bayar']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="text-center py-4">Data tidak ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?>
    <!-- Sidebar Content End -->

</body>

</html>