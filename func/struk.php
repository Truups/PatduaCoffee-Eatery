<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/service/database.php');

if(!isset($_SESSION['id_Pelanggan'])) die("Pelanggan tidak ditemukan.");

$kodeOrder = $_SESSION['orderAkhir'] ?? '';
if (!$kodeOrder) die("Tidak ada order yang masuk.");

//Ambil data pesanan
$q = $db->query("SELECT m.nama_Menu, p.jumlah_Pesan, p.total_Harga, p.id_Menu
                 FROM pesan p
                 JOIN menu m ON p.id_Menu = m.id_Menu
                 WHERE p.kode_Order = '$kodeOrder'");

$pesanan = $q->fetch_all(MYSQLI_ASSOC);

//Hitung total pembayaran
$total_all = 0;
foreach($pesanan as $p)
{
    $total_all += $p['total_Harga'];
}

//Function Xendit
function xenditInvoice($kodeOrder, $totalAmount, $customerEmail = 'customer@example.com')
{
    $xendit_API = 'xnd_development_QbcEZwgrcpavmE0tmd78XksWW7An7V3my6yOTT0fK47Aax95q3ivJylZ9B9hFbE';
    //URL untuk webhook
    $base_Url = 'https://patdua.store';

    $invoice_data = 
    [
        'external_id' => $kodeOrder,
        'amount' => $totalAmount,
        'description' => 'Pembayaran Order ' . $kodeOrder,
        'invoice_duration' => 86400,
        'customer' => [
            'email' => $customerEmail,
        ],
        'success_redirect_url' => $base_Url . '/pemb_sukses?order=' . $kodeOrder,
        'failure_redirect_url' => $base_Url . '/pemb_gagal?order=' . $kodeOrder,
        'should_send_email' => true
    ];

    //Kirim Curl
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.xendit.co/v2/invoices',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($invoice_data),
        CURLOPT_HTTPHEADER => 
        [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($xendit_API . ':')
        ],
    ]);

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $decoded_response = json_decode($response, true);
    
    // Debug untuk development
    if ($http_code !== 200) 
    {
        error_log("Xendit API Error: HTTP $http_code - " . $response);
    }

    return $decoded_response;
}

$invoice_url = '';
$error_msg = '';

//Cek request untuk membayar
if(isset($_POST['bayar_xendit'])) 
{
    $invoice_response = xenditInvoice($kodeOrder, $total_all);

    if(isset($invoice_response['invoice_url'])) 
    {
        $invoice_id = $invoice_response['id'];
        
        $stmt = $db->prepare("INSERT INTO transaksi (kode_Order, xendit_invoice_id, status_pembayaran, jumlah_dibayar) 
                             VALUES (?, ?, 'pending', ?) 
                             ON DUPLICATE KEY UPDATE 
                             xendit_invoice_id = VALUES(xendit_invoice_id), 
                             status_pembayaran = 'pending',
                             jumlah_dibayar = VALUES(jumlah_dibayar),
                             updated_at = CURRENT_TIMESTAMP");
        $stmt->bind_param("ssd", $kodeOrder, $invoice_id, $total_all);
        
        if($stmt->execute()) 
        {
            //Arahkan ke halaman pembayaran xendit
            header("Location: " . $invoice_response['invoice_url']);
            exit();
        } else 
        {
            $error_msg = "Gagal menyimpan data transaksi. Silahkan coba lagi.";
        }
    } else 
    {
        $error_msg = "Gagal membuat invoice pembayaran. Silahkan coba lagi.";
        // Debug: tampilkan response untuk troubleshooting
        if(isset($invoice_response['message'])) 
        {
            $error_msg .= " Error: " . $invoice_response['message'];
        }
        if(isset($invoice_response['error_code'])) 
        {
            $error_msg .= " Code: " . $invoice_response['error_code'];
        }
    }
}

//Cek status pembayaran yang sudah ada
$status_pembayaran = 'belum_bayar';
$check_payment = $db->prepare("SELECT status_pembayaran FROM transaksi WHERE kode_Order = ?");
$check_payment->bind_param("s", $kodeOrder);
$check_payment->execute();
$payment_result = $check_payment->get_result();
if($payment_result->num_rows > 0) 
{
    $payment_data = $payment_result->fetch_assoc();
    $status_pembayaran = $payment_data['status_pembayaran'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="../img/logo.svg" rel="icon" type="image/x-icon">
</head>

<body>
    <div class="p-8 bg-white text-black">
        <div class="max-w-md mx-auto border p-4 ">
            <div class="text-center w-full">
                <h1 class="text-xl font-bold bg-green-600 rounded-lg w-full text-white m-0 px-0 py-2 ">Struk Pembayaran</h1>
            </div>
            <p>Kode Order: <strong><?= $kodeOrder ?></strong></p>
            
            
            <hr class="my-2">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left">Menu</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesanan as $p): ?>
                        <tr>
                            <td><?= $p['nama_Menu'] ?></td>
                            <td class="text-center"><?= $p['jumlah_Pesan'] ?></td>
                            <td class="text-right">Rp<?= number_format($p['total_Harga'])  ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <hr class="my-2">
            <p class="text-right font-bold">Grand Total: Rp<?= number_format($total_all) ?></p>

            <?php if (!empty($error_msg)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
                    <?= $error_msg ?>
                </div>
            <?php endif; ?>
        </div>

     <div class="max-w-md mx-auto mt-4">
            <div class="bg-green-50 border border-black rounded-lg p-4">
                <h3 class="font-bold text-center mb-4">Pilih Metode Pembayaran</h3>
                
                <?php if($status_pembayaran == 'paid'): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center font-bold">
                        ✓ Pembayaran Berhasil
                    </div>
                <?php else: ?>
                    <!-- Xendit Payment -->
                     <form method="POST" class="mb-4">
                        <button type="submit" name="bayar_xendit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg mb-2 flex items-center justify-center"
                        <?= $status_pembayaran == 'pending' ? 'disabled' : '' ?>>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <?= $status_pembayaran == 'pending' ? 'Menunggu Pembayaran...' : 'Bayar Online (Xendit)' ?>
                        </button>
                     </form>

                    <p class="text-xs text-gray-600 text-center mb-4">
                        Metode pembayaran: Virtual Account, E-Wallet, QRIS
                    </p>
                <?php endif; ?>

                <!-- Check status pembayaran -->
                <a href="/cek_payment_status?order=<?= $kodeOrder ?>" 
                   class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg mb-4 block text-center">
                    Cek Status Pembayaran
                </a>
                
                <hr class="my-4">

                <!-- Pembayaran Manual -->
                 <p class="text-center text-sm text-gray-600 mb-4">
                    Atau silahkan kunjungi kasir untuk pembayaran Tunai.
                 </p>
            </div>
        </div>

        <div class="max-w-md mx-auto text-center mt-4">
           <button onclick="goBeranda()" 
           class="flex justify-self-center text-center bg-green-600 hover:bg-green-700 rounded-full text-white px-4 py-2 font-medium mt-4 ">Beranda</button>  
        </div>        

        <script>
            function goBeranda() 
            {
                const menuP = <?= json_encode(array_map(function ($p) 
                {
                    return 
                    [
                        'nama' => $p['nama_Menu'],
                        'id_Menu' => $p['id_Menu'],
                        'id_Pelanggan' => $_SESSION['id_Pelanggan'] ?? null
                    ];
                }, $pesanan)) ?>;

                localStorage.setItem('menuP', JSON.stringify(menuP));
                window.location.href = "/index.php";
            }
        </script>
    </div>
</body>
</html>