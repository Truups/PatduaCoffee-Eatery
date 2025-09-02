<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/service/database.php');

$kodeOrder = $_GET['order'] ?? '';

if ($kodeOrder) 
{
    //Update status pembayaran
    $stmt = $db->prepare("UPDATE transaksi SET status_pembayaran = 'failed' WHERE kode_Order = ?");
    $stmt->bind_param("s", $kodeOrder);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="../img/logo.svg" rel="icon" type="image/x-icon">
</head>

<body>
    <div class="bg-red-50">
        <div class="min-h-screen flex items-center justify-center">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8 text-center">
                <!-- Icon X untuk gagal -->
                <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-red-600 mb-2">Pembayaran Gagal</h1>
                <p class="text-gray-600 mb-2">Order: <strong><?= htmlspecialchars($kodeOrder) ?></strong></p>
                <p class="text-gray-600 mb-6">Pembayaran tidak dapat diproses. Silahkan coba lagi atau hubungi kasir.</p>
                
                <div class="space-y-3">
                    <a href="/struk" class="block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                        Coba Bayar Lagi
                    </a>
                    <a href="/index" class="block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>