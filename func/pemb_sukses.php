<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/service/database.php');

$kodeOrder = $_GET['order'] ?? '';

if ($kodeOrder) 
{
    //Update status pembayaran
    $stmt = $db->prepare("UPDATE transaksi SET status_pembayaran = 'success' WHERE kode_Order = ?");
    $stmt->bind_param("s", $kodeOrder);
    $stmt->execute();

    //ambil data menu untuk 1 orderan ini saja
    $state = $db->prepare("SELECT p.id_Menu, m.nama_Menu, p.id_Pelanggan 
                            FROM pesan p
                            JOIN menu m ON p.id_Menu = m.id_Menu
                            WHERE p.kode_Order= ? ");
    $state->bind_param("s", $kodeOrder);
    $state->execute();
    $result = $state->get_result();

    $menuArr = [];
    while ($row = $result->fetch_assoc()) 
    {
        $menuArr[] = [
            'id_Menu' => $row['id_Menu'],
            'nama' => $row['nama_Menu'],
            'id_Pelanggan' => $row['id_Pelanggan']
        ];
    }
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
    <div class="bg-green-50">
        <div class="min-h-screen flex items-center justify-center">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-green-600 mb-2">Pembayaran Berhasil</h1>
                <p class="text-gray-600 mb-2">Order: <strong><?= htmlspecialchars($kodeOrder) ?></strong></p>
                <p class="text-gray-600 mb-6">Terimakasih atas pembayaran Anda. Pesanan sedang diproses.</p>
                <div class="flex flex-col items-center gap-4 mt-6 w-full">
                <a href="struk?order=<?=urlencode($kodeOrder) ?>"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors mt-4 inline-block">
                    Lihat Struk
                </a>
                <a href="#" id="to-home" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    Kembali ke Beranda
                </a>
                </div>
            </div>
        </div>
    </div>


<script>
    const menuDiPesan = <?= json_encode($menuArr ?? []) ?>;

    document.getElementById('to-home').addEventListener('click', function (e) {
        e.preventDefault();
        if (menuDiPesan.length > 0) {
            localStorage.setItem('menuP', JSON.stringify(menuDiPesan));
        }
        window.location.href = '/index';
    });
</script>
</body>

</html>