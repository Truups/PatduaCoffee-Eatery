<?php
// check_payment_status.php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/service/database.php');

// Function untuk cek status invoice dari Xendit
function checkInvoiceStatus($invoice_id) 
{
    $xendit_api_key = 'xnd_development_QbcEZwgrcpavmE0tmd78XksWW7An7V3my6yOTT0fK47Aax95q3ivJylZ9B9hFbE';
    
    $curl = curl_init();
    curl_setopt_array($curl, 
    [
        CURLOPT_URL => 'https://api.xendit.co/v2/invoices/' . $invoice_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => 
        [
            'Authorization: Basic ' . base64_encode($xendit_api_key . ':')
        ],
    ]);
    
    $response = curl_exec($curl);

    if (curl_error($curl)) 
    {
        curl_close($curl);
        return false;
    }
    
    return json_decode($response, true);
}

$kodeOrder = $_GET['order'] ?? '';
$message = '';
$status = '';

if ($kodeOrder) 
{
    // Ambil invoice ID dari database
    $stmt = $db->prepare("SELECT xendit_invoice_id, status_pembayaran FROM transaksi WHERE kode_Order = ? LIMIT 1");
    if (!$stmt) 
    {
    die("Prepare failed: " . $db->error);
    }
    $stmt->bind_param("s", $kodeOrder);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    
    if ($order && $order['xendit_invoice_id']) 
    {
        $invoice_status = checkInvoiceStatus($order['xendit_invoice_id']);
        
        if ($invoice_status) 
        {
            $xendit_status = $invoice_status['status'];
            $current_db_status = $order['status_pembayaran'];
            
            // Update status jika berbeda
        if (($xendit_status == 'PAID' || $xendit_status == 'SETTLED') && $current_db_status != 'paid') 
            {
                $stmt = $db->prepare("UPDATE transaksi SET status_pembayaran = 'paid', tanggal_bayar = NOW() WHERE kode_Order = ?");
                $stmt->bind_param("s", $kodeOrder);
                $stmt->execute();
                $status = 'paid';
                $message = 'Pembayaran berhasil dikonfirmasi!';
            } 
        elseif ($xendit_status == 'EXPIRED' && $current_db_status != 'expired') 
            {
                $stmt = $db->prepare("UPDATE transaksi SET status_pembayaran = 'expired' WHERE kode_Order = ?");
                $stmt->bind_param("s", $kodeOrder);
                $stmt->execute();
                $status = 'expired';
                $message = 'Invoice pembayaran telah expired.';
            } 
        elseif ($xendit_status == 'PAID' || $xendit_status == 'SETTLED') 
            {
                $status = 'paid';
                $message = 'Pembayaran sudah dikonfirmasi sebelumnya.';
            } 
        else 
            {
                $status = 'pending';
                $message = 'Pembayaran masih pending. Status: ' . $xendit_status;
            }
        } else 
        {
            $message = 'Tidak dapat mengecek status pembayaran.';
        }
    } else 
    {
        $message = 'Order tidak ditemukan atau belum ada invoice.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pembayaran</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-center mb-6">Cek Status Pembayaran</h1>
            
            <?php if ($kodeOrder): ?>
                <div class="text-center mb-4">
                    <p class="text-gray-600 mb-2">Order: <strong><?= htmlspecialchars($kodeOrder) ?></strong></p>
                    
                    <?php if ($status == 'paid'): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="font-bold">Pembayaran Berhasil!</p>
                        </div>
                    <?php elseif ($status == 'expired'): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="font-bold">Invoice Expired</p>
                        </div>
                    <?php else: ?>
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="font-bold">Menunggu Pembayaran</p>
                        </div>
                    <?php endif; ?>
                    
                    <p class="text-gray-600 mb-6"><?= $message ?></p>
                </div>
                
                <div class="space-y-3">
                    <button onclick="checkAgain()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg">
                        Cek Ulang Status
                    </button>
                    
                    <?php if ($status == 'paid' || $status == 'pending'): ?>
                        <a href="/struk" class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg text-center">
                            Kembali ke Struk
                        </a>
                    <?php endif; ?>
                    
                    <a href="/index" class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg text-center">
                        Kembali ke Beranda
                    </a>
                </div>
                
            <?php else: ?>
                <p class="text-center text-red-600 mb-6">Kode order tidak ditemukan.</p>
                <a href="/index" class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg text-center">
                    Kembali ke Beranda
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function checkAgain() 
        {
            window.location.reload();
        }
        
        // Auto refresh setiap 30 detik jika status masih pending
        <?php if ($status == 'pending'): ?>
        setTimeout(function() 
        {
            window.location.reload();
        }, 30000);
        <?php endif; ?>
    </script>
</body>
</html>